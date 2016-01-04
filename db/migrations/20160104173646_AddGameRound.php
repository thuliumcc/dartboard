<?php

class AddGameRound extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE games ADD round INTEGER;");

    }
}
