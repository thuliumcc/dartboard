<?php


use Ouzo\Utilities\Arrays;

class FlotHelper
{
    static function toFlotData($game_users, $userDataFunction) {
        $result = [];
        foreach ($game_users as $game_user) {
            $data = array_values(Arrays::mapEntries($userDataFunction($game_user), function ($round, $closed) use ($game_user) {
                return [$round, $closed];
            }));
            $result[] = [
                'label' => $game_user->user->login,
                'data' => $data
            ];
        }
        return $result;
    }
}