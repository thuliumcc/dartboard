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