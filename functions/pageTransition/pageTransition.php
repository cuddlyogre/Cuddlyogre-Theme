<?php

function enqueuePageTransition()
{
    wp_enqueue_script('pageTransition', get_template_directory_uri() . '/functions/pageTransition/pageTransition.js', array('jquery'), '', 'true');
}

//add_action('wp_enqueue_scripts', 'enqueuePageTransition');
