<?php

//http://www.wprecipes.com/how-to-redirect-users-to-a-random-post
// set arguments for get_posts()
$args = array(
    'numberposts' => 1,
    'orderby' => 'rand'
);

// get a random post from the database
$my_random_post = get_posts($args);

// process the database request through a foreach loop
foreach ($my_random_post as $post)
{
    // redirect the user to the random post
    wp_redirect(get_permalink($post->ID));
    exit;
}
