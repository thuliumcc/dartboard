<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use Application\Model\Hit;
use Ouzo\Utilities\Arrays;
use Ouzo\View;

class Cricket implements GameEngine
{
    public static $SCORED_FIELDS = [15, 16, 17, 18, 19, 20, 25];

    /**
     * @var Game
     */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @inheritdoc
     */
    public function renderView()
    {
        $view = new View('Engines/cricket');
        $view->game = $this->game;
        return $view->render();
    }

    /**
     * @inheritdoc
     */
    public function isWinner()
    {
        $scoredFieldsHits = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->game->current_game_user_id, 'field' => self::$SCORED_FIELDS])
            ->groupBy('field')
            ->fetchAll();
        $allFieldsHit = sizeof($scoredFieldsHits) == sizeof(self::$SCORED_FIELDS);
        $allFieldsHit3Times = Arrays::all($scoredFieldsHits, function ($fetchedFieldHits) {
            $fieldHitCount = Arrays::first($fetchedFieldHits);
            return $fieldHitCount >= 3;
        });
        return $allFieldsHit && $allFieldsHit3Times;
    }

    /**
     * @inheritdoc
     */
    public function isScored($field, $multiplier)
    {
        $isScoredField = in_array($field, self::$SCORED_FIELDS);
        if ($isScoredField) {
            $sum = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->game->current_game_user_id, 'field' => $field])->fetch();
            $hitCountBeforeCurrentHit = Arrays::first($sum) - $multiplier;
            return $hitCountBeforeCurrentHit < 3;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function updateScore($field, $multiplier)
    {
    }
}
