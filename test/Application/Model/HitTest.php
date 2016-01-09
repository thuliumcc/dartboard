<?php
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\Assert;
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
        /** @var Game $game */
        $game = Game::create();
        $game->addPlayer($user->getId());
        /** @var GameUser $gameUser */
        $gameUser = Arrays::first($game->game_users);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId(), 'round' => 1]);

        //when
        $hit = Hit::createFor($field, $gameUser);

        //then
        $this->assertEquals($expectedField, $hit->field);
        $this->assertEquals($expectedMultiplier, $hit->multiplier);
        $this->assertEquals(1, $hit->round);
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

    /**
     * @test
     */
    public function shouldGetHitsOnlyForPlayersInCurrentGame()
    {
        //given
        $user = User::create(['login' => 'test', 'password' => 'a']);
        $game1 = Game::create();
        $game1->addPlayer($user->getId());
        /** @var GameUser $gameUser1 */
        $gameUser1 = Arrays::first($game1->game_users);

        $game2 = Game::create();
        $game2->addPlayer($user->getId());
        /** @var GameUser $gameUser2 */
        $gameUser2 = Arrays::first($game2->game_users);

        Hit::createFor('4d', $gameUser1);
        Hit::createFor('15t', $gameUser2);

        //when
        $hits = Hit::findForGame($game1);

        //then
        Assert::thatArray($hits)->onProperty('field')->containsExactly('4');
    }
}
