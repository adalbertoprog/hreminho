# CLAUDE.md — HRElectrominho

Documentação técnica do sistema para uso por agentes de IA e desenvolvedores.
Última actualização: Junho 2026 (rev. 18/06/2026 — Simulador de Disponibilidade Técnica, exportação PDF, integração Obras↔Simulador, testes StaffingCheck, PermissionService, obras/equipas/viaturas).

---

## Visão Geral

**HRElectrominho** é um sistema de gestão de recursos humanos (RH) desenvolvido em Laravel 13 com Blade templating. Destina-se à empresa Electrominho e gere funcionários, departamentos, presenças, férias/licenças, feriados, formações, vídeos, questionários, relatórios e documentos.

- **URL local**: `http://hreminho.test`
- **Base de dados**: MySQL — `dbhreminho`
- **Framework**: Laravel 13 (bootstrap/app.php — sem Kernel.php)
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

| Gate                 | Roles permitidos              | Uso                                                          |
|----------------------|-------------------------------|--------------------------------------------------------------|
| `manage-hr`          | `admin`, `hr`                 | Acesso ao back-office e operações de gestão de RH            |
| `admin-only`         | `admin`                       | Administração de utilizadores                                |
| `employee-portal`    | `employee`, `manager`         | Acesso ao portal do funcionário                              |
| `manage-attendance`  | `admin`, `hr`, `manager`      | Gestão de presenças; managers vêem só o seu dept/sector      |
| `view-projects`      | `admin`, `hr`, `manager`      | Leitura de obras e equipas associadas                        |
| `manage-projects`    | `admin`, `hr`                 | CRUD de obras, equipas e empresas subcontratadas             |

Usar com `Gate::authorize('manage-hr')` nos controllers ou `@can('manage-hr')` nas views.

**Importante:** Não usar `in_array($user->role, ['admin', 'hr'])` inline — usar sempre os Gates definidos.

---

## Roles de Utilizador

| Role       | Acesso |
|------------|--------|
| `admin`    | Acesso total ao back-office e todas as operações |
| `hr`       | Mesmo acesso que admin (gestão de RH) |
| `manager`  | Portal do funcionário + gestão de presenças do seu dept/sector + aprovação de licenças |
| `employee` | Apenas portal do funcionário (`/employee/dashboard`) |

O redirect pós-login está em `LoginController::login()`:
```php
$default = in_array($role, ['employee', 'manager']) ? route('employee.dashboard') : route('dashboard');
```

### Role manager — comportamento específico

- Acede ao portal via `can:employee-portal` (inclui `manager`)
- Acede à gestão de presenças via `can:manage-attendance`
- Em `/attendances`: vê apenas funcionários dos departamentos/sectores onde é `manager_id`
- Em `/manager/leaves`: vê pedidos de licença dos seus funcionários; admin/hr vêem todos
- `AttendanceController` e `EmployeeLeaveController::authorizeManager()` aplicam o filtro automaticamente
- `Department` e `Sector` têm campo `manager_id` (FK para `employees.id`)

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
- `certificate_path` — path relativo em `storage/app/public/certificates/`; acesso via `certificate_url` (URL pública) devolvido pelo `formatEnrollment()`
- Upload via `POST /api/v1/enrollments/{id}/certificate` (multipart, campo `certificate`, max 5 MB, PDF/JPG/PNG)
- Remoção via `PUT /api/v1/enrollments/{id}` com `{ certificate_path: null }` — apaga o ficheiro do disco
- Score só pode ser registado se `end_date` for hoje ou no passado — validado no frontend (`updateScoreState`) e no backend (`enroll` e `updateEnrollment`)
- Upload usa `file_get_contents` + `Storage::disk('public')->put()` directamente (evita problema com `upload_tmp_dir` no Windows/Laragon)

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

