<?php

function addCategoriesToPages()
{
    register_taxonomy_for_object_type('category', 'page');
}

add_action('init', 'addCategoriesToPages');

function removeCategoryBoxOnPages()
{
    remove_meta_box('categorydiv', 'page', 'normal');
}

add_action('add_meta_boxes', 'removeCategoryBoxOnPages');

function setup_page_categories()
{
    if (isCurrentTemplateFile('redirect.php'))
    {
        add_meta_box('categorydiv', 'Categories To Follow', 'post_categories_meta_box', 'page', 'side', 'low');
    }
}

add_action('add_meta_boxes', 'setup_page_categories');
