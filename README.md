# Laravel API Project

This is a Laravel-based API project designed to manage academic entities such as Dosen (lecturers) and Mahasiswa (students). The project includes authentication, middleware for API key and basic username authentication, and database seeding for initial data setup.

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd api
   ```

2. Install PHP dependencies using Composer:
   ```bash
   composer install
   ```

3. Install Node.js dependencies (for frontend assets if applicable):
   ```bash
   npm install
   ```

4. Copy the example environment file and configure your environment variables:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials and other necessary configuration.

5. Generate the application key:
   ```bash
   php artisan key:generate
   ```

## Configuration

- API keys are managed in `config/apikey.php`.
- Authentication settings are in `config/auth.php`.
- JWT settings are in `config/jwt.php`.

Ensure these configurations are set according to your environment.

## Usage

- Run database migrations:
  ```bash
  php artisan migrate
  ```

- Seed the database with initial data:
  ```bash
  php artisan db:seed
  ```

- Start the development server:
  ```bash
  php artisan serve
  ```

## API Endpoints

The API routes are defined in `routes/api.php`. Key controllers include:

- `AuthController.php`: Handles user authentication.
- `DosenController.php`: Manages lecturer-related operations.
- `MahasiswaController.php`: Manages student-related operations.

## Database

- Migrations are located in `database/migrations/`.
- Seeders are located in `database/seeders/`:
  - `UserSeeder.php`
  - `DosenSeeder.php`
  - `MahasiswaSeeder.php`
  - `DatabaseSeeder.php` (calls other seeders)

## Middleware

- `ApiKeyMiddleware.php`: Validates API key for incoming requests.
- `BasicAuthUsernameMiddleware.php`: Handles basic authentication using username.

## Testing

Run tests using PHPUnit:
```bash
php artisan test
```

## Contributing

Contributions are welcome. Please fork the repository and submit a pull request.

## License

This project is open-sourced software licensed under the MIT license.
