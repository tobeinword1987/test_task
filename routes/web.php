<?php
Route::get('/', function () {
    return view('login');
});

Route::resource('/users','UserController');



