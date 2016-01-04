<?php
use Ouzo\Routing\Route;

Route::get('/', 'games#index');
Route::get('/new_game', 'games#new_game');
Route::get('/game', 'games#game');
Route::get('/test', 'games#test');

Route::post('/games/restart', 'games#restart');
Route::post('/games/cancel', 'games#cancel');
Route::post('/games/add_player/:id', 'games#add_player');
Route::post('/games/next_player', 'games#next_player');

Route::post('/long_poll', 'events#poll');

Route::post('/hit', 'hits#index');

Route::resource('players');
