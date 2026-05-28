# CLAUDE.md — HRElectrominho

Documentação técnica do sistema para uso por agentes de IA e desenvolvedores.
Última actualização: Maio 2026.

---

## Visão Geral

**HRElectrominho** é um sistema de gestão de recursos humanos (RH) desenvolvido em Laravel 11 com Blade templating. Destina-se à empresa Electrominho e gere funcionários, departamentos, presenças, férias, formações, vídeos, questionários e documentos.

- **URL local**: `http://hreminho.test`
- **Base de dados**: MySQL — `dbhreminho`
- **Framework**: Laravel 11 (bootstrap/app.php — sem Kernel.php)
- **PHP**: 8.3+
- **Frontend**: Blade + CSS custom (dark theme com variáveis CSS), sem framework JS (vanilla JS)

---

## Arquitectura

### Padrão de rotas dual

O sistema usa dois grupos de rotas:

1. **`routes/web.php`** — Rotas Web que devolvem views Blade (autenticação via `middleware('auth')`)
2. **`routes/api.php`** — API JSON prefixada em `/api/v1/`, protegida por `middleware('auth:web')`

As rotas API usam sessão (não tokens), porque o `bootstrap/app.php` prepende os middlewares de sessão ao grupo `api`:

```php
$middleware->api(prepend: [
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
]);
```

### Chamadas fetch() no frontend

Todas as chamadas `fetch()` devem incluir:
```js
credentials: 'same-origin',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
```

---

## Roles de Utilizador

| Role       | Acesso |
|------------|--------|
| `admin`    | Acesso total ao back-office e todas as operações |
| `hr`       | Mesmo acesso que admin (gestão de RH) |
| `employee` | Apenas portal do funcionário (`/employee/dashboard`) |

O redirect pós-login está em `LoginController::login()`:
```php
$default = $role === 'employee' ? route('employee.dashboard') : route('dashboard');
```

---

## Autenticação

### Login dual (email ou código de funcionário)

Ficheiro: `app/Http/Controllers/Auth/LoginController.php`

O campo de login aceita e-mail ou código de funcionário (ex.: `FUN0590`):

```php
if (str_contains($loginValue, '@')) {
    $user = User::where('email', $loginValue)->first();
} else {
    $code = strtoupper($loginValue);
    $employee = Employee::whereRaw('UPPER(code) = ?', [$code])
                         ->whereNotNull('user_id')->first();
    $user = User::find($employee->user_id);
}
Auth::login($user, $remember);
```

- Validação manual com `Hash::check()` + `Auth::login()` (não usa `Auth::attempt()`)
- O placeholder de exemplo usa `FUN0777` (código fictício, não associado a nenhum funcionário real)

---

## Modelos e Relações

### User
```
users: id, name, email, password, role (admin|hr|employee), remember_token
```
- `hasOne(Employee::class)` — link inverso para o registo de funcionário

### Employee
```
employees: id, code, first_name, last_name, email, phone, date_of_birth, gender,
           nationality, address, work_location, profile_photo (longtext/base64),
           position_id, department_id, sector_id, hire_date, status, contract_type,
           end_date, user_id, deleted_at
```
- `belongsTo(User::class)` — conta de utilizador associada
- `belongsTo(Position/Department/Sector)`
- `hasMany(Attendance/Leave/EmployeeTraining)`
- `belongsToMany(Training)` via `employee_trainings`
- Soft deletes activos
- Accessor `getFullNameAttribute()` → `first_name last_name`
- Accessor `getProfilePhotoUrlAttribute()` → URL pública da foto

### Training
```
trainings: id, title, description, provider, has_video (bool), has_quiz (bool)
```
- `hasMany(TrainingVideo)` ordered by `order`
- `hasOne(Quiz)`
- `belongsToMany(Employee)` via `employee_trainings`

### TrainingVideo
```
training_videos: id, training_id, title, url, order, is_uploaded (bool), disk, path
```

