# Deploy para Produção — HRElectrominho

## Pré-requisitos

- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js + npm
- Servidor web (Nginx/Apache) com HTTPS configurado

---

## 1. Configurar o `.env`

Copiar o `.env.example` e preencher todas as variáveis:

```bash
cp .env.example .env
```

Variáveis obrigatórias para produção:

```env
APP_NAME=HRElectrominho
APP_ENV=production
APP_DEBUG=false
APP_URL=https://hreminho.electrominho.pt   # URL real em produção

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbhreminho
DB_USERNAME=<utilizador_db>
DB_PASSWORD=<password_db>

SESSION_SECURE_COOKIE=true     # Obrigatório com HTTPS
SESSION_DRIVER=database
SESSION_LIFETIME=120

MAIL_MAILER=smtp               # Configurar SMTP real
MAIL_HOST=<smtp_host>
MAIL_PORT=587
MAIL_USERNAME=<email>
MAIL_PASSWORD=<password>
MAIL_FROM_ADDRESS=noreply@electrominho.pt
MAIL_FROM_NAME="HRElectrominho"

DEFAULT_ADMIN_PASSWORD=<password_forte_e_unica>   # Mínimo 12 caracteres

DOCSEM_API_URL=https://docselectrominho.pt/api
DOCSEM_API_TOKEN=<token_producao>
DOCSEM_SYNC_ENABLED=true
```

---

## 2. Instalar dependências

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

---

## 3. Gerar chave de aplicação

```bash
php artisan key:generate
```

---

## 4. Base de dados

```bash
# Correr todas as migrações
php artisan migrate --force

# Criar utilizadores admin/hr iniciais (apenas na primeira vez)
php artisan db:seed --class=DatabaseSeeder --force
```

> **Atenção:** O `DatabaseSeeder` requer `DEFAULT_ADMIN_PASSWORD` no `.env`.
> Os utilizadores criados terão `must_change_password=true` — têm de alterar a password no primeiro login.

---

## 5. Storage

```bash
# Criar symlink public/storage → storage/app/public
php artisan storage:link

# Garantir permissões de escrita
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 6. Cache de configuração e rotas

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Para limpar as caches (ex: após atualização):
> ```bash
> php artisan optimize:clear
> ```

---

## 7. Verificações pós-deploy

- [ ] `APP_DEBUG=false` no `.env`
- [ ] `APP_ENV=production` no `.env`
- [ ] HTTPS activo e `SESSION_SECURE_COOKIE=true`
- [ ] Login funciona com email e com código de funcionário
- [ ] Mudança de password obrigatória no primeiro login funciona
- [ ] Fotos de perfil carregam (symlink `public/storage` ok)
- [ ] Relatórios de lacunas carregam sem erros
- [ ] Upload de vídeos funciona (verificar espaço em disco)
- [ ] Email de relatório chega ao destino (testar via `/reports`)
- [ ] Integração DocsElectroMinho responde (`/docsem` → ping)

---

## 8. Actualizações futuras

```bash
git pull
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Utilizadores iniciais criados pelo seeder

| Email               | Role  | Password inicial        |
|---------------------|-------|-------------------------|
| admin@hreminho.com  | admin | `DEFAULT_ADMIN_PASSWORD` |
| hr@hreminho.com     | hr    | `DEFAULT_ADMIN_PASSWORD` |

Ambos têm `must_change_password=true` — a password tem de ser alterada no primeiro login.

---

## Segurança — notas importantes

- Nunca commitar o ficheiro `.env` (está no `.gitignore`)
- Usar uma `DEFAULT_ADMIN_PASSWORD` forte e diferente da password final
- `APP_DEBUG=false` impede exposição de stack traces e variáveis de ambiente
- `SESSION_SECURE_COOKIE=true` exige HTTPS — não ativar sem certificado SSL
- Logs em `storage/logs/laravel.log` — monitorizar regularmente
