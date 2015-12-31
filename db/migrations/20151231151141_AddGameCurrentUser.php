<?php

class AddGameCurrentUser extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE game_users ADD current_game_user_id INTEGER REFERENCES game_users;");
    }
}
