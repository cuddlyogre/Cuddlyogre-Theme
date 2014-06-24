<?php

if (have_posts()) :
    while (have_posts()) :
        the_post();
        get_sidebar('left');
        get_template_part('partials/post', 'single');
    endwhile;
endif;
