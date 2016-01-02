<?php
use Ouzo\Routing\Route;

Route::get('/', 'games#index');

Route::resource('players');
