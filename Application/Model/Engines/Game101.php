<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use Ouzo\View;

class Game101 implements GameEngine
{
    /**
     * @var Game
     */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function renderView()
    {
        $view = new View('Engines/101');
        $view->game = $this->game;
        return $view->render();
    }

    public function isWinner()
    {
        return $this->game->current_game_user->score == 101;
    }

    public function isScored($field, $multiplier)
    {
        $currentScore = $this->game->current_game_user->score;
        $newScore = $currentScore + ($field * $multiplier);
        if ($newScore <= 101) {
            $this->game->current_game_user->updateAttributes(['score' => $newScore]);
            return true;
        }
        return false;
    }
}
