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

function gameGamesPath()
{
    return url("/game");
}

function restartGameGamesPath()
{
    return url("/restart_game");
}

function cancelGameGamesPath()
{
    return url("/cancel_game");
}

function addPlayerGamesPath($id)
{
    checkParameter($id);
    return url("/games/add_player/$id");
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