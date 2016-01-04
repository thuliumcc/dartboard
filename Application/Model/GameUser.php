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
    private $_fields = array('game_id', 'user_id', 'score' => 0, 'ordinal');

    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'attributes' => $attributes,
            'fields' => $this->_fields,
            'belongsTo' => [
                'user' => ['class' => 'User', 'foreignKey' => 'user_id'],
                'game' => ['class' => 'Game', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ));
    }
}