<?php

//http://scratch99.com/wordpress/development/how-to-change-post-template-via-url-parameter/
function sjc_add_query_vars($vars)
{
    return array('template') + $vars;
}

add_filter('query_vars', 'sjc_add_query_vars');

//http://www.cuddlyogre.com/2014/05/15/ajax-test/?template=basic
function sjc_template($template)
{
    global $wp;
    if ($wp->query_vars['template'] == 'basic')
    {
        return dirname(__FILE__) . '/single-basic.php';
    }
    else
    {
        return $template;
    }
}

add_filter('single_template', 'sjc_template');
