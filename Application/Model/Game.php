<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int current_game_user_id
 * @property int round
 * @property int id
 */
class Game extends Model
{
    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'attributes' => $attributes,
            'fields' => ['id', 'current_game_user_id', 'round' => 1],
            'belongsTo' => [
                'current_game_user' => ['class' => 'GameUser', 'foreignKey' => 'current_game_user_id'],
            ],
            'hasMany' => [
                'game_users' => ['class' => 'GameUser', 'foreignKey' => 'game_id']
            ]
        ));
    }

    public static function currentGame()
    {
        // in the future we will have 'finished' flag or something
        return Game::queryBuilder()->fetch();
    }

    public function possibleUsers()
    {
        $currentGame = self::currentGame();
        return User::where('not exists (select true from game_users where game_id = ? AND user_id = users.id)', $currentGame->id)->fetchAll();
    }
}