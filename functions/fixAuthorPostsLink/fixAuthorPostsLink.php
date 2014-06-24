<?php

//without this, when you click on the user name to go to the posts in that post type
//by clicking on the user name or the post count link in the user admin page,
//it goes to all posts, regardless of post type,
//even though it shows the post count for just that poast type

function fix_author_posts_link($query)
{
    global $pagenow;
    
    if ('edit.php' != $pagenow || !$query->is_admin)
        return $query;//get_current_screen doesn't work outside of admin area

    $screen = get_current_screen();
    if ($screen->post_type)
        $query->set('post_type', $screen->post_type);

    return $query;
}

add_filter('pre_get_posts', 'fix_author_posts_link');