### Leave
```
leaves: id, employee_id, leave_type (vacation|sick|unpaid), start_date, end_date,
        reason (text, nullable), status (pending|approved|rejected),
        manager_comment (text, nullable), timestamps
```
- `belongsTo(Employee)`
- Submetido pelo funcionário via portal; aprovado/rejeitado pelo responsável ou admin/hr
- Ao aprovar: `LeaveAttendanceSync::sync()` cria registos `on_leave` para cada dia útil do período
- Ao rejeitar: os registos de presença gerados por esta licença são removidos
- `reason` é nullable (migração `2026_06_10_000004_make_leaves_reason_nullable`)

### Attendance
```
attendances: id, employee_id, date, check_in (time), lunch_out (time), lunch_in (time),
             check_out (time), status (present|absent|late|on_leave|holiday),
             worked_hours (decimal), leave_id (FK nullable), notes, timestamps
```
- `belongsTo(Employee)`, `belongsTo(Leave)` (nullable — para registos gerados por licenças)
- Status calculado automaticamente com base nos campos de hora e settings do sistema:
  - `late` se `check_in > expected_check_in + late_threshold_minutes`
  - `on_leave` se `leave_id` presente (criado por `LeaveAttendanceSync`)
  - `holiday` se a data for feriado (verificado via `Holiday::isHoliday()`)
- `worked_hours` = `(check_out − check_in) − (lunch_in − lunch_out)` em horas decimais
- Accessor `worked_hours_formatted` → `"8h30m"` para exibição
- `leave_id` permite limpar registos de uma licença ao rejeitar ou alterar datas

### SystemSetting
```
system_settings: id, key (string unique), value (text), timestamps
```
- Helper: `Settings::get('key', $default)` — lê da BD com cache de 60s
- Helper: `Settings::set('key', 'value')` — actualiza e invalida cache
- Chaves usadas pelo sistema:

| Chave                      | Tipo    | Padrão | Descrição                                  |
|----------------------------|---------|--------|--------------------------------------------|
| `expected_check_in`        | time    | 09:00  | Hora esperada de entrada                   |
| `late_threshold_minutes`   | int     | 15     | Minutos de tolerância para marcar "Atrasado" |
| `work_hours_per_day`       | decimal | 8.0    | Horas de trabalho standard                 |
| `lunch_duration_minutes`   | int     | 60     | Duração esperada do almoço                 |

### Holiday
```
holidays: id, name (string), date (date), type (national|local|company), repeats_yearly (bool), timestamps
```
- `belongsTo` nenhum — tabela independente
- `Holiday::isHoliday($date)` — verifica se data é feriado (feriados anuais comparam só mês/dia via `DATE_FORMAT`)
- `Holiday::nameFor($date)` — devolve nome do feriado se existir
- Resultado em cache por 1 hora (chave `holiday_YYYY-MM-DD`)
- `HolidaySeeder` — popula feriados nacionais portugueses de 2025 e 2026
- Usado em `AttendanceController` para marcar status `holiday` automaticamente

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

### Portal do Funcionário — Licenças
| Método | Rota                                              | Gate                | Acção                              |
|--------|---------------------------------------------------|---------------------|------------------------------------|
| POST   | `/employee-portal/leaves`                         | autenticado         | Funcionário submete pedido         |
| DELETE | `/employee-portal/leaves/{leaveId}`               | autenticado         | Funcionário cancela pedido pendente |
| PUT    | `/employee-portal/leaves/{leaveId}/approve`       | `manage-attendance` | Manager/admin aprova               |
| PUT    | `/employee-portal/leaves/{leaveId}/reject`        | `manage-attendance` | Manager/admin rejeita              |

### Feriados (apenas `manage-hr`)
| Método | Rota                          | Acção              |
|--------|-------------------------------|--------------------|
| GET    | `/holidays?year=YYYY`         | Listar feriados    |
| POST   | `/holidays`                   | Criar feriado      |
| PUT    | `/holidays/{holiday}`         | Actualizar feriado |
| DELETE | `/holidays/{holiday}`         | Eliminar feriado   |

