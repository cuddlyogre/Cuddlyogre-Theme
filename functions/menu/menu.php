<?php

function register_my_menus()
{
    register_nav_menu('main-menu-location', 'Main Menu');
}

add_action('init', 'register_my_menus');

function createmenu($theme_location, $menu_container = 'menu-container', $menu_class = 'menu')
{
    $defaults = array(
        'theme_location' => $theme_location,
        'menu' => '',
        'container' => 'div',
        'container_class' => $menu_container,
        'container_id' => '',
        'menu_class' => $menu_class,
        'menu_id' => '',
        'echo' => true,
        'fallback_cb' => 'wp_page_menu',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth' => 0,
        'walker' => ''
    );

    wp_nav_menu($defaults);
}

function menuStyleSetup()
{
    wp_enqueue_script('menuSetup', get_template_directory_uri() . '/functions/menu/menu.js', array('jquery'), '', 'true');
    wp_enqueue_style('menuStyle', get_template_directory_uri() . '/functions/menu/menu.css');
}

add_action('wp_enqueue_scripts', 'menuStyleSetup', 1);
