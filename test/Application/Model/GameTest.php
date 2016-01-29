<?php
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\User;
use Ouzo\Tests\Assert;
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

    /**
     * @test
     */
    public function shouldSetGameEndedByCurrentUser()
    {
        //given
        /** @var User $user1 */
        $user1 = User::create(['login' => 'A']);
        /** @var User $user2 */
        $user2 = User::create(['login' => 'B']);
        /** @var Game $game */
        $game = Game::create();
        $game->addPlayer($user1->getId());
        $game->addPlayer($user2->getId());

        $currentGame = Game::currentGame();
        $currentGame->nextPlayer();

        //when
        $currentGame->endedByCurrentGameUser();

        //then
        $game->reload();
        $this->assertTrue($game->finished);
        $this->assertEquals($user2->getId(), $game->winner_game_user->user_id);
    }

    /**
     * @test
     */
    public function shouldGetCorrectNextPlayerWhenPlayerIsInMultipleGames()
    {
        //given
        /** @var User $user1 */
        $user1 = User::create(['login' => 'A']);
        /** @var User $user2 */
        $user2 = User::create(['login' => 'B']);
        $game1 = Game::create();
        $game1->addPlayer($user1->getId());
        $game1->addPlayer($user2->getId());
        $game1->updateAttributes(['finished' => true]);

        $game2 = Game::create();
        $gameUser1 = GameUser::create(['game_id' => $game2->id, 'user_id' => $user1->getId(), 'ordinal' => 0]);
        $gameUser2 = GameUser::create(['game_id' => $game2->id, 'user_id' => $user2->getId(), 'ordinal' => 1]);
        $game2->updateAttributes(['current_game_user_id' => $gameUser1->getId()]);

        //when
        $game2->nextPlayer();

        //then
        $game2->reload();
        $this->assertEquals($gameUser2->getId(), $game2->current_game_user_id);
    }

    /**
     * @test
     */
    public function shouldRestartLastGame()
    {
        //given
        /** @var User $user1 */
        $user1 = User::create(['login' => 'A']);
        /** @var User $user2 */
        $user2 = User::create(['login' => 'B']);
        $game1 = Game::create();
        $game1->addPlayer($user1->getId());
        $game1->addPlayer($user2->getId());
        $game1->updateAttributes(['finished' => true]);

        //when
        Game::restart();

        //then
        $game = Game::findUnfinishedGame();
        $this->assertFalse($game->isFinished());
        $this->assertTrue($game->isStarted());
        Assert::thatArray(GameUser::where(['game_id' => $game->getId()])->fetchAll())
            ->hasSize(2)
            ->onProperty('user_id')
            ->containsExactly($user1->getId(), $user2->getId());
    }
}
