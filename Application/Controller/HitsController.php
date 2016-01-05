<?php
namespace Application\Controller;

use Application\Model\Event;
use Application\Model\Game;
use Application\Model\Hit;
use Ouzo\Controller;

class HitsController extends Controller
{
    public function index()
    {
        $field = $this->params['field'];
        $game = Game::currentGame();
        $currentGameUser = $game->current_game_user;
        if ($currentGameUser->getLeftShoots() !== null) {
            Hit::createFor($field, $currentGameUser);
            Event::create(['name' => 'hit', 'params' => '{"field":"' . $field . '"}']);
        }
    }
}
