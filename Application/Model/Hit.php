<?php
namespace Application\Model;

use BadMethodCallException;
use Ouzo\Model;

/**
 * @property int game_user_id
 * @property int field
 * @property int multiplier
 * @property int round
 * @property GameUser userGame
 */
class Hit extends Model
{
    private static $multiplierCharMap = [
        's' => 1,
        'd' => 2,
        't' => 3
    ];

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['game_user_id', 'field', 'multiplier', 'round'],
            'belongsTo' => [
                'userGame' => ['class' => 'GameUser', 'foreignKey' => 'game_user_id'],
            ]
        ]);
    }

    /**
     * @param $field
     * @param GameUser $gameUser
     * @return Hit
     */
    public static function createFor($field, GameUser $gameUser)
    {
        if (preg_match('/(\d+)([sdt])/', $field, $matches)) {
            $multiplier = self::$multiplierCharMap[$matches[2]];
            return self::create(['game_user_id' => $gameUser->getId(), 'field' => $matches[1], 'multiplier' => $multiplier, 'round' => $gameUser->game->round]);
        }
        throw new BadMethodCallException('Cannot parse field');
    }
}