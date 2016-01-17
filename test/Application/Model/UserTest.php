<?php
use Application\Model\Game;
use Application\Model\GameUser;
use Application\Model\Hit;
use Application\Model\User;
use Ouzo\Tests\DbTransactionalTestCase;

class UserTest extends DbTransactionalTestCase
{
    /**
     * @test
     */
    public function shouldReturnNumberOfGames()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game1 = Game::create();
        $game1->addPlayer($user->getId());
        $game2 = Game::create();
        $game2->addPlayer($user->getId());

        //when
        $countGames = $user->countGames();

        //then
        $this->assertEquals(2, $countGames);
    }

    /**
     * @test
     */
    public function shouldReturnWNumberOfWonGames()
    {
        //given
        $user1 = User::create(['login' => 'A']);
        $user2 = User::create(['login' => 'B']);

        $game1 = Game::create();
        $gameUser1 = GameUser::create(['game_id' => $game1->id, 'user_id' => $user1->id]);
        $game1->updateAttributes(['winner_game_user_id' => $gameUser1->id]);

        $game2 = Game::create();
        $gameUser2 = GameUser::create(['game_id' => $game2->id, 'user_id' => $user1->id]);
        $game2->updateAttributes(['winner_game_user_id' => $gameUser2->id]);

        $game3 = Game::create();
        $gameUser3 = GameUser::create(['game_id' => $game3->id, 'user_id' => $user2->id]);
        $game3->updateAttributes(['winner_game_user_id' => $gameUser3->id]);

        $game4 = Game::create();
        $game4->addPlayer($user1->id);
        $game4->addPlayer($user2->id);

        //when
        $countWonGames = $user1->countWonGames();

        //then
        $this->assertEquals(2, $countWonGames);
    }

    /**
     * @test
     */
    public function shouldReturnMaxWonStreak()
    {
        //given
        $user1 = User::create(['login' => 'A']);
        $user2 = User::create(['login' => 'B']);

        $game1 = Game::create();
        $gameUser1 = GameUser::create(['game_id' => $game1->id, 'user_id' => $user1->id]);
        $game1->updateAttributes(['winner_game_user_id' => $gameUser1->id, 'finished' => true]);

        $game2 = Game::create();
        $gameUser2 = GameUser::create(['game_id' => $game2->id, 'user_id' => $user1->id]);
        $game2->updateAttributes(['winner_game_user_id' => $gameUser2->id, 'finished' => true]);

        $game3 = Game::create();
        $gameUser3 = GameUser::create(['game_id' => $game3->id, 'user_id' => $user2->id]);
        $game3->updateAttributes(['winner_game_user_id' => $gameUser3->id, 'finished' => true]);

        $game4 = Game::create();
        $game4->addPlayer($user1->id);
        $game4->addPlayer($user2->id);

        //when
        $maxWonStreak = $user1->maxWonStreak();

        //then
        $this->assertEquals(2, $maxWonStreak);
    }

    /**
     * @test
     */
    public function shouldReturnMostFrequentlyHitField()
    {
        //given
        $user = User::create(['login' => 'A']);
        $game = Game::create(['type' => 'cricket']);
        $gameUser = $game->addPlayer($user->getId());
        Hit::createFor('15t', $gameUser);
        Hit::createFor('16s', $gameUser);
        Hit::createFor('16s', $gameUser);

        //when
        $field = $user->mostFrequentlyHitField();

        //then
        $this->assertEquals('16', $field);
    }
}
