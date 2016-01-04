<?php

class AddEvents extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("CREATE UNLOGGED TABLE events(
          id serial PRIMARY KEY,
          name TEXT,
          params TEXT,
          created_at TIMESTAMP
        );");
    }
}
