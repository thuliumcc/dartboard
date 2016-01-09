<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use Application\Model\Hit;
use Ouzo\Utilities\Arrays;
use Ouzo\View;

class Cricket implements GameEngine
{
    /**
     * @var Game
     */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function renderView()
    {
        $view = new View('Engines/cricket');
        $view->game = $this->game;
        return $view->render();
    }

    public function isWinner()
    {
        $scoredFieldsHits = Hit::select('sum(multiplier)')->where(['game_user_id' => $this->game->current_game_user_id, 'field' => Hit::$SCORED_FIELDS])
            ->groupBy('field')
            ->fetchAll();
        $allFieldsHit = sizeof($scoredFieldsHits) == sizeof(Hit::$SCORED_FIELDS);
        $allFieldsHit3Times = Arrays::all($scoredFieldsHits, function ($fetchedFieldHits) {
            $fieldHitCount = Arrays::first($fetchedFieldHits);
            return $fieldHitCount >= 3;
        });
        return $allFieldsHit && $allFieldsHit3Times;
    }
}