### Quiz / QuizQuestion / QuizOption / QuizAttempt / QuizAnswer
```
quizzes: id, training_id, title, description, passing_score (default 70)
quiz_questions: id, quiz_id, question, type (mc|tf), order
quiz_options: id, question_id, text, is_correct (bool), order
quiz_attempts: id, quiz_id, user_id, score, passed (bool), completed_at
quiz_answers: id, attempt_id, question_id, option_id
```

### EmployeeTraining (pivot enriquecido)
```
employee_trainings: id, employee_id, training_id, status, certificate_path,
                    score, start_date, end_date, notes, validity_months
```

### Department / Position / Sector
Estrutura organizacional. `Department` e `Sector` podem ter foreign keys cruzadas.

### Leave / LeaveAttachment
Gestão de pedidos de férias/licenças com anexos.

### Attendance
Registo de presenças por funcionário.

---

## Associação Funcionário ↔ Utilizador

### Fluxo admin (back-office)
- Na listagem de funcionários, cada linha tem um botão 🔗 (verde = sem conta, laranja = tem conta)
- Abre modal rápido com dropdown de utilizadores `role=employee` ainda não associados
- PUT `/api/v1/employees/{id}` com `{ user_id: X }`
- O dropdown filtra `usedUserIds` para não mostrar utilizadores já associados a outros funcionários

### Fluxo funcionário (portal)
- Banner no dashboard do portal quando `$employee` é null
- Funcionário insere o seu código (ex.: `FUN0590`)
- POST `/api/v1/employee-portal/associate` → `EmployeeAssociationController::associate()`
- Página recarrega após sucesso

### Geração em massa de contas
- Botão "Gerar Acessos" na toolbar da listagem de funcionários
- POST `/api/v1/employees/bulk-create-users` → `BulkUserController::createEmployeeUsers()`
- Password padrão: `12345678`
- Lógica:
  1. Funcionário com email que já tem utilizador → liga `user_id` (contado como `$linked`)
  2. Funcionário com email sem utilizador → cria utilizador com esse email
  3. Funcionário sem email → cria email interno `funXXXX@hrelectrominho.local`
- Artisan: `php artisan employees:create-users [--dry-run]`

---

## Formações — Vídeos e Questionários

### Back-office (admin/hr)
- Modal "Conteúdo" na listagem de formações
- Upload ou URL de vídeos; opção `is_uploaded` distingue ficheiro local vs URL externa
- Editor de quiz com perguntas e opções (MC ou Verdadeiro/Falso)
- Botão "📊 Resultados" por formação (quando `has_quiz = true`) — modal com:
  - Sumário: total de participantes, aprovados, média de pontuação
  - Tabela filtrável por nome/código e por estado (aprovado/reprovado)
  - Melhor pontuação por utilizador (não a última)

### Portal do Funcionário
- `/employee/dashboard` — lista formações com vídeos/quiz disponíveis
- `/employee/training/{training}` — player de vídeo + quiz
- Quiz bloqueado até todos os vídeos serem vistos (marcação no frontend via `Set`)
- Respostas certas ocultas para `role=employee`
- Histórico de tentativas por formação

---

## API Endpoints Principais

Prefixo: `/api/v1/` — todos requerem sessão autenticada (`auth:web`)

### Recursos CRUD
| Recurso        | Rota                          |
|----------------|-------------------------------|
| Employees      | `/employees` (apiResource)    |
| Departments    | `/departments` (apiResource)  |
| Positions      | `/positions` (apiResource)    |
| Sectors        | `/sectors` (apiResource)      |
| Attendances    | `/attendances` (apiResource)  |
| Leaves         | `/leaves` (apiResource)       |
| Trainings      | `/trainings` (apiResource)    |
| Users          | `/users` (apiResource)        |

### Inscrições em Formações
| Método | Rota                          | Acção               |
|--------|-------------------------------|---------------------|
| GET    | `/enrollments`                | Listar inscrições   |
| POST   | `/enrollments`                | Inscrever           |
| PUT    | `/enrollments/{enrollment}`   | Actualizar          |
| DELETE | `/enrollments/{enrollment}`   | Remover             |

### Vídeos
| Método | Rota                                        |
|--------|---------------------------------------------|
| *      | `/trainings/{training}/videos` (apiResource, shallow) |

