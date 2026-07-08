# Contributing

Thank you for considering contributing to this project.

## Getting Started

1. Fork the repository
2. Clone your fork
3. Run `composer install`
4. Copy `.env.example` to `.env` and configure your database
5. Run `composer migrate` to set up the database schema

## Code Style

- Follow PSR-12 coding standards
- Use type hints wherever possible
- Keep methods small and focused
- Write PHPDoc blocks for all classes and methods

## Pull Request Process

1. Create a feature branch from `main`
2. Make your changes
3. Run `composer validate` to ensure `composer.json` is valid
4. Ensure your code does not introduce debug output (`dump()`, `var_dump()`)
5. Submit a pull request with a clear description of the changes

## Reporting Issues

Report issues via the GitHub issue tracker with:
- A descriptive title
- Steps to reproduce
- Expected vs actual behavior
- Environment details (PHP version, database, OS)
