<?php
namespace Application\Model\Engines;

use Application\Model\Game;
use Ouzo\View;

class Game101 implements GameEngine
{
    /**
     * @var Game
     */
    private $game;

    private static $sortedShots = [
        '20t' => 60, '19t' => 57, '18t' => 54, '17t' => 51, '25d' => 50, '16t' => 48, '14t' => 42, '20d' => 40,
        '13t' => 39, '19d' => 38, '18d' => 36, '12t' => 36, '17d' => 34, '11t' => 33, '16d' => 32, '10t' => 30,
        '14d' => 28, '9t' => 27, '13d' => 26, '25s' => 25, '12d' => 24, '8t' => 24, '11d' => 22, '7t' => 21,
        '20s' => 20, '10d' => 20, '19s' => 19, '18s' => 18, '9d' => 18, '6t' => 18, '17s' => 17, '16s' => 16,
        '8d' => 16, '5t' => 15, '14s' => 14, '7d' => 14, '13s' => 13, '12s' => 12, '6d' => 12, '4t' => 12,
        '11s' => 11, '10s' => 10, '5d' => 10, '9s' => 9, '3t' => 9, '8s' => 8, '4d' => 8, '7s' => 7, '6s' => 6,
        '3d' => 6, '2t' => 6, '5s' => 5, '4s' => 4, '2d' => 4, '3s' => 3, '1t' => 3, '2s' => 2, '1d' => 2, '1s' => 1
    ];

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function renderView()
    {
        $view = new View('Engines/101');
        $view->game = $this->game;
        $view->bestShots = $this->getPlayersBestShots();
        return $view->render();
    }

    public function getPlayersBestShots()
    {
        $shots = [];
        $gameUsers = $this->game->game_users;
        foreach ($gameUsers as $gameUser) {
            $diff = 101 - $gameUser->score;
            foreach (self::$sortedShots as $key => $value) {
                $mod = $diff % $value;
                if ($mod != $diff) {
                    $shots[$gameUser->getId()][] = $key;
                }
                $diff = $mod;
            }
        }
        return $shots;
    }

    public function isWinner()
    {
        return $this->game->current_game_user->score == 101;
    }

    public function isScored($field, $multiplier)
    {
        $newScore = $this->calculateNewScore($field, $multiplier);
        return $newScore <= 101;
    }

    public function updateScore($field, $multiplier)
    {
        $newScore = $this->calculateNewScore($field, $multiplier);
        $this->game->current_game_user->updateAttributes(['score' => $newScore]);
    }

    private function calculateNewScore($field, $multiplier)
    {
        $currentScore = $this->game->current_game_user->score;
        $newScore = $currentScore + ($field * $multiplier);
        return $newScore;
    }
}
