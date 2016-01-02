<?php


use Application\Model\Game;
use Ouzo\Tests\ControllerTestCase;

class GamesControllerTest extends ControllerTestCase
{
    /**
     * @test
     */
    public function shouldAddScoreForCurrentUser()
    {
        //given
        $game = Game::create([]);

        //when
        $this->post('/hit', ['field' => '15t']);

        //then
        $this->assertRenders('Players/index');
    }

}