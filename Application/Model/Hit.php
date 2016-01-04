<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int game_user_id
 * @property int field
 * @property int multiplier
 */
class Hit extends Model
{
    private $_fields = ['game_user_id', 'field', 'multiplier'];

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => $this->_fields
        ]);
    }
}
