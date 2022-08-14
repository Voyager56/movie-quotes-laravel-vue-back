## Table of Contents

-   [Introduction](#introduction)
-   [Prerequisites](#prerequisites)
-   [Tech Stack](#tech-stack)
-   [Getting Started](#getting-started)
-   [Development](#development)
-   [Deployment](#deployment)
-   [Resources](#resources)

## Introduction

<p> 
    This is a webapp for checking the world coronavirus statistics.
 </p>

## Prerequisites

-   [PHP@8.0 and up ](https://www.php.net/downloads)
-   [Composer@2.3.5 and up ](https://getcomposer.org/download/)
-   [Laravel@9 and up](https://laravel.com/docs/7.x/installation)
-   [npm@8 and up](https://nodejs.org/en/download/)
-   [MySQL Latest](https://www.mysql.com/downloads/)
-   [Swagger-ui](https://swagger.io/docs/specification/about/)
-   [JWT-auth](https://jwt-auth.readthedocs.io/en/develop/)
-   [Pusher](https://pusher.com/)
## Tech Stack

-   [PHP](https://www.php.net/)
-   [Laravel](https://laravel.com/)
-   [MySQL](https://www.mysql.com/)

## Getting Started

-   Installation:

Clone the repository: `git clone https://github.com/RedberryInternship/Chad-movie-quotes-back-Levan-Mikatadze`

Go to the root directory of the repository: `cd Chad-movie-quotes-back-Levan-Mikatadze`

Install neccesary dependencies: `composer install`

Install node modules: `npm install`

Copy the .env file to the root directory: `cp .env.example .env`
and fill in the values for the database connection.

go to jwt laravel documentation and follow the instructions to install jwt-auth.

Generate the keys for the application: `php artisan key:generate`

Initialize the database: `php artisan migrate:fresh`

create pusher channel and follow the instructions to integrate it into application.

run `php artisan genre:populate` to initialize static genre database

## Development

to run the application: `php artisan serve`
and for live reloading: `npm run watch`

for swagger documentation run: `npm run dev` with `php artisan serve` then go to `app-url/swagger`

## Deployment

-   ssh into the server: `ssh username@ipaddress`
-   run sudo apt update
-   run `sudo add-apt-repository ppa:ondrej/php`
-   run `sudo apt install php8.0 php8.0-curl php8.0-mbstring php8.0-xml php8.0-sqlite3`
-   run `sudo apt purge apache2`
-   to install sqlite3 run `sudo apt install zip sqlite3` and and for php `curl https://getcomposer.org/installer | php` then `sudo mv composer.phar /usr/bin/composer`
-   for node `curl https://deb.nodesource.com/setup_14.x | sudo bash ` then `sudo apt install nodejs`

-   install the application but use sqlite3 instead of mysql, run php artisan optimize for better performance

-   after all this install php-fpm and nginx and configure them.

## Resources

-   [Draw Sql](https://drawsql.app/teams/redberry-18/diagrams/chad-movie-quotes)