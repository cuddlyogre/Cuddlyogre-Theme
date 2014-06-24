<?php

function wordpressCoreCSSSetup()
{
    wp_enqueue_style('wordpressCoreCSSStyle', get_template_directory_uri() . '/functions/wordpressCoreCSS/wordpressCoreCSS.css');
}

add_action('wp_enqueue_scripts', 'wordpressCoreCSSSetup', 1);