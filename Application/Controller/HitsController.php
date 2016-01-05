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
        $game = Game::currentGame();
        $field = $this->params['field'];
        Hit::createFor($field, $game->current_game_user);
        Event::create(['name' => 'hit', 'params' => '{"field":"' . $field . '"}']);
    }
}
