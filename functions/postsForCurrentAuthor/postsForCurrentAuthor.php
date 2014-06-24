<?php

//http://wordpressapi.com/show-only-current-authors-post-in-wordpress-admin-panel/
function posts_for_current_author($query)
{
    global $pagenow;

    if ('edit.php' != $pagenow || !$query->is_admin)
        return $query;

    if (!current_user_can('manage_options'))
    {
        global $user_ID;
        $query->set('author', $user_ID);
    }

    return $query;
}

add_filter('pre_get_posts', 'posts_for_current_author');
