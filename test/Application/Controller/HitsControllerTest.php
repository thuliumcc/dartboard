<?php
use Application\Model\Event;
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\ControllerTestCase;
use Ouzo\Utilities\Arrays;

class HitsControllerTest extends ControllerTestCase
{
    /**
     * @var GameUser
     */
    private $gameUser;

    public function setUp()
    {
        parent::setUp();
        $user = User::create(['login' => 'test', 'password' => 'a']);
        $game = Game::create();
        $game->addPlayer($user->getId());
        $this->gameUser = $gameUser = Arrays::first($game->game_users);
        $game->updateAttributes(['current_game_user_id' => $gameUser->getId()]);
    }

    /**
     * @test
     */
    public function shouldCreateHit()
    {
        //given
        $field = '25d';

        //when
        $this->post('/hit', ['field' => $field]);

        //then
        /** @var Hit $hit */
        $hit = Arrays::first(Hit::all());
        $this->assertEquals($this->gameUser->getId(), $hit->game_user_id);
        $this->assertEquals(25, $hit->field);
        $this->assertEquals(2, $hit->multiplier);
    }

    /**
     * @test
     */
    public function shouldEmitEventAfterHit()
    {
        //given
        $field = '20t';

        //when
        $this->post('/hit', ['field' => $field]);

        //then
        /** @var Event $event */
        $event = Arrays::first(Event::all());
        $this->assertEquals('hit', $event->name);
        $this->assertEquals('{"field":20,"multiplier":3,"scored":true,"winner":false,"shots_left":2}', $event->params);
    }
}
