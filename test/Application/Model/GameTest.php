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
        /** @var User $user1 */
        $user1 = User::create(['login' => 'A']);
        /** @var User $user2 */
        $user2 = User::create(['login' => 'B']);
        /** @var User $user3 */
        $user3 = User::create(['login' => 'C']);

        /** @var Game $game */
        $game = Game::create();
        $game->addPlayer($user1->getId());
        $game->addPlayer($user2->getId());
        $game->addPlayer($user3->getId());

        //when
        $this->assertEquals($user1, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user2, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user3, $game->reload()->current_game_user->user);

        $game->nextPlayer();
        $this->assertEquals($user1, $game->reload()->current_game_user->user);
    }

    /**
     * @test
     */
    public function shouldReturnFalseIfGameHasNotPlayers()
    {
        //given
        $game = Game::create();

        //when
        $hasPlayers = $game->hasPlayers();

        //then
        $this->assertFalse($hasPlayers);
    }
}
