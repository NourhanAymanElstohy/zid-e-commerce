## About Project

This Project is a simple e-commerce apis laravel 9

## Packages Used

-   php-open-source-saver/jwt-auth
-   spatie/laravel-permission

## Technologies used

-   PHP
-   Laravel
-   MySQL

## Features

For Merchant:

-   Can Create store or more.
-   Can update his store name.
-   Can decide if VAT value is included in product price or will be calculated from the price.

For Consumer:

-   Can add products from different stores to his cart.
-   Can remove item or more from his cart.
-   Can clear all items in his cart.
-   Can get cart total price.

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/installation#installation)

Clone the repository

```
git clone https://github.com/NourhanAymanElstohy/zid-e-commerce.git
```

Switch to the repo folder

```
cd zid-e-commerce
```

Install all the dependencies using composer

```
composer install
```

Copy the example env file and make the required configuration changes in the .env file

```
cp .env.example .env
```

Generate a new application key

```
php artisan key:generate
```

Run the database migrations and seed (**Set the database credentials in .env before migrating**)

```
php artisan migrate:fresh --seed
```

Start the local development server

```
php artisan serve
```

You can now access the server at http://localhost:8000 and test all APIs

**Command List**

```
git clone https://github.com/NourhanAymanElstohy/zid-e-commerce.git
cd zid-e-commerce
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```
