# HRElectrominho — Sistema de Gestão de Recursos Humanos

Sistema de gestão de RH desenvolvido para a empresa **Electrominho**. Cobre a gestão completa de funcionários, departamentos, presenças, licenças/férias, feriados, formações com vídeo e questionários, portal do funcionário com fluxo de aprovação de licenças, relatórios exportáveis (Excel/PDF) e integração com o sistema documental externo DocsElectroMinho.

---

## Tech Stack

- **Backend**: PHP 8.3+ com Laravel 13 (`bootstrap/app.php`, sem Kernel.php)
- **Frontend**: Blade Templates + CSS custom (dark theme com variáveis CSS) + Vanilla JS
- **Build Tool**: Vite com Laravel Vite Plugin
- **Base de dados**: MySQL (`dbhreminho`)
- **URL local**: `http://hreminho.test`

---

## Funcionalidades

### Gestão de Funcionários
- Registo completo: dados pessoais, cargo, departamento, sector, foto de perfil (guardada em storage)
- Soft deletes — registos arquivados em vez de eliminados
- Associação a conta de utilizador por código de funcionário (ex.: `FUN0590`) ou e-mail
- Geração em massa de contas para todos os funcionários activos (password padrão: `12345678`, com flag `must_change_password`)

### Estrutura Organizacional
- Departamentos, Cargos (Positions) e Sectores com relações entre si
- Campo `manager_id` em departamentos e sectores — designa o responsável (role `manager`)

### Presenças
- Registo diário com entrada, saída, início e fim de almoço
- Status automático: Presente / Atrasado / Ausente / Licença / Feriado
- Tolerância de atraso e hora de entrada configuráveis em `/settings`
- Filtros rápidos (Hoje / Semana / Mês) e intervalo personalizado De/Até
- Barra de resumo com contadores e destaque de registos incompletos
- Vista semanal (grid Seg–Dom por funcionário)
- Managers vêem apenas os funcionários dos seus departamentos/sectores

### Licenças e Férias
- Funcionário submete pedido via portal (tipo, datas, motivo)
- Manager ou admin/hr aprova/rejeita com comentário
- Aprovação cria automaticamente registos de presença `on_leave` para dias úteis
- Rejeição remove os registos gerados
- Badge no menu com contagem de pedidos pendentes

### Feriados
- CRUD de feriados em `/settings` (nacional / local / empresa)
- Opção "repete anualmente" para feriados fixos (Natal, Ano Novo, etc.)
- Integrados no cálculo automático de status de presença

### Formações
- Catálogo de formações com vídeos (upload ou URL externa) e questionários
- Quiz com perguntas de escolha múltipla (MC) e verdadeiro/falso (TF)
- Pontuação mínima configurável por questionário (default: 70%)
- Resultados por formação: melhor pontuação por utilizador, filtros por nome/código e estado
- Formações obrigatórias: regras por departamento, cargo ou todos, com tracking de compliance
- Plano anual: vista de calendário por meses com campos financeiros (custo por pessoa, participantes estimados)
- Dashboard de formações com KPIs, evolução e compliance

### Portal do Funcionário
- Dashboard pessoal com perfil, resumo de presenças e widget de licenças
- Secção **Formações**: player de vídeo + quiz (bloqueado até ver todos os vídeos), histórico de tentativas
- Secção **Licenças e Férias**: submissão de pedidos, histórico com estados e cancelamento
- Auto-associação da conta por código de funcionário
- Role `manager`: acesso ao portal + gestão de presenças + aprovação de licenças da sua equipa

### Relatórios
- Formações concluídas (filtros por formação, sector, data)
- Funcionários com formações concluídas
- Sumário de presenças (com todos os campos: entrada, saída, almoço, horas trabalhadas)
- Validade de certificados
- Análise de lacunas (obrigatórias, certificados expirados, plano vs execução)
- Envio de relatórios por e-mail
- **Exportação Excel** via SheetJS (client-side, inclui todos os registos)
- **Exportação PDF** via `window.print()` com CSS dedicado por tab

### Integração DocsElectroMinho
- Sincronização de funcionários activos com sistema externo de gestão documental
- Sincronização global ou individual por funcionário
- Página de estado e ping em `/docsem`

