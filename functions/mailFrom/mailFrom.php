<?php

function new_mail_from($old)
{
    return 'cuddlyogre@cuddlyogre.com';
}

add_filter('wp_mail_from', 'new_mail_from');

function new_mail_from_name($old)
{
    return 'cuddlyogre.com Wordpress';
}

add_filter('wp_mail_from_name', 'new_mail_from_name');
