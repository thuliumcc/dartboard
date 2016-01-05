<?php
use Ouzo\Utilities\Arrays;

include_once 'GeneratedUriHelper.php';

function postButton($label, $url, $options = [])
{
    $class = Arrays::getValue($options, 'class', '');
    $id = Arrays::getValue($options, 'id', '');
    $idHtml = $id ? " id=\"$id\" " : "";
    return <<<TAG
<form action="$url" $idHtml method="post" class="post-button $class">
    <button type="submit" class="btn btn-primary">$label</button>
</form>
TAG;
}
