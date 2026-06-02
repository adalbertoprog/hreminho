# CLAUDE.md — HRElectrominho

Documentação técnica do sistema para uso por agentes de IA e desenvolvedores.
Última actualização: Maio 2026 (rev. 31/05/2026 — campos financeiros em training_sessions, plano anual, formações obrigatórias).

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

1. **`routes/web.php`** — Rotas Web que devolvem views Blade
2. **`routes/api.php`** — API JSON prefixada em `/api/v1/`, protegida por `middleware('auth:web')`

As rotas API usam sessão (não tokens), porque o `bootstrap/app.php` prepende os middlewares de sessão ao grupo `api`:

```php
$middleware->api(prepend: [
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
]);
```

### Grupos de middleware em web.php

Existem dois grupos distintos de rotas autenticadas:

```php
// Rotas de password — isentas de ForcePasswordChange (evitar redirect loop)
Route::middleware('auth')->group(function () {
    Route::get('/password/change', ...)->name('password.change');
    Route::put('/password', ...)->name('password.update');
});

// Todas as outras rotas autenticadas — com verificação de password obrigatória
Route::middleware(['auth', 'force.password.change'])->group(function () {
    // dashboard, employees, trainings, portal, etc.
});
```

### Chamadas fetch() no frontend

Todas as chamadas `fetch()` devem incluir:
```js
credentials: 'same-origin',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
```

---

## Gates de Autorização

Definidos em `AppServiceProvider::boot()`:

| Gate              | Roles permitidos  | Uso                                          |
|-------------------|-------------------|----------------------------------------------|
| `manage-hr`       | `admin`, `hr`     | Acesso ao back-office e operações de gestão  |
| `admin-only`      | `admin`           | Administração de utilizadores                |
| `employee-portal` | `employee`        | Acesso ao portal do funcionário              |

Usar com `Gate::authorize('manage-hr')` nos controllers ou `@can('manage-hr')` nas views.

**Importante:** Não usar `in_array($user->role, ['admin', 'hr'])` inline — usar sempre os Gates definidos.

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
users: id, name, email, password, role (admin|hr|employee), must_change_password (bool), remember_token
```
- `hasOne(Employee::class)` — link inverso para o registo de funcionário
- Usa `$fillable` convencional (não o atributo PHP `#[Fillable]`, que tem suporte limitado)

### Employee
```
employees: id, code, first_name, last_name, email, phone, date_of_birth, gender,
           nationality, address, work_location, profile_photo (string — path relativo em storage),
           position_id, department_id, sector_id, hire_date, status, contract_type,
           end_date, user_id, deleted_at
```
- `belongsTo(User::class)` — conta de utilizador associada
- `belongsTo(Position/Department/Sector)`
- `hasMany(Attendance/Leave/EmployeeTraining)`
- `belongsToMany(Training)` via `employee_trainings`
- Soft deletes activos
- Accessor `getFullNameAttribute()` → `first_name last_name`
- Accessor `getProfilePhotoUrlAttribute()` → URL pública via `asset('storage/' . $path)`, com fallback para base64 legado

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

### MandatoryTraining
```
mandatory_trainings: id, training_id, target_type (all|department|position), target_id (nullable), deadline_days (nullable), notes
```
- `belongsTo(Training)`
- `affectedEmployeeIds()` — devolve Collection de IDs de funcionários activos abrangidos pela regra
- `doneEmployeeIds(Collection $affectedIds)` — funcionários que já cumpriram (via inscrição ou quiz aprovado)
- **Nota:** O método chama-se `affectedEmployeeIds()` (não `scopeAffectedEmployeeIds`) — não é um Eloquent query scope

