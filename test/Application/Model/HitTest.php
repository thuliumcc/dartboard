<?php
use Application\Model\Game;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;
use Ouzo\Utilities\Arrays;

class HitTest extends DbTransactionalTestCase
{
    /**
     * @test
     * @dataProvider fields
     * @param $field
     * @param $expectedField
     * @param $expectedMultiplier
     */
    public function shouldCreateHitForGameUserForCurrentHit($field, $expectedField, $expectedMultiplier)
    {
        //given
        $user = User::create(['login' => 'test', 'password' => 'a']);
        $game = Game::create();
        $game->addPlayer($user->getId());
        $gameUser = Arrays::first($game->game_users);

        //when
        $hit = Hit::createFor($field, $gameUser->getId());

        //then
        $this->assertEquals($expectedField, $hit->field);
        $this->assertEquals($expectedMultiplier, $hit->multiplier);
    }

    public function fields()
    {
        return [
            ['20s', 20, 1],
            ['15d', 15, 2],
            ['19t', 19, 3],
            ['5s', 5, 1],
            ['1d', 1, 2],
            ['8t', 8, 3]
        ];
    }
}