### Quiz
| Método | Rota                                        | Acção                          |
|--------|---------------------------------------------|--------------------------------|
| GET    | `/trainings/{training}/quiz`                | Ver quiz (respostas ocultas para employee) |
| POST   | `/trainings/{training}/quiz`                | Criar quiz (admin/hr)          |
| PUT    | `/trainings/{training}/quiz`                | Actualizar quiz (admin/hr)     |
| GET    | `/trainings/{training}/quiz/results`        | Resultados por utilizador (admin/hr) |
| POST   | `/quiz/{training}/attempt`                  | Submeter tentativa             |
| GET    | `/quiz/{training}/my-attempts`              | Histórico de tentativas        |

### Outros
| Método | Rota                                        | Acção                          |
|--------|---------------------------------------------|--------------------------------|
| POST   | `/employee-portal/associate`                | Associar funcionário por código |
| POST   | `/employees/bulk-create-users`              | Criar contas em massa          |
| GET    | `/reports/completed-trainings`              | Relatório formações concluídas |
| GET    | `/reports/employees-with-trainings`         | Funcionários com formações     |
| GET    | `/reports/attendance-summary`               | Sumário de presenças           |

---

## Rotas Web

| Rota                              | Controller                          | Notas                        |
|-----------------------------------|-------------------------------------|------------------------------|
| `/`                               | —                                   | Página de apresentação       |
| `/login` / POST `/login`          | `LoginController`                   | Login dual (email ou código) |
| `/dashboard`                      | `DashboardController`               | Back-office (admin/hr)       |
| `/employees`                      | `EmployeeWebController`             |                              |
| `/departments`                    | `DepartmentWebController`           |                              |
| `/positions`                      | `PositionWebController`             |                              |
| `/sectors`                        | `SectorWebController`               |                              |
| `/attendances`                    | `AttendanceWebController`           |                              |
| `/leaves`                         | `LeaveWebController`                |                              |
| `/trainings`                      | `TrainingWebController`             |                              |
| `/users`                          | `UserWebController`                 |                              |
| `/reports`                        | `ReportWebController`               |                              |
| `/calendar`                       | `CalendarWebController`             |                              |
| `/password`                       | `PasswordWebController`             | PUT — alterar password       |
| `/docsem/*`                       | `DocsElectroMinhoWebController`     | Integração externa           |
| `/employee/dashboard`             | `EmployeePortalController`          | Portal funcionário           |
| `/employee/training/{training}`   | `EmployeePortalController`          | Vídeo + quiz                 |

---

## Integração DocsElectroMinho

Integração com sistema externo de gestão documental de subcontratadas.

- **Config**: variáveis `.env` — `DOCSEM_API_URL`, `DOCSEM_API_TOKEN`, `DOCSEM_SYNC_ENABLED`
- **Service**: `app/Services/DocsElectroMinhoService.php`
- **Controller**: `app/Http/Controllers/Web/DocsElectroMinhoWebController.php`
- Sincroniza funcionários activos para o sistema externo
- Permite sincronização global ou por funcionário individual
- Página de estado em `/docsem`

---

## Comandos Artisan Personalizados

| Comando                                          | Descrição                                         |
|--------------------------------------------------|---------------------------------------------------|
| `php artisan employees:create-users [--dry-run]` | Cria contas para todos os funcionários activos sem utilizador associado. Password padrão: `12345678`. |
| `php artisan docsem:sync`                        | Sincroniza funcionários com o sistema DocsElectroMinho. |

---

## Views e Layouts

### Layouts
- `layouts/app.blade.php` — layout principal do back-office (sidebar + navbar)
- `layouts/guest.blade.php` — layout para páginas públicas (login, home)
- `layouts/employee.blade.php` — layout do portal do funcionário

