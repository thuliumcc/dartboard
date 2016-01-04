<?php

class CreateTableHits extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("CREATE TABLE hits(
          id serial PRIMARY KEY,
          game_user_id INTEGER REFERENCES game_users,
          field INTEGER,
          multiplier INTEGER
        )");
    }
}
