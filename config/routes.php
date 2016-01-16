<?php
use Ouzo\Routing\Route;

Route::get('/', 'home#index');
Route::get('/new_game', 'games#new_game');
Route::get('/end_game', 'games#end_game');
Route::get('/test', 'games#test');
Route::get('/game_content', 'games#game_content');

Route::get('/games', 'games#index');
Route::get('/games/current', 'games#game');
Route::get('/games/:id', 'games#show');
Route::post('/games', 'games#create');
Route::post('/games/restart', 'games#restart');
Route::post('/games/cancel', 'games#cancel');
Route::post('/games/next_player', 'games#next_player');

Route::post('/long_poll', 'events#poll');

Route::post('/hit', 'hits#index');

Route::resource('players');