### TrainingSession
```
training_sessions: id, training_id, planned_date, planned_end_date (nullable),
                   location (nullable), max_participants (nullable),
                   estimated_participants (nullable), cost_per_person decimal(10,2) (nullable),
                   status (planned|ongoing|completed|cancelled), notes, timestamps
```
- `belongsTo(Training)`
- Accessor `duration_days` — dias entre planned_date e planned_end_date (mínimo 1)
- Accessor `computed_status` — estado calculado com base nas datas (ignora o campo `status` excepto se `cancelled`)
- O campo `estimated_total` **não existe na BD** — é calculado no `format()` do controller: `cost_per_person × estimated_participants`
- Cast `cost_per_person` como `decimal:2` devolve string no Laravel — o controller converte com `(float)` antes de calcular

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
- Password padrão: `12345678` — flag `must_change_password = true` activado automaticamente
- Lógica:
  1. Funcionário com email que já tem utilizador → liga `user_id` (contado como `$linked`)
  2. Funcionário com email sem utilizador → cria utilizador com esse email
  3. Funcionário sem email → cria email interno `funXXXX@hrelectrominho.local`
- Artisan: `php artisan employees:create-users [--dry-run]` (também activa `must_change_password`)

---

## Fotos de Perfil

- Guardadas em `storage/app/public/employees/photos/` (disco `public`)
- O campo `profile_photo` na tabela guarda o **path relativo** (ex: `employees/photos/abc123.jpg`)
- Acesso via `$employee->profile_photo_url` (accessor no modelo) → devolve URL pública
- `EmployeeController::storePhoto()` converte base64 data URI para ficheiro em storage
- Ao actualizar foto, a foto antiga é apagada do disco automaticamente
- Ao eliminar funcionário, a foto é apagada do disco automaticamente
- `EmployeeResource` devolve `photo` como URL pública (nunca base64)
- A migração `2026_05_31_000001` converteu registos legados de base64 para path

---

## Mudança de Password Obrigatória

- Campo `must_change_password` (boolean, default `false`) na tabela `users`
- Activado automaticamente ao criar contas via `BulkUserController` e comando `employees:create-users`
- Middleware `ForcePasswordChange` registado como alias `force.password.change` em `bootstrap/app.php`
- Aplicado no grupo `['auth', 'force.password.change']` em `routes/web.php` — corre **após** `auth`
- As rotas `GET /password/change` e `PUT /password` estão num grupo só com `auth` (sem o middleware) para evitar redirect loop
- View: `auth/change-password.blade.php`
- Após guardar nova password, `must_change_password` é reposto a `false` e utilizador é redirecionado para o dashboard

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
- Cada card exibe uma etiqueta de estado no canto superior direito:
  - **✓ Concluído** (verde) — quiz aprovado
  - **✗ Reprovado** (vermelho) — quiz feito mas não passou
  - **Por fazer** (amarelo) — tem quiz mas ainda não tentou
  - **Disponível** (roxo) — só tem vídeo, sem quiz
- Cards concluídos têm borda verde subtil
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

**Nota:** A rota `POST /employees/bulk-create-users` está declarada **antes** do `apiResource('employees')` para evitar conflito com `employees.show`.

### Inscrições em Formações
| Método | Rota                          | Acção               |
|--------|-------------------------------|---------------------|
| GET    | `/enrollments`                | Listar inscrições   |
| POST   | `/enrollments`                | Inscrever           |
| PUT    | `/enrollments/{enrollment}`   | Actualizar          |
| DELETE | `/enrollments/{enrollment}`   | Remover             |

### Vídeos
| Método | Rota                                                  |
|--------|-------------------------------------------------------|
| *      | `/trainings/{training}/videos` (apiResource, shallow) |

### Quiz
| Método | Rota                                        | Acção                                      |
|--------|---------------------------------------------|--------------------------------------------|
| GET    | `/trainings/{training}/quiz`                | Ver quiz (respostas ocultas para employee) |
| POST   | `/trainings/{training}/quiz`                | Criar quiz (admin/hr)                      |
| PUT    | `/trainings/{training}/quiz`                | Actualizar quiz (admin/hr)                 |
| GET    | `/trainings/{training}/quiz/results`        | Resultados por utilizador (admin/hr)       |
| POST   | `/quiz/{training}/attempt`                  | Submeter tentativa                         |
| GET    | `/quiz/{training}/my-attempts`              | Histórico de tentativas                    |

