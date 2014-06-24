<?php

if (have_posts()) :
    while (have_posts()) :
        the_post();
        if (get_the_content()):
            get_sidebar('left');
            get_template_part('partials/post', 'single');
        endif;
    endwhile;
endif;
