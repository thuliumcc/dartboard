<?php
use Ouzo\Utilities\Arrays;

include_once 'GeneratedUriHelper.php';

function postButton($label, $url, $options = [])
{
    $class = Arrays::getValue($options, 'class', '');
    return <<<TAG
<form action="$url" method="post" class="post-button $class">
    <button type="submit" class="btn btn-primary">$label</button>
</form>
TAG;
}
