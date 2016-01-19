<?php
namespace Application\Controller;

use Application\Model\Event;
use Application\Model\Game;
use Application\Model\GameFormObject;
use Ouzo\Controller;

class GamesController extends Controller
{
    public function init()
    {
        $this->layout->setLayout('layout');
    }

    public function index()
    {
        $this->view->games = Game::where(['finished' => true])->order('started_at desc, id desc')->fetchAll();
        $this->view->render();
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
        $this->view->game = new GameFormObject();
        $this->view->render();
    }

    public function create()
    {
        $game = new GameFormObject();
        $game->assignAttributes($this->params);
        if ($game->isValid()) {
            $game->save();
            $this->redirect(gameGamesPath());
        } else {
            $this->view->game = $game;
            $this->view->render('Games/new_game');
        }
    }

    public function create_new()
    {
        $unfinishedGame = Game::findUnfinishedGame();
        if ($unfinishedGame) {
            $unfinishedGame->delete();
        }
        $this->redirect(newGameGamesPath());
    }

    public function cancel()
    {
        Game::findUnfinishedGame()->delete();
        $this->redirect(indexHomePath());
    }

    public function game()
    {
        $this->view->game = Game::currentGame();
        $this->view->render();
    }

    public function show()
    {
        $this->view->game = Game::findById($this->params['id']);
        $this->view->render();
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
}
