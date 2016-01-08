<?php
function checkParameter($parameter)
{
    if (!isset($parameter)) {
        throw new \InvalidArgumentException("Missing parameters");
    }
}

function indexGamesPath()
{
    return url("/");
}

function newGameGamesPath()
{
    return url("/new_game");
}

function endGameGamesPath()
{
    return url("/end_game");
}

function gameGamesPath()
{
    return url("/game");
}

function testGamesPath()
{
    return url("/test");
}

function gameContentGamesPath()
{
    return url("/game_content");
}

function restartGamesPath()
{
    return url("/games/restart");
}

function cancelGamesPath()
{
    return url("/games/cancel");
}

function addPlayerGamesPath($id)
{
    checkParameter($id);
    return url("/games/add_player/$id");
}

function nextPlayerGamesPath()
{
    return url("/games/next_player");
}

function pollEventsPath()
{
    return url("/long_poll");
}

function indexHitsPath()
{
    return url("/hit");
}

function playersPath()
{
    return url("/players");
}

function freshPlayerPath()
{
    return url("/players/fresh");
}

function editPlayerPath($id)
{
    checkParameter($id);
    return url("/players/$id/edit");
}

function playerPath($id)
{
    checkParameter($id);
    return url("/players/$id");
}

function allGeneratedUriNames()
{
    return array('indexGamesPath', 'newGameGamesPath', 'endGameGamesPath', 'gameGamesPath', 'testGamesPath', 'gameContentGamesPath', 'restartGamesPath', 'cancelGamesPath', 'addPlayerGamesPath', 'nextPlayerGamesPath', 'pollEventsPath', 'indexHitsPath', 'playersPath', 'freshPlayerPath', 'editPlayerPath', 'playerPath', 'playersPath', 'playerPath', 'playerPath', 'playerPath');
}