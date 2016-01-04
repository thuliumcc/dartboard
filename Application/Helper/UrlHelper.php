<?php
include_once 'GeneratedUriHelper.php';


function postButton($label, $url) {
    return <<<TAG
<form action="$url" method="post" class="post-button">
    <button type="submit" class="btn btn-primary">$label</button>
</form>
TAG;

}