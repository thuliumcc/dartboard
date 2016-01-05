<?php
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;

class GameUserTest extends DbTransactionalTestCase
{
    /**
     * @test
     */
    public function shouldReturnScoreForField()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->getId(), 'user_id' => $user->getId()]);
        Hit::createFor('15s', $gameUser);
        Hit::createFor('15s', $gameUser);

        //when
        $score = $gameUser->getScore(15);

        $this->assertEquals(2, $score);
    }

    /**
     * @test
     */
    public function shouldReturnMaxScoreForField()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        Hit::createFor('15d', $gameUser);
        Hit::createFor('16d', $gameUser);
        Hit::createFor('15t', $gameUser);

        //when
        $score = $gameUser->getScore(15);

        $this->assertEquals(3, $score);
    }

    /**
     * @test
     */
    public function shouldNoLeftShoots()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15d', $gameUser);
        Hit::createFor('16d', $gameUser);
        Hit::createFor('15t', $gameUser);

        //when
        $left = $gameUser->getLeftShoots();

        //then
        $this->assertNull($left);
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfFieldIsScored()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);

        //when
        $hit = Hit::createFor('15t', $gameUser);

        //then
        $this->assertTrue($hit->isScored());
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfHitFieldIsScored()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15d', $gameUser);

        //when
        $hit = Hit::createFor('15t', $gameUser);

        //then
        $this->assertTrue($hit->isScored());
    }

    /**
     * @test
     */
    public function shouldReturnFalseIfFieldIsNotScored()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15t', $gameUser);

        //when
        $hit = Hit::createFor('15s', $gameUser);

        //then
        $this->assertFalse($hit->isScored());
    }

}
