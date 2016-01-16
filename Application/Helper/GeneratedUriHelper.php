<?php
function checkParameter($parameter)
{
    if (!isset($parameter)) {
        throw new \InvalidArgumentException("Missing parameters");
    }
}

function indexHomePath()
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

function testGamesPath()
{
    return url("/test");
}

function gameContentGamesPath()
{
    return url("/game_content");
}

function indexGamesPath()
{
    return url("/games");
}

function gameGamesPath()
{
    return url("/games/current");
}

function showGamesPath($id)
{
    checkParameter($id);
    return url("/games/$id");
}

function createGamesPath()
{
    return url("/games");
}

function restartGamesPath()
{
    return url("/games/restart");
}

function cancelGamesPath()
{
    return url("/games/cancel");
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
    return array('indexHomePath', 'newGameGamesPath', 'endGameGamesPath', 'testGamesPath', 'gameContentGamesPath', 'indexGamesPath', 'gameGamesPath', 'showGamesPath', 'createGamesPath', 'restartGamesPath', 'cancelGamesPath', 'nextPlayerGamesPath', 'pollEventsPath', 'indexHitsPath', 'playersPath', 'freshPlayerPath', 'editPlayerPath', 'playerPath', 'playersPath', 'playerPath', 'playerPath', 'playerPath');
}