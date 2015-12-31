<?php
namespace Application\Model;

use Ouzo\Model;

/**
 */
class Game extends Model
{
    private $_fields = array('id', 'current_game_user_id');

    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'attributes' => $attributes,
            'fields' => $this->_fields,
            'belongsTo' => [
                'current_game_user' => ['class' => 'GameUser'],
            ]
        ));
    }
}