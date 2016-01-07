<?php
namespace Application\Model;

use Ouzo\Model;

/**
 * @property int current_game_user_id
 * @property int round
 * @property int id
 * @property GameUser current_game_user
 * @property GameUser[] game_users
 */
class Game extends Model
{
    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['id', 'current_game_user_id', 'round' => 1],
            'belongsTo' => [
                'current_game_user' => ['class' => 'GameUser', 'foreignKey' => 'current_game_user_id', 'referencedColumn' => 'id'],
            ],
            'hasMany' => [
                'game_users' => ['class' => 'GameUser', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ]);
    }

    /**
     * @return Game
     */
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

    public function addPlayer($id)
    {
        $ordinal = GameUser::count(['game_id' => $this->id]);
        $player = GameUser::create(['game_id' => $this->id, 'user_id' => $id, 'ordinal' => $ordinal]);
        if ($ordinal == 0) {
            $this->current_game_user_id = $player->id;
            $this->update();
        }
    }

    public function nextPlayer()
    {
        $count = GameUser::count(['game_id' => $this->id]);
        $nextOrdinal = ($this->current_game_user->ordinal + 1) % $count;
        $nextPlayer = GameUser::where(['ordinal' => $nextOrdinal])->fetch();
        $this->current_game_user_id = $nextPlayer->id;
        if ($nextOrdinal == 0) {
            $this->round++;
        }
        $this->update();
    }

    public function delete()
    {
        $this->current_game_user_id = null;
        $this->update();
        GameUser::where(['game_id' => $this->id])->deleteEach();
        return parent::delete();
    }

    public function isStarted()
    {
        return $this->current_game_user_id;
    }
}
