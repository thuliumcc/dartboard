<?php
namespace Application\Model;

use Application\Model\Engines\GameEngine;
use Application\Model\Engines\GameEngineStrategyMapper;
use Ouzo\Model;

/**
 * @property int current_game_user_id
 * @property int round
 * @property int id
 * @property bool finished
 * @property int winner_game_user_id
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
            'fields' => ['id', 'current_game_user_id', 'round' => 1, 'finished' => false, 'winner_game_user_id'],
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

    public function possibleUsers()
    {
        $currentGame = self::currentGame();
        return User::where('not exists (select true from game_users where game_id = ? AND user_id = users.id)', $currentGame->getId())
            ->fetchAll();
    }

    public function addPlayer($id)
    {
        $ordinal = GameUser::count(['game_id' => $this->id]);
        $player = GameUser::create(['game_id' => $this->id, 'user_id' => $id, 'ordinal' => $ordinal]);
        $player->game = $this;
        if ($ordinal == 0) {
            $this->current_game_user_id = $player->id;
            $this->update();
        }
        return $player;
    }

    public function nextPlayer()
    {
        $count = GameUser::count(['game_id' => $this->id]);
        $nextOrdinal = ($this->current_game_user->ordinal + 1) % $count;
        $nextPlayer = GameUser::where(['game_id' => $this->id, 'ordinal' => $nextOrdinal])->fetch();
        $this->current_game_user_id = $nextPlayer->getId();
        if ($nextOrdinal == 0) {
            $this->round++;
        }
        $this->update();
    }

    public function delete()
    {
        $this->current_game_user_id = null;
        $this->winner_game_user_id = null;
        $this->update();
        GameUser::where(['game_id' => $this->getId()])->deleteEach();
        return parent::delete();
    }

    public function isStarted()
    {
        return $this->current_game_user_id;
    }

    public function isFinished()
    {
        return $this->finished;
    }

    public function hasPlayers()
    {
        return GameUser::count(['game_id' => $this->id]) > 0;
    }

    public function endedByCurrentGameUser()
    {
        $this->updateAttributes(['finished' => true, 'winner_game_user_id' => $this->current_game_user_id]);
    }

    /**
     * @return GameEngine
     */
    public function getEngine()
    {
        return GameEngineStrategyMapper::instance('101', $this);
    }
}