### Principais Views
| View                           | Descrição                                          |
|--------------------------------|----------------------------------------------------|
| `auth/login.blade.php`         | Formulário de login (campo `login`, tipo `text`)  |
| `dashboard/index.blade.php`    | Dashboard back-office com métricas                |
| `employees/index.blade.php`    | CRUD funcionários + associação rápida + "Gerar Acessos" |
| `trainings/index.blade.php`    | CRUD formações + conteúdo (vídeos/quiz) + resultados |
| `users/index.blade.php`        | CRUD utilizadores do sistema                      |
| `employee/dashboard.blade.php` | Portal do funcionário (perfil + banner associação) |
| `employee/training.blade.php`  | Player de vídeo + questionário                    |
| `reports/index.blade.php`      | Relatórios (formações, presenças, etc.)           |
| `docsem/index.blade.php`       | Estado da integração DocsElectroMinho             |

---

## Migrations (por ordem)

| Ficheiro                                                    | Descrição                                  |
|-------------------------------------------------------------|--------------------------------------------|
| `0001_01_01_000000_create_users_table`                      | Tabela users + sessions + password_resets  |
| `2024_01_01_000001` a `000010`                              | Estrutura base: positions, departments, sectors, employees, attendances, leaves, trainings, employee_trainings |
| `2026_04_25_182834_add_work_location_to_employees_table`    | Campo `work_location` nos funcionários     |
| `2026_04_25_185356_make_employee_nullable_fields`           | Tornar campos opcionais nos funcionários   |
| `2026_04_26_000001_change_profile_photo_to_longtext`        | Foto de perfil como base64 (longtext)      |
| `2026_04_28_000001_add_validity_months_to_employee_trainings` | Validade de certificados                 |
| `2026_05_04_161603_add_deleted_at_to_employees_table`       | Soft deletes nos funcionários              |
| `2026_05_26_000001_normalize_user_role_enum`                | Normalizar enum de roles                   |
| `2026_05_26_000002_create_training_videos_table`            | Vídeos de formação                         |
| `2026_05_26_000003_create_quiz_tables`                      | Quiz, perguntas, opções, tentativas, respostas |
| `2026_05_27_000001_add_has_video_has_quiz_to_trainings`     | Flags `has_video` e `has_quiz`             |
| `2026_05_27_000002_add_is_uploaded_to_training_videos`      | Flag `is_uploaded` para distinguir upload/URL |
| `2026_05_27_000003_add_user_id_to_employees_table`          | Associação `user_id` nos funcionários      |

---

## Regras de Validação Importantes

### UpdateEmployeeRequest
- `user_id`: `nullable|integer|exists:users,id|unique:employees,user_id,{$id}` — evita associar o mesmo utilizador a múltiplos funcionários, ignorando o próprio registo

### StoreEmployeeRequest
- `user_id`: `nullable|integer|exists:users,id|unique:employees,user_id`

### Quiz (inline no QuizController)
- Criação: perguntas `required|array|min:1`, opções `required|array|min:2`
- Actualização: substitui completamente as perguntas se `questions` for enviado

---

## Pendente / Trabalho Futuro

Ver `docs/To do.md` para lista completa. Resumo:

### Bugs conhecidos
- Inscrições: campo de funcionários não apresenta todos
- Não bloquear score se formação ainda não concluída (data fim futura)
- Pluralização errada "formaçãooes" no relatório "Funcionários com formação"

### Funcionalidades planeadas
- Gestão de equipas (designação a obras)
- Exportação Excel nos relatórios
- Aplicativo móvel / dispositivo biométrico para controlo de presenças
- Gestão documental de empresas subcontratadas (portal próprio)

---

## Setup Local

```bash
# 1. Dependências
composer install
npm install

# 2. Ambiente
cp .env.example .env
php artisan key:generate

# 3. Base de dados (MySQL — dbhreminho)
php artisan migrate

# 4. Assets
npm run build

# 5. Storage symlink (para vídeos/uploads)
php artisan storage:link
```

### Desenvolvimento
```bash
composer run dev   # Laravel server + queue + logs + Vite
```

### Variáveis .env relevantes
```
APP_NAME=HRElectrominho
DB_CONNECTION=mysql
DB_DATABASE=dbhreminho
DOCSEM_API_URL=http://docselectrominho.test/api
DOCSEM_API_TOKEN=...
DOCSEM_SYNC_ENABLED=true
```
