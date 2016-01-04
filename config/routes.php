<?php
use Ouzo\Routing\Route;

Route::get('/', 'games#index');
Route::get('/new_game', 'games#new_game');
Route::get('/game', 'games#game');
Route::get('/restart_game', 'games#restart_game');
Route::get('/cancel_game', 'games#cancel_game');
Route::post('/games/add_player/:id', 'games#add_player');
Route::post('/long_poll', 'events#poll');

Route::post('/hit', 'hit#index');

Route::resource('players');
