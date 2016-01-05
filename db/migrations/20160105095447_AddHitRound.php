<?php

class AddHitRound extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE hits ADD round INTEGER;");
    }
}