### Configurações do Sistema
| Método | Rota         | Gate                | Acção                    |
|--------|--------------|---------------------|--------------------------|
| GET    | `/settings`  | `manage-attendance` | Ler todas as settings    |
| PUT    | `/settings`  | `manage-hr`         | Actualizar uma ou mais   |

### Obras e Equipas (Gate `manage-hr`)
| Método | Rota                                                           | Acção                          |
|--------|----------------------------------------------------------------|--------------------------------|
| *      | `/projects` (apiResource)                                      | CRUD obras                     |
| GET    | `/projects/{project}/teams`                                    | Listar equipas da obra         |
| POST   | `/projects/{project}/teams`                                    | Criar equipa                   |
| PUT    | `/projects/{project}/teams/{team}`                             | Actualizar equipa              |
| DELETE | `/projects/{project}/teams/{team}`                             | Remover equipa                 |
| POST   | `/projects/{project}/teams/{team}/employees`                   | Adicionar funcionário          |
| DELETE | `/projects/{project}/teams/{team}/employees`                   | Remover funcionário            |
| POST   | `/projects/{project}/teams/{team}/vehicles`                    | Adicionar viatura              |
| DELETE | `/projects/{project}/teams/{team}/vehicles`                    | Remover viatura                |

### Empresas Subcontratadas (Gate `manage-hr`)
| Método | Rota                                              | Acção                                         |
|--------|---------------------------------------------------|-----------------------------------------------|
| GET    | `/projects/{project}/companies`                   | Listar empresas associadas à obra             |
| POST   | `/projects/{project}/companies`                   | Associar empresa à obra                       |
| PUT    | `/projects/{project}/companies/{company}`         | Actualizar datas/observações                  |
| DELETE | `/projects/{project}/companies/{company}`         | Remover associação                            |
| GET    | `/docsem/empresas?search=&tipo=`                  | Pesquisar empresas no DocsElectro-Minho       |

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
| `/employee/dashboard`             | `EmployeePortalController`          | `auth`, `force.password.change`, `can:employee-portal` | Portal funcionário |
| `/employee/training/{training}`   | `EmployeePortalController`          | `auth`, `force.password.change`, `can:employee-portal` | Vídeo + quiz       |
| `/employee/leaves`                | `EmployeePortalController`          | `auth`, `force.password.change`, `can:employee-portal` | Licenças e Férias (funcionário) |
| `/manager/leaves`                 | `EmployeePortalController`          | `auth`, `force.password.change`, `can:manage-attendance` | Aprovação de licenças (manager/admin) |
| `/settings`                       | `SettingsWebController`             | `auth`, `force.password.change`, `can:manage-hr` | Configurações do sistema + feriados |

---

## Integração DocsElectroMinho

Integração com sistema externo de gestão documental (`C:\laragon\www\docselectrominho`).

### Configuração
- **Variáveis `.env`**: `DOCSEM_API_URL`, `DOCSEM_API_TOKEN`, `DOCSEM_SYNC_ENABLED`
- **Service**: `app/Services/DocsElectroMinhoService.php`
- **Controller de estado**: `app/Http/Controllers/Web/DocsElectroMinhoWebController.php`
- **Página de estado**: `/docsem`

### Sincronização de Funcionários
- Sincroniza funcionários activos para o sistema externo (em lotes de 100)
- Permite sincronização global ou por funcionário individual
- Método `sincronizarFuncionarios(Collection)` → `['criados', 'atualizados', 'erros']`
- Método `sincronizarFuncionario(Employee)` → upsert via `PUT /funcionarios/rh:{id}`
- Método `removerFuncionario(int $rhEmployeeId)` → `DELETE /funcionarios/rh:{id}`
- Método `documentosDoFuncionario(int)` → `GET /funcionarios/rh:{id}/documentos`

### Empresas Subcontratadas
Módulo que liga obras do HREminho a empresas cadastradas no DocsElectroMinho.

