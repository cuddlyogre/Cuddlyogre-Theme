<div class="postcontent">
    <div <?php post_class(''); ?>>
        <h2 class="title pagetitle">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <span class="postinfo"><?php echo " - " . get_the_date(); ?></span>
        </h2>
        <?php
        global $more;
        $more = 0;
        the_content("Read more...");

        if (current_user_can('edit_posts'))
        {
            edit_post_link('Edit Post');
        }
        ?>
    </div>
</div>