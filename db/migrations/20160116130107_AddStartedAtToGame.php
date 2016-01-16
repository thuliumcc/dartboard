<?php

class AddStartedAtToGame extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE games ADD started_at TIMESTAMP;");
    }
}
