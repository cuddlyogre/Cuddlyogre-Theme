<?php

function isLocal()
{
    return ($_SERVER['SERVER_ADDR'] === '127.0.0.1');
}

function echoLocal()
{
    if (isLocal())
    {
        echo 'LOCAL - ';
    }
}
