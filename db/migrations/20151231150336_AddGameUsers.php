<?php

class AddGameUsers extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("CREATE TABLE game_users(
          id serial PRIMARY KEY,
          game_id INTEGER REFERENCES games,
          user_id INTEGER REFERENCES users,
          score INTEGER,
          ordinal INTEGER
        );");
    }
}
