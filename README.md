<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Event Management System

### Prerequisites

Before you begin, ensure you have the following prerequisites installed:

-   PHP 8.1 or higher
-   Composer (latest version)
-   Database (MySQL)

### Installation Steps

1. Clone the Repository

```bash
git clone https://github.com/your-repo/your-project.git
cd your-project
```

2. Install Dependencies

```bash
composer install
```

3. Set Up Environment
   Copy the `.env.example` file and paste and rename to `.env` file:

```bash
cp .env.example .env
```

4. Configure SMTP
   Ensure you set up SMTP settings in your `.env` file for email functionality. Example configuration:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

5. Generate Application Key

```bash
php artisan key:generate
```

6. Migrate and Seed Database

```bash
php artisan migrate
php artisan db:seed
```

7. Create Storage Symlink

```bash
php artisan storage:link
```

8. Start the Application Server

```bash
php artisan serve
```

9. Open other terminal and running the Queue Worker

```bash
php artisan queue:work
```

Your Laravel application is now ready to use! 🚀