**Modelo `ProjectCompany`** (`app/Models/ProjectCompany.php`)
- Tabela pivot `project_companies` entre obras e empresas externas
- Campos: `project_id`, `docsem_empresa_id`, `empresa_nome` (cache), `empresa_nif` (cache), `data_entrada`, `data_saida`, `observacoes`
- `empresa_nome` e `empresa_nif` são cached no momento da associação para evitar chamadas repetidas à API

**Relações em `Project`**
```php
public function companies(): HasMany
{
    return $this->hasMany(ProjectCompany::class);
}
```

**`DocsElectroMinhoService` — métodos de empresas**
```php
getEmpresas(array $filtros = []): array  // GET /empresas?estado=ativa&per_page=500
getEmpresa(int $docsemEmpresaId): array  // GET /empresas/{id}
```
Por omissão só são devolvidas empresas com `estado=ativa`.

**`ProjectCompanyController`** (`app/Http/Controllers/ProjectCompanyController.php`)
- `index` — Gate `view-projects`
- `searchDocsem` — Gate `manage-projects`; proxy para `getEmpresas()` com parâmetros `search` e `tipo`
- `store`, `update`, `destroy` — Gate `manage-projects`

**UI — Drawer de Obras (`/projects`)**
O drawer lateral das obras tem dois painéis seleccionáveis por tabs:
- **Equipas** — lista equipas e membros (comportamento anterior)
- **Empresas Subcontratadas** — lista empresas associadas; modal de associação com picker live-search

O picker pesquisa no DocsEM ao focar (lista todos os activos) e ao escrever (debounce 350 ms; mínimo 2 caracteres). A empresa seleccionada fica em campos `hidden` e é exibida como chip.

`switchDrawerTab(tab)` gere a troca de painel e despoleta o carregamento de dados do painel activo.

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
| `employee/dashboard.blade.php`   | Portal: perfil + banner associação + widget licenças + cards formações |
| `employee/training.blade.php`    | Player de vídeo + questionário                                         |
| `employee/leaves.blade.php`      | Portal: submissão e histórico de pedidos de licença                    |
| `employee/manager-leaves.blade.php` | Aprovação/rejeição de licenças pelo manager/admin                   |
| `reports/index.blade.php`        | Relatórios com 5 tabs; exportação Excel (SheetJS) e PDF (`window.print` + CSS) |
| `settings/index.blade.php`       | Configurações do sistema (horário, tolerância) + CRUD feriados         |
| `docsem/index.blade.php`         | Estado da integração DocsElectroMinho                                  |
| `projects/index.blade.php`       | Obras: lista + drawer com tabs Equipas / Empresas Subcontratadas       |

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
| `2026_06_09_000001_add_lunch_fields_to_attendances_table`     | Campos `lunch_out` e `lunch_in` nas presenças                   |
| `2026_06_09_000002_create_system_settings_table`              | Tabela `system_settings` (chave/valor)                          |
| `2026_06_10_000001_add_manager_id_to_departments_sectors`     | Campos `manager_id` em departments e sectors (FK → employees)   |
| `2026_06_10_000002_add_leave_id_to_attendances_table`         | Campo `leave_id` nullable em attendances                        |
| `2026_06_10_000003_create_holidays_table`                     | Tabela `holidays` (nome, data, tipo, repeats_yearly)            |
| `2026_06_10_000004_make_leaves_reason_nullable`               | Torna `leaves.reason` nullable                                  |
| `2026_06_11_000005_create_projects_table`                     | Tabela `projects` (obras)                                       |
| `2026_06_11_000006_create_project_companies_table`            | Pivot `project_companies` (obras ↔ empresas DocsEM)             |

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

## Presenças — Funcionalidades Avançadas

### Campos de horário
Além de `check_in`/`check_out`, a tabela tem `lunch_out` e `lunch_in`. O `worked_hours` deduz a duração do almoço quando ambos os campos estão preenchidos.

### Status automático
`AttendanceController` calcula o status com base nas `system_settings`:
- `holiday` — verificado primeiro via `Holiday::isHoliday()`
- `on_leave` — se existe registo com `leave_id` para esse dia
- `late` — `check_in > expected_check_in + late_threshold_minutes`
- `present` — entrou a horas
- `absent` — sem registo

