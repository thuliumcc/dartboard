<?php
namespace Application\Controller;

use Application\Model\Event;
use Application\Model\Game;
use Ouzo\Controller;
use Ouzo\Utilities\Arrays;

class GamesController extends Controller
{
    public function init()
    {
        $this->layout->setLayout('layout');
    }

    public function index()
    {
        $game = Game::currentGame();
        if ($game && $game->isStarted()) {
            $this->view->game = $game;
            $this->view->render('Games/game');
        } else {
            $this->view->render();
        }
    }

    public function game_content()
    {
        $this->layout->setLayout('ajax_layout');
        $game = Game::currentGame();
        if ($game) {
            $this->view->game = $game;
            if ($game->isFinished()) {
                $this->view->render('Games/end_game');
            } else {
                $this->view->render();
            }
        }
    }

    public function new_game()
    {
        $game = Game::currentGame();
        if (!$game) {
            $game = Game::create();
        }
        $this->view->game = $game;
        $this->view->render();
    }

    public function restart()
    {
        $unfinishedGame = Game::findUnfinishedGame();
        if ($unfinishedGame) {
            $unfinishedGame->delete();
        }
        Game::create();
        $this->redirect(newGameGamesPath());
    }

    public function cancel()
    {
        Game::findUnfinishedGame()->delete();
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
        $type = Arrays::getValue($this->params, 'type');
        $game = Game::currentGame();
        if ($type) {
            $game->setType($type);
        }
        if ($game->hasPlayers()) {
            $this->view->game = $game;
            $this->view->render();
        } else {
            $this->redirect(newGameGamesPath());
        }
    }

    public function test()
    {
        $this->view->render();
    }

    public function next_player()
    {
        $game = Game::currentGame();
        $game->nextPlayer();
        Event::create(['name' => 'refresh', 'params' => json_encode([])]);
        $this->layout->renderAjax();
    }

    public function end_game()
    {
        $this->layout->setLayout('ajax_layout');
        $game = Game::currentGame();
        $game->endedByCurrentGameUser();
        $this->view->game = $game;
        $this->view->render();
    }

    public function stats()
    {
        $this->view->game = Game::currentGame();
        $this->view->render();
    }
}
