<?php

function home_page_menu_args($args)
{
    $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'home_page_menu_args');

//http://www.deluxeblogtips.com/2013/07/get-url-of-php-file-in-wordpress.html
function current_file_url($file = __FILE__)
{

    // Get correct URL and path to wp-content
    $content_url = untrailingslashit(dirname(dirname(get_stylesheet_directory_uri())));
    $content_dir = untrailingslashit(dirname(dirname(get_stylesheet_directory())));
    // Fix path on Windows
    $file = str_replace('\\', '/', $file);
    $content_dir = str_replace('\\', '/', $content_dir);
    return $url = str_replace($content_dir, $content_url, $file);
}

function current_dir_url($file = __FILE__)
{
    return dirname(current_file_url($file));
}