### Filtros e vistas
- Filtros rápidos: Hoje / Esta Semana / Este Mês
- Filtro intervalo personalizado De/Até
- Barra de resumo com contadores (presente/ausente/atrasado/licença/feriado)
- Vista Semanal (grid Seg–Dom com cada funcionário por linha)
- Destaque visual em registos incompletos (sem `check_out`)

### Filtro por role
`AttendanceController::index()` aplica filtro automático:
- `admin` / `hr` — vêem todos os funcionários
- `manager` — filtrado para funcionários dos departamentos/sectores onde o utilizador é `manager_id`

---

## Obras, Equipas e Viaturas

Módulo de gestão de obras de construção/instalação com designação de equipas e viaturas.

### Modelos

**Project**
```
projects: id, name, reference, client, location, start_date, end_date,
          status (planning|active|completed|cancelled), notes,
          docsem_obra_id (nullable), docsem_synced_at (datetime nullable), timestamps
```
- `hasMany(Team)`
- `hasMany(ProjectCompany)`
- `employees()` — funcionários distintos afectos a qualquer equipa da obra

**Team**
```
teams: id, project_id, name, leader_id (FK → employees.id nullable), notes, timestamps
```
- `belongsTo(Project)`
- `belongsTo(Employee, 'leader_id')` — líder da equipa
- `belongsToMany(Employee)` via `team_employees` com pivot `start_date`, `end_date`, `role`
- `belongsToMany(Vehicle)` via `team_vehicles` com pivot `start_date`, `end_date`
- `activeEmployees()` — apenas membros sem `end_date` ou com `end_date >= hoje`

**Vehicle**
```
vehicles: id, plate, brand, model, year, type, status (available|in_use|maintenance), notes, timestamps
```
- `belongsToMany(Team)` via `team_vehicles`

**Tabelas pivot**
```
team_employees: team_id, employee_id, start_date, end_date, role, timestamps
team_vehicles:  team_id, vehicle_id, start_date, end_date, timestamps
```

### Portal do Funcionário — Obras
- `/employee/projects` — funcionário vê as obras e equipas a que pertence
- `EmployeePortalController::projects()` filtra por `team_employees.employee_id`

---

## Sistema de Permissões Configuráveis

`app/Services/PermissionService.php` — sistema de permissões por role com overrides persistentes.

### Arquitectura
- Permissões definidas como constante `PERMISSIONS` (array estático)
- Defaults hardcoded por role para cada permissão
- Overrides configuráveis guardados em `system_settings` com chave `perm.{role}.{permission}`
- Cache em memória por request (`static $resolved`)
- Admin tem sempre acesso total (sem override possível)
- Employee tem acesso fixo ao portal (sem override possível)

### Permissões disponíveis

| Chave                | Label                            | Grupo        | hr default | manager default | Configurável por |
|----------------------|----------------------------------|--------------|------------|-----------------|-----------------|
| `view_employees`     | Funcionários — Ver lista         | employees    | true       | false           | manager          |
| `edit_employees`     | Funcionários — Criar / Editar    | employees    | true       | false           | manager          |
| `delete_employees`   | Funcionários — Eliminar          | employees    | false      | false           | hr               |
| `view_attendances`   | Presenças — Ver                  | attendances  | true       | true            | manager          |
| `manage_attendances` | Presenças — Registar / Editar    | attendances  | true       | true            | hr, manager      |
| `approve_leaves`     | Licenças — Aprovar / Rejeitar    | leaves       | true       | true            | hr, manager      |
| `view_all_leaves`    | Licenças — Ver todos             | leaves       | true       | false           | manager          |
| `view_projects`      | Obras — Ver                      | projects     | true       | true            | hr, manager      |
| `manage_projects`    | Obras — Criar / Editar / Eliminar| projects     | true       | false           | hr, manager      |
| `view_reports`       | Relatórios — Ver / Exportar      | reports      | true       | false           | manager          |
| `manage_trainings`   | Formações — Gerir / Inscrever    | trainings    | true       | false           | manager          |

