<?php


use Application\Model\Game;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;

class GameTest extends DbTransactionalTestCase
{
    /**
     * @test
     */
    public function shouldMoveToNextPlayer()
    {
        //given
        $user1 = User::create(['login' => 'A']);
        $user2 = User::create(['login' => 'B']);
        $user3 = User::create(['login' => 'C']);

        $game = Game::create();
        $game->addPlayer($user1->id);
        $game->addPlayer($user2->id);
        $game->addPlayer($user3->id);

        //when
        $this->assertEquals($user1, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user2, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user3, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user1, $game->reload()->current_game_user->user);
    }
}
