# PHP Blog

A blog built with PHP and MySQL using MVC architecture.

## Requirements

- PHP 8.1+
- MySQL
- Composer

## Quick Start

```bash
composer install
cp .env.example .env
# Configure database credentials in .env
php bin/migrate migrate --no-interaction
php composer start
```

## Features

- MVC architecture with PSR-4 autoloading
- Doctrine Migrations for database versioning
- Twig templating engine
- Admin dashboard with CRUD operations
- Contact form management
- SEO-friendly URLs via `.htaccess`
