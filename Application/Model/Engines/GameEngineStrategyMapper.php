<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use BadMethodCallException;

class GameEngineStrategyMapper
{
    /**
     * @param Game $game
     * @return GameEngine
     */
    public static function instance(Game $game)
    {
        $type = $game->type;
        switch ($type) {
            case 'cricket':
                return new Cricket($game);
                break;
            case '101':
                return new Game101($game);
                break;
        }
        throw new BadMethodCallException('Unknown type [' . $type . ']');
    }
}