### API de uso

```php
// Verificar permissão
PermissionService::allows('manager', 'view_projects'); // bool

// Gravar overrides (chamado por SettingsController)
PermissionService::save(['manager.view_projects' => true, 'hr.delete_employees' => false]);

// Matriz completa para UI
PermissionService::matrix(); // array com valores actuais e configurabilidade

// Limpar cache após gravar
PermissionService::clearCache();
```

### UI de permissões
- `/settings/permissions` (GET) — tabela com checkboxes por role/permissão
- `POST /settings/permissions` — grava overrides via `PermissionService::save()`
- Apenas permissões marcadas como `configurable` para aquele role são alteráveis
- Admin e employee não aparecem na UI (não configuráveis)

---

## Testes

### Cobertura actual (~2800 linhas)

**Feature tests** (`tests/Feature/`):
| Ficheiro                    | Linhas | O que testa                                                  |
|-----------------------------|--------|--------------------------------------------------------------|
| `AuthTest.php`              | 201    | Login (email e código), logout, mudança obrigatória de password |
| `EmployeeApiTest.php`       | 305    | CRUD employees, autorização por role, associação user_id     |
| `ProjectApiTest.php`        | 478    | CRUD obras, equipas, viaturas, empresas subcontratadas       |
| `ReportApiTest.php`         | 281    | Todos os endpoints de relatórios, filtros e exportação       |
| `StaffingCheckApiTest.php`  | 367    | Simulador de disponibilidade: autorização, validação, 9 cenários de negócio |
| `TrainingApiTest.php`       | 305    | CRUD formações, vídeos, quiz, inscrições, tentativas         |

**Unit tests** (`tests/Unit/`):
| Ficheiro                    | Linhas | O que testa                                          |
|-----------------------------|--------|------------------------------------------------------|
| `EmployeeTest.php`          | 153    | Modelo Employee: accessors, relações, soft deletes   |
| `EmployeeTrainingTest.php`  | 174    | Pivot EmployeeTraining: status, certificado, score   |
| `MandatoryTrainingTest.php` | 224    | Compliance: affectedEmployeeIds, doneEmployeeIds     |
| `ProjectTest.php`           | 181    | Modelo Project: relações, employees(), status        |
| `TrainingSessionTest.php`   | 163    | Sessões: duration_days, computed_status, estimated_total |
| `TrainingTest.php`          | 131    | Modelo Training: relações, has_video, has_quiz       |
| `UserTest.php`              | 150    | Modelo User: roles, must_change_password, relações   |

### Executar testes
```bash
composer run test
# ou
php artisan test
```

---

## Migrações — Adicional (Junho 2026)

| Ficheiro                                                      | Descrição                                           |
|---------------------------------------------------------------|-----------------------------------------------------|
| `2026_06_11_000001_create_projects_table`                     | Tabela `projects` (obras)                           |
| `2026_06_11_000002_create_vehicles_table`                     | Tabela `vehicles` (viaturas)                        |
| `2026_06_11_000003_create_teams_table`                        | Tabela `teams` (equipas de obra)                    |
| `2026_06_11_000004_create_team_employees_table`               | Pivot `team_employees`                              |
| `2026_06_11_000005_create_team_vehicles_table`                | Pivot `team_vehicles`                               |
| `2026_06_11_000006_create_project_companies_table`            | Pivot `project_companies` (obras ↔ empresas DocsEM) |
| `2026_06_11_000007_seed_permission_settings`                  | Seeder de permissões iniciais em `system_settings`  |
| `2026_06_12_000001_add_docsem_obra_id_to_projects_table`      | Campo `docsem_obra_id` para sincronização com DocsEM |
| `2026_06_12_000002_add_employees_count_to_project_companies`  | Campo `employees_count` em `project_companies`      |

---

## Notas de Desenvolvimento

