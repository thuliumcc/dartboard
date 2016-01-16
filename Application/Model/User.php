<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int id
 * @property string login
 * @property string password
 * @property GameUser[] game_users
 */
class User extends Model
{
    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['id', 'login', 'password'],
            'hasMany' => [
                'game_users' => ['class' => 'GameUser', 'foreignKey' => 'user_id']
            ]
        ]);
    }

    public function validate()
    {
        parent::validate();
        $this->validateNotBlank($this->login, 'Login cannot be blank');
    }

    /**
     * @return int
     */
    public function countGames()
    {
        return count($this->game_users);
    }

    /**
     * @return int
     */
    public function countWonGames()
    {
        return GameUser::where(['user_id' => $this->id])
            ->innerJoin('winner_game')
            ->count();
    }

    /**
     * @return int
     */
    public function maxWonStreak()
    {
        $userIds = Game::where(['finished' => true])
            ->innerJoin('winner_game_user')
            ->order('games.id')
            ->select(['user_id'])
            ->fetchAll();
        $maxWonStreak = 0;
        $tmpStreak = 0;
        foreach ($userIds as $userId) {
            if ($userId[0] == $this->id) {
                $tmpStreak++;
            } else {
                $maxWonStreak = $tmpStreak > $maxWonStreak ? $tmpStreak : $maxWonStreak;
                $tmpStreak = 0;
            }
        }
        return $maxWonStreak;
    }
}
