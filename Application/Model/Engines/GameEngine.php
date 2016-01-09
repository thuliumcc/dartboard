<?php
namespace Application\Model\Engines;

interface GameEngine
{
    public function renderView();

    public function isWinner();
}
