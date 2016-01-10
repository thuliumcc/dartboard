<?php
namespace Application\Model\Engines;

interface GameEngine
{
    /**
     * @return string
     */
    public function renderView();

    /**
     * @return bool
     */
    public function isWinner();

    /**
     * @param int $field
     * @param int $multiplier
     * @return bool
     */
    public function isScored($field, $multiplier);

    /**
     * @param int $field
     * @param int $multiplier
     * @return void
     */
    public function updateScore($field, $multiplier);
}
