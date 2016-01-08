<?php

class AddFinishedAndWinnerToGames extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE games ADD finished BOOLEAN DEFAULT false;");
        $this->execute("ALTER TABLE games ADD winner_game_user_id INTEGER REFERENCES game_users;");
    }
}
