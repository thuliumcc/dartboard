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
        Hit::createFor('15s', $gameUser->getId());
        Hit::createFor('15s', $gameUser->getId());

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
        Hit::createFor('15d', $gameUser->getId());
        Hit::createFor('16d', $gameUser->getId());
        Hit::createFor('15t', $gameUser->getId());

        //when
        $score = $gameUser->getScore(15);

        $this->assertEquals(3, $score);
    }
}
