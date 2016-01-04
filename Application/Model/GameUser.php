<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int game_id
 * @property int user_id
 * @property int score
 * @property int ordinal
 * @property int current_game_user_id
 */
class GameUser extends Model
{
    private $_fields = ['game_id', 'user_id', 'score' => 0, 'ordinal'];

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => $this->_fields,
            'belongsTo' => [
                'user' => ['class' => 'User', 'foreignKey' => 'user_id'],
                'game' => ['class' => 'Game', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ]);
    }

    function getScore($field)
    {
        $sum = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->id, 'field' => $field])->fetch();
        return min($sum[0], 3);
    }

    public function delete()
    {
        Hit::where(['game_user_id' => $this->id])->deleteAll();
        return parent::delete();
    }
}
