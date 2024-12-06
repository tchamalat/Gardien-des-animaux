<?php

if( isset($_SESSION['userid']))
{
    unset($SESSION['userid']);
}

$info->logged_in = false;
echo json_encode($info);
