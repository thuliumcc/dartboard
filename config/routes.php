<?php
use Ouzo\Routing\Route;

Route::get('/', 'games#index');
Route::post('/games', 'games#new_game', ['as' => 'new_game']);
Route::post('/games/add_player/:id', 'games#add_player');

Route::resource('players');
