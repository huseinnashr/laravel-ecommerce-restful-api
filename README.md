# Laravel e-commerce Restful API

## What is this

This is an exercise project by following [RESTful API with Laravel](https://www.udemy.com/course/restful-api-with-laravel-php-homestead-passport-hateoas/) course ([the repo](https://github.com/JuanDMeGon/RESTful-API-with-Laravel-Definitive-Guide)) with some diffrences.

## What's this cover

Eloquent ORM and model event - Collection - File Upload - Email - Migration - Factories and Seeding - Request and Response Transformation - Complex Model Operation - Paging - Caching - Hateoas (or REST level 3) -
Validation - Error Handling - Middlewares - Passport and Scopes - Policy - Gate

## What I Did Differently

### Docker instead of Vagrant

I was using [docker-compose-laravel](https://github.com/aschmelyun/docker-compose-laravel) for the development environment instead of vagrant. With that setup, i didn't need to install dev app (such as artisan or composer) on my local machine making the project more organize and can be run anywhere (with docker). Docker also faster than vagrant.

### Laravel 7

The tutorial was 3 years old and using laravel 5.4. I'd decided to use laravel 7 to see the differences. Indeed, there are differences and i've learned so much figuring out why things not working the same as the tutorial. But I've also learned that there were barely any breaking changes from 5.4 to 7 as long as you stick to the pattern and not doing hacky implementation.

## Want to know more

### The bussiness process

This is an e-commerce backend which include buyer, seller, product, category and transaction model. You can go to `/design-artifacts` to see the list of endpoints and the database diagram.

### How to run

1. Download docker
2. Run `docker-compose run -d --build` to build and run the container in detach mode.
3. Run `docker-compose run --rm artisan migrate:refresh --seed` to migrate & seed the database.
4. The system should be running on `localhost:8080`
