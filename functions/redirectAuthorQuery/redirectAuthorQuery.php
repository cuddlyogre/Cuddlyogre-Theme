<?php

function redirect_author_query()
{
    global $wp_query;

    if ($wp_query->query_vars['author_name'] == 'bluescreenguru')
    {
        wp_redirect(get_author_posts_url('', 'cuddlyogre'), 302);
        exit;
    }
}

add_filter('template_redirect', 'redirect_author_query');
