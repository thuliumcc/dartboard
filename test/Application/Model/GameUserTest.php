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

    /**
     * @test
     */
    public function shouldReturnIsWinner()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15t', $gameUser);
        Hit::createFor('16t', $gameUser);
        Hit::createFor('17t', $gameUser);
        Hit::createFor('18t', $gameUser);
        Hit::createFor('19t', $gameUser);
        Hit::createFor('20t', $gameUser);
        Hit::createFor('25d', $gameUser);
        Hit::createFor('25s', $gameUser);

        //when
        $isWinner = $gameUser->isWinner();

        //then
        $this->assertTrue($isWinner);
    }

    /**
     * @test
     */
    public function shouldReturnIsNotWinner()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
        Hit::createFor('15t', $gameUser);
        Hit::createFor('16t', $gameUser);

        //when
        $isWinner = $gameUser->isWinner();

        //then
        $this->assertFalse($isWinner);
    }

    /**
     * @test
     * @dataProvider hits
     */
    public function shouldReturnIsScoredIfFieldIsInRange($hit, $isScored)
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $gameUser = GameUser::create(['game_id' => $game->id, 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);

        //when
        $hit = Hit::createFor($hit, $gameUser);

        //then
        $this->assertEquals($isScored, $hit->isScored());
    }

    public function hits()
    {
        return [
            ['1s', false],
            ['2s', false],
            ['3s', false],
            ['4s', false],
            ['5s', false],
            ['6s', false],
            ['7s', false],
            ['8s', false],
            ['9s', false],
            ['10s', false],
            ['11s', false],
            ['12s', false],
            ['13s', false],
            ['14s', false],
            ['15s', true],
            ['16s', true],
            ['17s', true],
            ['18s', true],
            ['19s', true],
            ['20s', true],
            ['25s', true],
        ];
    }

}
