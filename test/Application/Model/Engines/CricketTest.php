<?php

use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;

class CricketTest extends DbTransactionalTestCase
{
    /**
     * @test
     */
    public function shouldCalculateIsShittyRound()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create(['type' => 'cricket']);
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('1s', $gameUser);
        Hit::createFor('1s', $gameUser);
        Hit::createFor('2t', $gameUser);

        //when
        $isShittyRound = $gameUser->isShittyRound();

        //then
        $this->assertTrue($isShittyRound);
    }

    /**
     * @test
     */
    public function shouldCalculateIsNotShittyRound()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create(['type' => 'cricket']);
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15t', $gameUser);
        Hit::createFor('16t', $gameUser);
        Hit::createFor('17t', $gameUser);

        //when
        $isShittyRound = $gameUser->isShittyRound();

        //then
        $this->assertFalse($isShittyRound);
    }
}
