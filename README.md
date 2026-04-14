# Laravel-Freedom-Wall
Assignment 7 for the Laravel version of the Freedom Wall

## Program Notes:
- Install all necessary things such as composer and php.
- U have to manually create the freedom_board table in ur mysql db.
- Make a copy of the .env.example file, rename it to .env, and add ur database credentials or else the project won't work
- connection.php implementation has to be done manually in Laravel, so before running the web application, please cd to my-app and run the following commands to create the users and posts tables in the freedom_board table:
```bash
bash:
php artisan make:migration create_users_table
php artisan make:migration create_posts_table
```
- To run the app, cd to my-app and run in terminal ```php artisan serve``` and you can open the app at ```http://127.0.0.1:8000``` (it'll hopefully open our freedom board index)

## Progress:
### Finished Pages:
- index.blade.php
- login.blade.php
- register.blade.php
- thread.blade.php (simply the DisplayThread from the old register.php code)
- logout (not in blade.php but inside of FreedomWallController.php since it has no frontend)
- connection (not in blade.php but is configured through ```.env``` and php files in ```database/migrations```)

### Pages Needing Fixing From PHP to Laravel, Blade:
- post_message.blade.php
- connection.blade.php (not sure about this one)
- delete_post.blade.php

## Directories That Matter:
- ```resources/views```: this is where all our ```*.blade.php``` files are (the real coding stuff)
- ```public/css```: where our css files are (```index.css, log-reg.css```)
- ```routes```: specifically the web.php file, this is where we modify our routes (GET/POST)
- ```database/migrations```: files inside contain code that migrates (creates) tables into the selected database configured in the .env file
- ```app/Http/Controllers```: specifically ```FreedomWallController.php```. This file is where we put all out functions that are called in web.php for example:
Inside FreedomWallController.php:
```php
php:
class FreedomWallController extends Controller
{
  public function index() {
    //content
  }

  public function showRegister() {
    //content
  }

  public function register() {
    //content
  }
}
```

And inside of web.php:
```php
php:
Route::get('/', [FreedomWallController::class, 'index']); <-index here is a function inside of FreedomWallController.php

Route::get('/register', [FreedomWallController::class, 'showRegister']); <- same here with showRegister

Route::post('/register', [FreedomWallController::class, 'register']); <- and register
```
[Elizah] - April 15
- fixed post_message.blade.php (still needs: delete, and reply thread)
- deleted connection.blade.php
- deleted thread.blade.php 

