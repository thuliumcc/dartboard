<?php

class AddTypeToGames extends Ruckusing_Migration_Base
{
    public function up()
    {
        $this->execute("ALTER TABLE games ADD type TEXT;");
    }
}
