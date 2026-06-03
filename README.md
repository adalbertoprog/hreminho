# HRElectrominho — Sistema de Gestão de Recursos Humanos

Sistema de gestão de RH desenvolvido para a empresa **Electrominho**. Cobre a gestão completa de funcionários, departamentos, presenças, férias, formações com vídeo e questionários, e integração com o sistema documental externo DocsElectroMinho.

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

### Presenças e Férias
- Registo diário de presenças por funcionário
- Pedidos de férias/licenças com anexos e histórico

### Formações
- Catálogo de formações com vídeos (upload ou URL externa) e questionários
- Quiz com perguntas de escolha múltipla (MC) e verdadeiro/falso (TF)
- Pontuação mínima configurável por questionário (default: 70%)
- Resultados por formação: melhor pontuação por utilizador, filtros por nome/código e estado
- Formações obrigatórias: regras por departamento, cargo ou todos, com tracking de compliance
- Plano anual: vista de calendário por meses com campos financeiros (custo por pessoa, participantes estimados)
- Dashboard de formações com KPIs, evolução e compliance

### Portal do Funcionário
- Dashboard pessoal com perfil e formações disponíveis
- Player de vídeo integrado + realização de questionários
- Quiz bloqueado até todos os vídeos da formação serem vistos
- Histórico de tentativas por formação
- Auto-associação da conta por código de funcionário

### Relatórios
- Formações concluídas (filtros por formação, sector, data)
- Funcionários com formações concluídas
- Sumário de presenças
- Validade de certificados
- Análise de lacunas (formações obrigatórias, certificados expirados, plano vs execução)
- Envio de relatórios por e-mail

### Integração DocsElectroMinho
- Sincronização de funcionários activos com sistema externo de gestão documental
- Sincronização global ou individual por funcionário
- Página de estado e ping em `/docsem`

### Autenticação
- Login por e-mail **ou** código de funcionário (ex.: `FUN0777`)
- Três roles: `admin`, `hr`, `employee` — com redirects e acessos distintos
- Sessão persistente com "Manter sessão iniciada"
- Mudança de password obrigatória no primeiro login (`must_change_password`)

---

## Roles de Utilizador

| Role       | Acesso                                              |
|------------|-----------------------------------------------------|
| `admin`    | Back-office completo — todas as operações           |
| `hr`       | Mesmo acesso que admin                              |
| `employee` | Portal do funcionário (`/employee/dashboard`) apenas |

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

# 4. Executar migrações
php artisan migrate

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
│   │   └── Web/             # Controllers Web (Blade)
│   ├── Middleware/          # ForcePasswordChange
│   └── Requests/            # Form Requests com validação
├── Models/                  # Eloquent models
├── Providers/               # AppServiceProvider (Gates de autorização)
└── Services/                # DocsElectroMinhoService
routes/
├── web.php                  # Rotas Blade (auth + force.password.change)
└── api.php                  # Rotas API /api/v1/ (auth:web)
resources/views/
├── layouts/                 # app, guest
├── auth/                    # login, change-password
├── dashboard/               # back-office
├── employees/               # CRUD + associação + geração em massa
├── trainings/               # CRUD + conteúdo + dashboard + plano anual
├── employee/                # portal do funcionário
├── reports/                 # relatórios
└── docsem/                  # integração DocsElectroMinho
docs/
├── DEPLOY.md                # Guia de deploy para produção
└── To do.md                 # Backlog e bugs conhecidos
database/migrations/         # 25 migrações desde Jan 2024 a Jun 2026
```

---

**Autor**: Adalberto Filipe  
**Criado**: Abril 2026  
**Última actualização**: Junho 2026
