<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int game_id
 * @property int user_id
 * @property int score
 * @property int ordinal
 * @property int current_game_user_id
 * @property User user
 * @property Game game
 */
class GameUser extends Model
{
    const POSSIBLE_SHOTS = 3;

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['game_id', 'user_id', 'score' => 0, 'ordinal'],
            'belongsTo' => [
                'user' => ['class' => 'User', 'foreignKey' => 'user_id'],
                'game' => ['class' => 'Game', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ]);
    }

    public function getScore($field)
    {
        $sum = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->id, 'field' => $field])->fetch();
        return min($sum[0], 3);
    }

    public function getLeftShoots()
    {
        $shoots = self::POSSIBLE_SHOTS - Hit::count(['game_user_id' => $this->getId(), 'round' => $this->game->round]);
        return $shoots > 0 ? $shoots : null;
    }

    public function delete()
    {
        Hit::where(['game_user_id' => $this->id])->deleteAll();
        return parent::delete();
    }
}
