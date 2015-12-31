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
    private $_fields = array('game_id', 'user_id', 'score', 'ordinal', 'current_game_user_id');

    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'attributes' => $attributes,
            'fields' => $this->_fields,
            'belongsTo' => [
                'user' => ['class' => 'User'],
                'game' => ['class' => 'Game']
            ]
        ));
    }
}