### Sessões de Formação (Plano Anual)
| Método | Rota                                              | Acção                                      |
|--------|---------------------------------------------------|--------------------------------------------|
| GET    | `/training-sessions`                              | Listar (filtros: year, month, status, training_id) |
| POST   | `/training-sessions`                              | Criar sessão                               |
| PUT    | `/training-sessions/{trainingSession}`            | Actualizar sessão                          |
| DELETE | `/training-sessions/{trainingSession}`            | Remover sessão                             |
| GET    | `/training-sessions/annual-summary?year=YYYY`     | Resumo anual por mês e por estado          |

**Nota:** A rota `annual-summary` está declarada **antes** do `index` para evitar conflito de rotas.

O endpoint devolve `estimated_total` calculado (não existe na BD): `cost_per_person × estimated_participants`.

### Formações Obrigatórias
| Método | Rota                                               | Acção                                 |
|--------|----------------------------------------------------|---------------------------------------|
| GET    | `/mandatory-trainings`                             | Listar regras com dados de cumprimento |
| POST   | `/mandatory-trainings`                             | Criar regra                           |
| PUT    | `/mandatory-trainings/{mandatoryTraining}`         | Actualizar (apenas deadline_days e notes) |
| DELETE | `/mandatory-trainings/{mandatoryTraining}`         | Remover regra                         |
| GET    | `/mandatory-trainings/compliance`                  | Sumário global de cumprimento         |
| GET    | `/mandatory-trainings/{mandatoryTraining}/gaps`    | Funcionários em falta para uma regra  |

**Nota:** A rota `compliance` está declarada **antes** de `{mandatoryTraining}` para evitar conflito.

### Outros
| Método | Rota                                  | Acção                          |
|--------|---------------------------------------|--------------------------------|
| POST   | `/employee-portal/associate`          | Associar funcionário por código |
| POST   | `/employees/bulk-create-users`        | Criar contas em massa          |
| GET    | `/reports/completed-trainings`        | Relatório formações concluídas |
| GET    | `/reports/employees-trainings`        | Funcionários com formações     |
| GET    | `/reports/training-employees`         | Formações com funcionários     |
| GET    | `/reports/attendance`                 | Sumário de presenças           |
| GET    | `/reports/validity`                   | Relatório de validade de certificados |
| GET    | `/reports/gaps`                       | Análise de lacunas (obrigatórias, certificados, sem formação, plano vs execução) |
| POST   | `/reports/send-email`                 | Enviar relatório por email     |

---

## Rotas Web

