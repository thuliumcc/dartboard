<?php
namespace Application\Model;

use Ouzo\Model;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Functions;

/**
 * @property int id
 * @property int game_id
 * @property int user_id
 * @property int score
 * @property int ordinal
 * @property User user
 * @property Game game
 */
class GameUser extends Model
{
    const POSSIBLE_SHOTS = 3;

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['id', 'game_id', 'user_id', 'score' => 0, 'ordinal'],
            'belongsTo' => [
                'user' => ['class' => 'User', 'foreignKey' => 'user_id', 'referencedColumn' => 'id'],
                'game' => ['class' => 'Game', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ]);
    }

    /**
     * @param int $field
     * @return int
     */
    public function getScore($field)
    {
        $sum = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->id, 'field' => $field])->fetch();
        return min($sum[0], self::POSSIBLE_SHOTS);
    }

    /**
     * @inheritdoc
     */
    public function isWinner()
    {
        return $this->game->getEngine()->isWinner();
    }

    public function isShittyRound()
    {
        return $this->game->getEngine()->isShittyRound();
    }

    /**
     * @return int
     */
    public function getLeftShoots()
    {
        return self::POSSIBLE_SHOTS - Hit::count(['game_user_id' => $this->id, 'round' => $this->game->round]);
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->game->current_game_user_id == $this->id;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        Hit::where(['game_user_id' => $this->id])->deleteAll();
        return parent::delete();
    }

    public function getClosedByRoundForCricket()
    {
        $hits = Hit::join('game_user->user')->where(['game_user_id' => $this->id])->fetchAll();
        $byRound = Arrays::groupBy($hits, Functions::extract()->round);
        $closedInRound = [];
        $closed = [15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 25 => 0];
        foreach ($byRound as $round => $hits) {
            $closedInCurrentRound = 0;
            foreach ($hits as $hit) {
                if (isset($closed[$hit->field])) {
                    $closedField = min($hit->multiplier, 3 - $closed[$hit->field]);
                    $closedInCurrentRound += $closedField;
                    $closed[$hit->field] += $closedField;
                }
            }
            $closedInRound[$round] = $closedInCurrentRound;
        }
        return $closedInRound;
    }

}
