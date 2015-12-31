<?php

class AddGames extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("CREATE TABLE games(
          id serial PRIMARY KEY
        );");
    }
}