| Rota                              | Controller                          | Middleware                        | Notas                        |
|-----------------------------------|-------------------------------------|-----------------------------------|------------------------------|
| `/`                               | —                                   | —                                 | Página de apresentação       |
| `/login` / POST `/login`          | `LoginController`                   | `guest`                           | Login dual (email ou código) |
| POST `/logout`                    | `LoginController`                   | `auth`                            |                              |
| `GET /password/change`            | `PasswordWebController`             | `auth`                            | Formulário mudança obrigatória |
| `PUT /password`                   | `PasswordWebController`             | `auth`                            | Alterar password             |
| `/dashboard`                      | `DashboardController`               | `auth`, `force.password.change`   | Back-office (admin/hr)       |
| `/employees`                      | `EmployeeWebController`             | `auth`, `force.password.change`   |                              |
| `/departments`                    | `DepartmentWebController`           | `auth`, `force.password.change`   |                              |
| `/positions`                      | `PositionWebController`             | `auth`, `force.password.change`   |                              |
| `/sectors`                        | `SectorWebController`               | `auth`, `force.password.change`   |                              |
| `/attendances`                    | `AttendanceWebController`           | `auth`, `force.password.change`   |                              |
| `/leaves`                         | `LeaveWebController`                | `auth`, `force.password.change`   |                              |
| `/trainings`                      | `TrainingWebController`             | `auth`, `force.password.change`   |                              |
| `/trainings/dashboard`            | `TrainingDashboardController`       | `auth`, `force.password.change`   | Dashboard de formações       |
| `/trainings/plan`                 | `TrainingPlanWebController`         | `auth`, `force.password.change`   | Plano anual de formações     |
| `/users`                          | `UserWebController`                 | `auth`, `force.password.change`   |                              |
| `/reports`                        | `ReportWebController`               | `auth`, `force.password.change`   |                              |
| `/calendar`                       | `CalendarWebController`             | `auth`, `force.password.change`   |                              |
| `/docsem/*`                       | `DocsElectroMinhoWebController`     | `auth`, `force.password.change`   | Integração externa           |
| `/employee/dashboard`             | `EmployeePortalController`          | `auth`, `force.password.change`   | Portal funcionário           |
| `/employee/training/{training}`   | `EmployeePortalController`          | `auth`, `force.password.change`   | Vídeo + quiz                 |

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

| Comando                                          | Descrição                                                                                      |
|--------------------------------------------------|------------------------------------------------------------------------------------------------|
| `php artisan employees:create-users [--dry-run]` | Cria contas para todos os funcionários activos sem utilizador associado. Password padrão: `12345678`. Activa `must_change_password`. |
| `php artisan docsem:sync`                        | Sincroniza funcionários com o sistema DocsElectroMinho.                                        |

---

## Views e Layouts

### Layouts
- `layouts/app.blade.php` — layout principal (sidebar + navbar); sidebar condicional por Gate (`@can`)
- `layouts/guest.blade.php` — layout para páginas públicas (login, home)

### Principais Views
| View                             | Descrição                                                              |
|----------------------------------|------------------------------------------------------------------------|
| `auth/login.blade.php`           | Formulário de login (campo `login`, tipo `text`)                       |
| `auth/change-password.blade.php` | Formulário de mudança obrigatória de password (primeiro login)         |
| `dashboard/index.blade.php`      | Dashboard back-office com métricas e gráficos                          |
| `employees/index.blade.php`      | CRUD funcionários + associação rápida + "Gerar Acessos"                |
| `trainings/index.blade.php`      | CRUD formações + conteúdo (vídeos/quiz) + resultados + obrigatórias    |
| `trainings/dashboard.blade.php`  | Dashboard de formações: KPIs, evolução, compliance obrigatórias        |
| `trainings/plan.blade.php`       | Plano anual: vista de calendário (meses) + lista + CRUD de sessões     |
| `users/index.blade.php`          | CRUD utilizadores do sistema                                           |
| `employee/dashboard.blade.php`   | Portal: perfil + banner associação + cards de formações com estado     |
| `employee/training.blade.php`    | Player de vídeo + questionário                                         |
| `reports/index.blade.php`        | Relatórios (formações, presenças, validade, etc.)                      |
| `docsem/index.blade.php`         | Estado da integração DocsElectroMinho                                  |

---

## Migrations (por ordem)

