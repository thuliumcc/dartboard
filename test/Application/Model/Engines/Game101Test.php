<?php
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;

class Game101Test extends DbTransactionalTestCase
{
    /**
     * @var GameUser
     */
    private $gameUser;

    public function setUp()
    {
        parent::setUp();
        /** @var User $user */
        $user = User::create(['login' => 'A']);
        $game = Game::create();
        $this->gameUser = $gameUser = GameUser::create(['game_id' => $game->getId(), 'user_id' => $user->getId()]);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
    }

    /**
     * @test
     */
    public function shouldAddScore()
    {
        //given
        $hit = Hit::createFor('13d', $this->gameUser);

        //when
        $isScored = $hit->isScored();

        //then
        $this->gameUser->reload();
        $this->assertTrue($isScored);
        $this->assertEquals(26, $this->gameUser->score);
    }

    /**
     * @test
     */
    public function shouldNotScoreWhenHitMoreThanNeeded()
    {
        //given
        $this->gameUser->updateAttributes(['score' => 99]);
        $hit = Hit::createFor('13d', $this->gameUser);

        //when
        $isScored = $hit->isScored();

        //then
        $this->gameUser->reload();
        $this->assertFalse($isScored);
        $this->assertEquals(99, $this->gameUser->score);
    }

    /**
     * @test
     */
    public function shouldSetScoreWhenIsExactly101()
    {
        //given
        $this->gameUser->updateAttributes(['score' => 99]);
        $hit = Hit::createFor('2s', $this->gameUser);

        //when
        $isScored = $hit->isScored();

        //then
        $this->gameUser->reload();
        $this->assertTrue($isScored);
        $this->assertEquals(101, $this->gameUser->score);
    }
}
