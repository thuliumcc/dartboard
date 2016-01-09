<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use Ouzo\View;

class Cricket implements GameEngine
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
        $view = new View('Engines/cricket');
        $view->game = $this->game;
        return $view->render();
    }
}
