<?php

namespace Application\Controller;


use Application\Model\Game;
use Ouzo\Controller;

class HomeController extends Controller
{
    public function init()
    {
        $this->layout->setLayout('layout');
    }

    public function index()
    {
        $game = Game::currentGame();
        if ($game && $game->isStarted()) {
            $this->redirect(gameGamesPath($game->id));
        } else {
            $this->view->render();
        }
    }
}