| Ficheiro                                                      | Descrição                                  |
|---------------------------------------------------------------|--------------------------------------------|
| `0001_01_01_000000_create_users_table`                        | Tabela users + sessions + password_resets  |
| `2024_01_01_000001` a `000010`                                | Estrutura base: positions, departments, sectors, employees, attendances, leaves, trainings, employee_trainings |
| `2026_04_25_182834_add_work_location_to_employees_table`      | Campo `work_location` nos funcionários     |
| `2026_04_25_185356_make_employee_nullable_fields`             | Tornar campos opcionais nos funcionários   |
| `2026_04_26_000001_change_profile_photo_to_longtext`          | Foto de perfil como base64 (longtext) — legado |
| `2026_04_28_000001_add_validity_months_to_employee_trainings` | Validade de certificados                   |
| `2026_05_04_161603_add_deleted_at_to_employees_table`         | Soft deletes nos funcionários              |
| `2026_05_26_000001_normalize_user_role_enum`                  | Normalizar enum de roles                   |
| `2026_05_26_000002_create_training_videos_table`              | Vídeos de formação                         |
| `2026_05_26_000003_create_quiz_tables`                        | Quiz, perguntas, opções, tentativas, respostas |
| `2026_05_27_000001_add_has_video_has_quiz_to_trainings`       | Flags `has_video` e `has_quiz`             |
| `2026_05_27_000002_add_is_uploaded_to_training_videos`        | Flag `is_uploaded` para distinguir upload/URL |
| `2026_05_27_000003_add_user_id_to_employees_table`            | Associação `user_id` nos funcionários      |
| `2026_05_31_000001_change_profile_photo_to_string`            | Migra fotos de base64 para storage path    |
| `2026_05_31_000002_add_must_change_password_to_users`         | Flag de mudança obrigatória de password    |
| `2026_05_31_000003_create_mandatory_trainings_table`          | Tabela de formações obrigatórias           |
| `2026_05_31_000004_create_training_sessions_table`            | Tabela do plano anual de formações         |
| `2026_05_31_000005_add_financial_fields_to_training_sessions` | Campos `estimated_participants` e `cost_per_person` nas sessões |

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

## Plano Anual de Formações

Rota: `/trainings/plan` — apenas `admin` e `hr`.

### Funcionamento
- Vista anual: grid de 12 meses com chips das sessões planeadas; clicar num mês abre modal de detalhe
- Vista lista: tabela filtrável por estado e formação, com colunas financeiras e totalizador
- Navegação entre anos via botões `‹` / `›`

### Campos financeiros (por sessão)
| Campo                   | Tipo              | Descrição                                      |
|-------------------------|-------------------|------------------------------------------------|
| `estimated_participants`| smallint unsigned | Nº previsto de participantes                   |
| `cost_per_person`       | decimal(10,2)     | Custo unitário por participante (€)            |
| `estimated_total`       | —                 | Calculado: `cost_per_person × estimated_participants` (não existe na BD, só na API) |

O totalizador no rodapé da tabela lista só aparece se pelo menos uma sessão tiver custo definido.

### Notas de implementação
- `annualSummary` carrega `training:id,title,provider` (o `provider` é necessário para o `format()`)
- O cast `decimal:2` no modelo devolve string — o controller converte com `(float)` antes de calcular `estimated_total`
- `recalcTotal()` no frontend recalcula em tempo real ao editar participantes ou custo

---

## Formações Obrigatórias

Regras que definem quais formações são obrigatórias para todos os funcionários, um departamento específico, ou um cargo específico.

### Modelo MandatoryTraining
- `target_type`: `all` | `department` | `position`
- `target_id`: null para `all`, ID do departamento/cargo nos outros casos
- `affectedEmployeeIds()` — **método de instância** (não Eloquent scope), devolve Collection de IDs
- `doneEmployeeIds(Collection)` — verifica cumprimento via inscrição (`enrolled`/`completed`) **ou** quiz aprovado

### Compliance
- `/api/v1/mandatory-trainings/compliance` — sumário global (taxa por regra)
- `/api/v1/mandatory-trainings/{id}/gaps` — lista de funcionários em falta para uma regra específica
- Visível no Dashboard de Formações e na tab "Obrigatórias" em `/trainings`

---

## Pendente / Trabalho Futuro

Ver `docs/To do.md` para lista completa. Resumo:

### Bugs conhecidos
- Inscrições: campo de funcionários não apresenta todos
- Não bloquear score se formação ainda não concluída (data fim futura)

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

# 5. Storage symlink (para fotos e uploads)
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
