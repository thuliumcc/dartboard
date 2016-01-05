<?php
namespace Application\Model;

use BadMethodCallException;
use Ouzo\Model;

/**
 * @property int game_user_id
 * @property int field
 * @property int multiplier
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
            'fields' => ['game_user_id', 'field', 'multiplier'],
            'belongsTo' => [
                'userGame' => ['class' => 'GameUser', 'foreignKey' => 'game_user_id'],
            ]
        ]);
    }

    /**
     * @param $field
     * @param $gameUserId
     * @return Hit
     */
    public static function createFor($field, $gameUserId)
    {
        if (preg_match('/(\d+)([sdt])/', $field, $matches)) {
            $multiplier = self::$multiplierCharMap[$matches[2]];
            return self::create(['game_user_id' => $gameUserId, 'field' => $matches[1], 'multiplier' => $multiplier]);
        }
        throw new BadMethodCallException('Cannot parse field');
    }
}