### Configurações do Sistema
- Página `/settings` (admin/hr) com dois painéis:
  - **Horário**: hora de entrada esperada, tolerância de atraso, horas diárias, duração do almoço
  - **Feriados**: CRUD completo com filtro por ano e opção de repetição anual

### Autenticação
- Login por e-mail **ou** código de funcionário (ex.: `FUN0777`)
- Quatro roles: `admin`, `hr`, `manager`, `employee` — com redirects e acessos distintos
- Sessão persistente com "Manter sessão iniciada"
- Mudança de password obrigatória no primeiro login (`must_change_password`)

---

## Roles de Utilizador

| Role       | Acesso                                                                              |
|------------|-------------------------------------------------------------------------------------|
| `admin`    | Back-office completo — todas as operações                                           |
| `hr`       | Mesmo acesso que admin                                                              |
| `manager`  | Portal do funcionário + gestão de presenças e aprovação de licenças da sua equipa   |
| `employee` | Portal do funcionário (`/employee/dashboard`) apenas                                |

---

## Instalação

### Pré-requisitos
- PHP 8.3+
- Composer
- Node.js e npm
- MySQL

### Setup rápido

```bash
# Configura dependências, chave e base de dados num só comando
composer run setup
php artisan storage:link
```

### Passos manuais

```bash
# 1. Instalar dependências
composer install
npm install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Configurar base de dados em .env
# DB_CONNECTION=mysql
# DB_DATABASE=dbhreminho

# 4. Executar migrações e seeder de feriados
php artisan migrate
php artisan db:seed --class=HolidaySeeder

# 5. Compilar assets
npm run build

# 6. Symlink de storage (fotos de perfil e uploads)
php artisan storage:link
```

---

## Desenvolvimento

```bash
composer run dev
```

Inicia em simultâneo: servidor Laravel, queue listener, logs (Pail) e Vite dev server.

---

## Comandos Artisan

```bash
# Criar contas de utilizador para todos os funcionários activos sem conta
php artisan employees:create-users

# Simulação sem alterações na base de dados
php artisan employees:create-users --dry-run

# Sincronizar funcionários com DocsElectroMinho
php artisan docsem:sync
```

---

## Variáveis .env Relevantes

```env
APP_NAME=HRElectrominho
APP_ENV=local
APP_DEBUG=true
APP_URL=http://hreminho.test

DB_CONNECTION=mysql
DB_DATABASE=dbhreminho

DOCSEM_API_URL=http://docselectrominho.test/api
DOCSEM_API_TOKEN=...
DOCSEM_SYNC_ENABLED=true
```

Para produção consultar `docs/DEPLOY.md`.

---

## Estrutura de Pastas Relevante

```
app/
├── Console/Commands/        # CreateEmployeeUsers, SyncToDocsElectroMinho
├── Http/
│   ├── Controllers/         # Controllers API (JSON)
│   │   ├── Auth/            # LoginController
│   │   └── Web/             # Controllers Web (Blade) + EmployeeLeaveController
│   ├── Middleware/          # ForcePasswordChange
│   └── Requests/            # Form Requests com validação
├── Models/                  # Eloquent models (Employee, Leave, Attendance, Holiday, SystemSetting, ...)
├── Providers/               # AppServiceProvider (Gates de autorização)
└── Services/                # DocsElectroMinhoService, LeaveAttendanceSync
routes/
├── web.php                  # Rotas Blade (auth + force.password.change + portal + manager)
└── api.php                  # Rotas API /api/v1/ (auth:web)
resources/
├── js/pages/                # employees.js, trainings.js, reports.js (extraídos das views)
└── views/
    ├── layouts/             # app, guest
    ├── auth/                # login, change-password
    ├── dashboard/           # back-office
    ├── employees/           # CRUD + associação + geração em massa
    ├── trainings/           # CRUD + conteúdo + dashboard + plano anual
    ├── employee/            # portal: dashboard, leaves, manager-leaves, training
    ├── reports/             # relatórios com exportação Excel/PDF
    ├── settings/            # configurações + feriados
    └── docsem/              # integração DocsElectroMinho
docs/
├── DEPLOY.md                # Guia de deploy para produção
└── To do.md                 # Backlog e bugs conhecidos
database/migrations/         # 32 migrações desde Jan 2024 a Jun 2026
```

---

**Autor**: Adalberto Filipe  
**Criado**: Abril 2026  
**Última actualização**: Junho 2026 (rev. 11/06/2026)
