# HRELETROMINHO - HR Management System

A modern and scalable Employee Management System built with Laravel 13 and **Blade templating**, featuring a modern web interface with **Tailwind CSS** and **Vite** for asset bundling, designed to streamline HR operations such as employee records, department management, attendance tracking, leave requests, career progression, and staff training.


## About

**HRELETROMINHO** is a full-featured HR management application designed to streamline employee management, attendance tracking, leave management, department organization, and training administration. This project is currently in early development stages and aims to provide a user-friendly solution for managing all aspects of human resources.

## Tech Stack

- **Backend**: PHP 8.3+ with Laravel 13
- **Frontend**: Blade Templates (35%) + Tailwind CSS 4.0
- **Build Tool**: Vite 8.0 with Laravel Vite Plugin
- **Database**: SQLite (default, configurable)
- **Testing**: PHPUnit 12.5+
- **Development Tools**: Laravel Pint, Faker, Mockery

## Key Features

### Employee Management
- Create and manage employee records
- Track employee details and assignments

### Department Management
- Organize departments within the organization
- Assign employees to departments

### Position Management
- Define and manage job positions
- Link positions to employees and departments

### Sectors
- Organize company structure by sectors

### Attendance Tracking
- Monitor employee attendance records
- Track attendance patterns

### Leave Management
- Manage leave requests and approvals
- Track available leave days

### Training Management
- Organize and track employee training programs
- Monitor training history

### Reporting
- Generate comprehensive HR reports
- Access analytics and insights

### User Management
- Administrative user control
- Role-based access management

### Authentication
- Secure login system
- Session management

## Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js and npm
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/adalbertoprog/hreminho.git
   cd hreminho
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

### Quick Setup Script

The project includes a convenient setup script:
```bash
composer run setup
```

This will:
- Install PHP dependencies
- Copy `.env` file
- Generate application key
- Run migrations
- Install npm packages
- Build frontend assets

## Development

### Start Development Server

Run the development command to start all services concurrently:

```bash
composer run dev
```

This command will start:
- Laravel development server
- Queue listener
- Laravel Pail (logs)
- Vite development server

### Build for Production

```bash
npm run build
```

### Testing

Run the test suite:
```bash
composer run test
```

This will clear config cache and run PHPUnit tests.

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Web/
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   ├── Models/
│   └── ...
├── routes/
│   └── web.php
├── resources/
│   ├── views/
│   └── css/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── config/
├── public/
└── storage/
```



## Database Configuration

By default, HREMINHO uses MySQL. To configure a different database:

1. Edit `.env` file
2. Uncomment and configure `DB_*` variables for SQLite/PostgreSQL/etc
3. Run migrations


## Available Commands

### Composer Scripts

```bash
composer run setup      # Complete project setup
composer run dev        # Development mode with watchers
composer run test       # Run tests
```

### Artisan Commands

```bash
php artisan serve       # Start development server
php artisan migrate     # Run migrations
php artisan tinker      # Interactive shell
php artisan queue:listen    # Listen for queued jobs
php artisan pail        # Tail application logs
```


## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support & Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com)
- [Vite Documentation](https://vitejs.dev)

---

**Author**: Adalberto Prog 
**Repository**: https://github.com/adalbertoprog/hreminho  
**Created**: April 2026
