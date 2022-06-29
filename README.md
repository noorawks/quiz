# Quiz Apps
- PHP 7.3.14
- Laravel 6.20.44
- Axios
- Vue JS

## How to use

### Preparation
- Make sure you have PHP and Composer installed globally on your computer.
- Clone the repo and enter the project folder

```
git clone https://github.com/noorawks/quiz.git
cd quiz
```

### Install the app

```
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

### Install the Database and create Dummy Data 
- Make sure to create database `quiz` on your PHPMyAdmin

```
php artisan migrate
php artisan create:admin
php artisan db:seed
```

Run the web server

```
php artisan serve
```

That's it. Now you can use the app with credential below
```
email: admin@quiz.id
password: 123123
```