- O `CLAUDE.md` foi substituído por este `DOCUMENTATION.md`
- O ficheiro `resources/views/layouts/app.blade.php.bak` está no `.gitignore` — não versionar
- O `docs/To do.md` deve ser mantido actualizado; o módulo de equipas/obras está implementado
- Para limpar assets antigos do Vite: `npm run build` apaga o manifesto anterior automaticamente via `emptyOutDir: true` em `vite.config.js`
- `PermissionService::$resolved` é cache de memória por request — thread-safe para o modelo single-threaded do PHP-FPM

---

---

## Simulador de Disponibilidade Técnica

Ferramenta que permite ao gestor verificar se a empresa tem técnicos certificados suficientes para uma empreitada, e identificar lacunas com tempo para as colmatar.

### Acesso
- GET `/trainings/staffing-check` — vista (Gate: `manage-hr`)
- POST `/api/v1/staffing-check` — endpoint de verificação (Gate: `manage-hr`)

### Controllers
- `App\Http\Controllers\Web\StaffingCheckWebController` — serve a view com a lista de formações
- `App\Http\Controllers\StaffingCheckController::check()` — lógica principal

### Inputs da simulação
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `start_date` | date | Início da empreitada |
| `end_date` | date | Fim da empreitada |
| `requirements[]` | array | Lista de `{training_id, quantity}` |

### Lógica de negócio
Para cada requisito, o sistema consulta `EmployeeTraining` (status `enrolled`/`completed`) de funcionários activos e categoriza cada inscrição:

| Categoria | Condição | Conta como disponível? |
|-----------|----------|------------------------|
| `qualified` | `expiry >= end_date` | ✅ Sim |
| `no_expiry` | `expiry == null` | ✅ Sim |
| `expiring_during` | `expiry >= start AND expiry < end` | ✅ Sim (com aviso) |
| `expired_before` | `expiry < start` | ❌ Não |

`available = count(qualified) + count(no_expiry) + count(expiring_during)`

O status por requisito é:
- `ok` — `available >= needed` e sem `expiring_during`
- `warning` — `available >= needed` mas existe pelo menos um `expiring_during`
- `gap` — `available < needed`

### Resposta da API
```json
{
  "start_date": "2026-09-01",
  "end_date": "2026-09-30",
  "duration_days": 30,
  "global_status": "ok|warning|gap",
  "total_gap": 0,
  "results": [{
    "training_id": 1,
    "training_title": "...",
    "needed": 3,
    "available": 3,
    "gap": 0,
    "status": "ok",
    "days_until_start": 75,
    "qualified": [...],
    "no_expiry": [...],
    "expiring_during": [...],
    "expired_before": [...]
  }]
}
```

### Vista (`trainings/staffing-check.blade.php`)
- Layout duas colunas: formulário (sticky) + resultados
- Banner global com estado (✅ ok / ⚠️ warning / 🚨 gap)
- Cards por formação com barra de progresso de disponibilidade, chips de técnicos e alertas de renovação
- **Exportar PDF**: botão "📄 Exportar PDF" (activado após simulação) — usa `window.print()` com `@media print` CSS dedicado; sem dependência server-side
- Banner de contexto quando pré-preenchido a partir de uma obra

### Integração com Obras
Cada card de obra tem um botão **"🔍 Disponibilidade"** que navega para `/trainings/staffing-check?start=YYYY-MM-DD&end=YYYY-MM-DD&name=NomeObra`. A vista lê estes parâmetros via `URLSearchParams` e pré-preenche os campos de data, exibindo um banner roxo com o nome da obra.

### Testes
`tests/Feature/StaffingCheckApiTest.php` — 17 testes cobrindo:
- Autorização (guest 401, employee 403, admin/hr 200)
- Validação (start_date, end_date, requirements)
- Cenários: ok, gap, warning (expiry durante obra), expirado antes, sem validade, inactivos, múltiplas formações
- Estrutura de resposta e cálculo de `duration_days`

---

## Portal do Funcionário — Licenças e Férias

