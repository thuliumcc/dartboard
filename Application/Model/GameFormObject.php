<?php

namespace Application\Model;


use Ouzo\Utilities\Arrays;
use Ouzo\Validatable;

class GameFormObject extends Validatable
{
    public $game;
    public $userIds;
    public $type;

    public function __construct($game = null)
    {
        $this->game = $game ?: new Game();
    }

    public function assignAttributes($params)
    {
        $this->userIds = Arrays::getValue($params, 'players', []);
        $this->type = Arrays::getValue($params['game'], 'type');
    }

    public function save()
    {
        $this->game->type = $this->type;
        $this->game->insert();
        foreach($this->userIds as $userId) {
            $this->game->addPlayer($userId);
        }
    }

    public function getModelName()
    {
        return 'game';
    }

    public function validate()
    {
        parent::validate();
        $this->validateTrue(!empty($this->userIds), 'Select players');
    }

}