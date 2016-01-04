<?php
namespace Application\Controller;

use Application\Model\Game;
use Application\Model\GameUser;
use Ouzo\Controller;

class GamesController extends Controller
{
    public function init()
    {
        $this->layout->setLayout('layout');
    }

    public function index()
    {
        $game = Game::currentGame();
        if ($game) {
            $this->view->game = $game;
            $this->view->render('Games/game');
        } else {
            $this->view->render();
        }
    }

    public function new_game()
    {
        Game::create();
        $this->view->game = Game::currentGame();
        $this->view->render();
    }

    public function restart_game()
    {
        GameUser::queryBuilder()->deleteAll();
        Game::queryBuilder()->deleteAll();
        Game::create();
        $this->redirect(newGameGamesPath());
    }

    public function cancel_game()
    {
        GameUser::queryBuilder()->deleteAll();
        Game::queryBuilder()->deleteAll();
        $this->redirect(indexGamesPath());
    }

    public function add_player()
    {
        $game = Game::currentGame();
        $game->addPlayer($this->params['id']);
        $this->redirect(newGameGamesPath());
    }

    public function game()
    {
        $this->view->game = Game::currentGame();
        $this->view->render();
    }
}
