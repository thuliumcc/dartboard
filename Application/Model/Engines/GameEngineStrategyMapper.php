<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use BadMethodCallException;

class GameEngineStrategyMapper
{
    /**
     * @param $type
     * @param Game $game
     * @return GameEngine
     */
    public static function instance($type, Game $game)
    {
        switch ($type) {
            case 'cricket':
                return new Cricket($game);
                break;
            case '101':
                return new Game101($game);
                break;
            default:
                throw new BadMethodCallException('Unknown type [' . $type . ']');
        }
    }
}
