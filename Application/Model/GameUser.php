<?php
namespace Application\Model;

use Ouzo\Model;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Functions;

/**
 * @property int game_id
 * @property int user_id
 * @property int score
 * @property int ordinal
 * @property int current_game_user_id
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
            'fields' => ['game_id', 'user_id', 'score' => 0, 'ordinal'],
            'belongsTo' => [
                'user' => ['class' => 'User', 'foreignKey' => 'user_id', 'referencedColumn' => 'id'],
                'game' => ['class' => 'Game', 'foreignKey' => 'game_id', 'referencedColumn' => 'id']
            ]
        ]);
    }

    public function getScore($field)
    {
        $sum = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->id, 'field' => $field])->fetch();
        return min($sum[0], 3);
    }

    public function isWinner()
    {
        return $this->game->getEngine()->isWinner();
    }

    public function getLeftShoots()
    {
        return self::POSSIBLE_SHOTS - Hit::count(['game_user_id' => $this->getId(), 'round' => $this->game->round]);
    }

    public function isCurrent()
    {
        return $this->game->current_game_user_id == $this->getId();
    }

    public function delete()
    {
        Hit::where(['game_user_id' => $this->getId()])->deleteAll();
        return parent::delete();
    }

    public function getClosedByRoundForCricket()
    {
        $hits = Hit::join('userGame->user')->where(['game_user_id' => $this->id])->fetchAll();
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