### Fluxo de pedido (funcionário)
1. `/employee/leaves` — lista todos os seus pedidos com estado
2. Botão "Novo Pedido" → modal com: tipo (Férias/Doença/Não remunerada), data início, data fim, motivo (opcional)
3. POST `/api/v1/employee-portal/leaves` → cria com `status=pending`
4. Pode cancelar pedidos `pending` (DELETE)

### Fluxo de aprovação (manager/admin)
1. `/manager/leaves` — lista pedidos pendentes e histórico recente
2. Badge no menu lateral com contagem de pendentes
3. Botão Aprovar / Rejeitar → modal de confirmação com campo de comentário
4. PUT `.../approve` ou `.../reject`
5. Ao aprovar: `LeaveAttendanceSync::sync()` cria registos `on_leave` automaticamente
6. Ao rejeitar: registos anteriores desta licença são removidos

### Controlo de acesso (`EmployeeLeaveController::authorizeManager()`)
- `admin` e `hr`: passam sempre
- `manager`: verificado contra `department.manager_id` e `sector.manager_id`

---

## Relatórios — Exportação

### Excel (SheetJS)
- Biblioteca `xlsx` (npm) bundled via Vite em `reports.js` (~313KB)
- `window.attendanceAllRows` — global preenchido em `loadAttendance()` com todos os registos (não só os visíveis no DOM)
- Cada tab tem colunas e formatação específicas
- Larguras de coluna configuradas com `ws['!cols']`

### PDF (`window.print()` + CSS)
- Sem dependência server-side (sem DomPDF)
- `exportPdf(tab)` adiciona classe ao `<body>` antes de imprimir e remove após:
  - `printing-attendance`, `printing-employees`, `printing-trainings`, `printing-validity`, `printing-gaps`
- `@media print` em `reports/index.blade.php`: para cada classe, esconde todos os outros tabs e mostra só o activo
- Cada tab tem um bloco `*-print-block` com tabela pré-renderizada para impressão

---

## Serviço LeaveAttendanceSync

`app/Services/LeaveAttendanceSync.php`

Chamada: `(new LeaveAttendanceSync)->sync($leave);`

- `sync()`: se aprovada → `removeAttendances()` + `createAttendances()`; senão → só `removeAttendances()`
- `createAttendances()`: itera dias úteis (Seg–Sex) do período; cria ou actualiza para `on_leave` com `leave_id`
- `removeAttendances()`: apaga por `leave_id` + fallback para registos `on_leave` sem `leave_id` no período (licenças pré-migração)

---

## Frontend — Arquitectura JS

Os ficheiros JS estão extraídos das views para ficheiros dedicados em `resources/js/pages/`:

| Ficheiro         | View associada              | Tamanho aprox. |
|------------------|-----------------------------|----------------|
| `employees.js`   | `employees/index.blade.php` | 32KB           |
| `trainings.js`   | `trainings/index.blade.php` | 46KB           |
| `reports.js`     | `reports/index.blade.php`   | 52KB + SheetJS |

Bundled via Vite. `vite.config.js` usa `build: { emptyOutDir: false }` para evitar EPERM no Windows/Laragon (ficheiros CSS ficam bloqueados pelo browser). Cada view Blade inclui um `DOMContentLoaded` inline como fallback para garantir que a função de init é chamada mesmo com cache do browser a servir um bundle antigo.

---

## Pendente / Trabalho Futuro

Ver `docs/To do.md` para lista completa. Resumo:

- Aplicativo móvel / biométrico para controlo de presenças
- Portal próprio de gestão documental de subcontratadas (no DocsElectroMinho)
- Notificações por email ao submeter/aprovar/rejeitar pedidos de licença

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
php artisan db:seed --class=HolidaySeeder   # feriados nacionais PT 2025/2026

# 4. Assets
npm run build

# 5. Storage symlink
php artisan storage:link
```

### Desenvolvimento
```bash
composer run dev   # Laravel server + queue + logs (Pail) + Vite em simultâneo
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
