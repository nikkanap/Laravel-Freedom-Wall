# Laravel-Freedom-Wall
Assignment 7 for the Laravel version of the Freedom Wall

NOTE:
- Install all necessary things such as composer and php.
- Make sure u have the freedom_board table in ur mysql db.
- Make a copy of the .env.example file, rename it to .env, and add ur database credentials or else the project won't work
- to run the app, cd to my-app and run in terminal ```php artisan serve``` and you can open the app at ```http://127.0.0.1:8000``` (it'll hopefully open our freedom board index)

Finished Pages:
- index.blade.php
- login.blade.php
- register.blade.php
- thread.blade.php (simply the DisplayThread from the old register.php code)

Pages Needing Fixing From PHP to Laravel, Blade:
- logout.blade.php
- post_message.blade.php
- connection.blade.php (not sure about this one)
- delete_post.blade.php

Directories that matter:
- resources/views: this is where all our *.blade.php files are (the real coding stuff)
- public/css: where our css files are (index.css, log-reg.css)
- routes: specifically the web.php file, this is where we modify our routes (GET/POST)
- app/Http/Controllers: specifically FreedomWallController.php. This file is where we put all out functions that are called in web.php for example:
Inside FreedomWallController.php:
```
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
```
Route::get('/', [FreedomWallController::class, 'index']); <-index here is a function inside of FreedomWallController.php

Route::get('/register', [FreedomWallController::class, 'showRegister']); <- same here with showRegister

Route::post('/register', [FreedomWallController::class, 'register']); <- and register
```



