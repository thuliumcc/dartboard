<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use BadMethodCallException;
use Ouzo\Utilities\Arrays;

class GameEngineStrategyMapper
{
    private static $map = [
        'cricket' => '\Application\Model\Engines\Cricket',
        '101' => '\Application\Model\Engines\Game101',
    ];

    /**
     * @param Game $game
     * @return GameEngine
     */
    public static function instance(Game $game)
    {
        $type = $game->type;
        $class = Arrays::getValue(self::$map, $type);
        if ($class) {
            return new $class($game);
        }
        throw new BadMethodCallException('Unknown type [' . $type . ']');
    }
}
