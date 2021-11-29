# Prject name
**Fizzyfest**

## Pre requirements

### 1. Create .env file
Create `.env` file and set up application environment.
```
cp .env.example .env
php artisan key:generate
```

### 2. Install Project
Install `npm` package, get `vendor` file.
```
npm install && npm run watch
composer install
```

### 3. Generate migration
Generate test data, install laravel passport and create storage link
```
php artisan migrate:fresh --seed
php artisan passport:install
php artisan storage:link
```

# Run Project
Run websocket server and local server
```
php artisan websocket:serve
php artisan serve
```

# Links
- [download-theme](https://github.com/stisla/stisla/archive/v2.2.0.zip)
- [user-only-free-icons](https://fontawesome.com/icons?d=gallery&m=free)
