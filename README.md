# Prject name
**Fizzyfest**

Fizzy Fest is a game-based learning platform in which players play the quiz created by the host. The quiz is fully customizable by the host. The host can create any type of quiz with any questions he/she wants.

When the quiz is successfully created host starts the game and 6 digit pin is created. Users/Players connect via that pin in that specific quiz and play.

Technologies Used :-

```
Laravel
VueJS
PostgreSQL
PHP
JQuery
Javascript
```

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
