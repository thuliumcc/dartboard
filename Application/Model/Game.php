<?php
namespace Application\Model;

use Application\Model\Engines\GameEngine;
use Application\Model\Engines\GameEngineStrategyMapper;
use Ouzo\Model;
use Ouzo\ValidationException;

/**
 * @property int current_game_user_id
 * @property int round
 * @property int id
 * @property bool finished
 * @property int winner_game_user_id
 * @property string type
 * @property GameUser current_game_user
 * @property GameUser winner_game_user
 * @property GameUser[] game_users
 */
class Game extends Model
{
    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['id', 'current_game_user_id', 'round' => 1, 'finished' => false, 'winner_game_user_id', 'type'],
            'belongsTo' => [
                'current_game_user' => ['class' => 'GameUser', 'foreignKey' => 'current_game_user_id', 'referencedColumn' => 'id'],
                'winner_game_user' => ['class' => 'GameUser', 'foreignKey' => 'winner_game_user_id', 'referencedColumn' => 'id'],
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
        return self::findUnfinishedGame();
    }

    /**
     * @return Game
     */
    public static function findUnfinishedGame()
    {
        return self::where(['finished' => false])->fetch();
    }

    /**
     * @return User[]
     */
    public function possibleUsers()
    {
        $currentGame = self::currentGame();
        return User::where('not exists (select true from game_users where game_id = ? AND user_id = users.id)', $currentGame->id)
            ->fetchAll();
    }

    /**
     * @param int $id
     * @return GameUser
     * @throws ValidationException
     */
    public function addPlayer($id)
    {
        $ordinal = GameUser::count(['game_id' => $this->id]);
        /** @var GameUser $player */
        $player = GameUser::create(['game_id' => $this->id, 'user_id' => $id, 'ordinal' => $ordinal]);
        $player->game = $this;
        if ($ordinal == 0) {
            $this->current_game_user_id = $player->id;
            $this->update();
        }
        return $player;
    }

    /**
     * @return void
     */
    public function nextPlayer()
    {
        $count = GameUser::count(['game_id' => $this->id]);
        $nextOrdinal = ($this->current_game_user->ordinal + 1) % $count;
        /** @var GameUser $nextPlayer */
        $nextPlayer = GameUser::where(['game_id' => $this->id, 'ordinal' => $nextOrdinal])->fetch();
        $this->current_game_user_id = $nextPlayer->id;
        if ($nextOrdinal == 0) {
            $this->round++;
        }
        $this->update();
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->current_game_user_id = null;
        $this->winner_game_user_id = null;
        $this->update();
        GameUser::where(['game_id' => $this->id])->deleteEach();
        return parent::delete();
    }

    /**
     * @return int
     */
    public function isStarted()
    {
        return $this->current_game_user_id;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @return bool
     */
    public function hasPlayers()
    {
        return GameUser::count(['game_id' => $this->id]) > 0;
    }

    /**
     * @return void
     */
    public function endedByCurrentGameUser()
    {
        $this->updateAttributes(['finished' => true, 'winner_game_user_id' => $this->current_game_user_id]);
    }

    /**
     * @return GameEngine
     */
    public function getEngine()
    {
        return GameEngineStrategyMapper::instance($this);
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->updateAttributes(['type' => $type]);
    }
}
