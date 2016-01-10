<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use BadMethodCallException;
use Exception;
use Ouzo\Utilities\Arrays;

class GameEngineStrategyMapper
{
    private static $classMap = [
        'cricket' => '\Application\Model\Engines\Cricket',
        '101' => '\Application\Model\Engines\Game101',
    ];

    private static $nameMap = [
        'cricket' => 'Cricket',
        '101' => '101'
    ];

    /**
     * @param Game $game
     * @return GameEngine
     */
    public static function instance(Game $game)
    {
        $type = $game->type;
        $class = Arrays::getValue(self::$classMap, $type);
        if ($class) {
            return new $class($game);
        }
        throw new BadMethodCallException('Unknown type [' . $type . ']');
    }

    public static function getTypeNames()
    {
        $typeNamesMap = [];
        foreach (self::$classMap as $type => $class) {
            $name = Arrays::getValue(self::$nameMap, $type);
            if ($name) {
                $typeNamesMap[$type] = $name;
            } else {
                throw new Exception('Not defined name for the game type [' . $type . ']');
            }
        }
        return $typeNamesMap;
    }
}
