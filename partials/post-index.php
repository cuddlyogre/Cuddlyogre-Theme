<div class="postcontent">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            displayHomePost($post);
        endwhile;

        if (canPaginate()) :
            pagination("blogpagination");
        elseif (is_single() && canCatPaginate()):
            catpagination("blogpagination");
        endif;
    else:
        get_template_part('partials/post', 'none');
    endif;
    ?>
</div>

<?php

function displayHomePost($post)
{
    $classes = array('blogpost');
    ?>
    <div <?php post_class($classes); ?>>
        <?php showTitle(get_the_title(), '', true, true, 'h2'); ?>

        <div class="blogtext">
            <?php
            if (has_featured_image())
            {
                $attr = array('alt' => $post->post_excerpt, 'title' => get_the_title());
                ?>
                <a class="postthumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail', $attr); ?></a>
                <?php
            }
            else if (has_featured_video())
            {
                $attr = array('alt' => $post->post_excerpt, 'title' => get_the_title());
                ?>
                <a class="postthumbnail" href="<?php the_permalink(); ?>"><?php the_youtube_thumbnail($attr); ?></a>
                <?php
            }
            ?>
            <?php
            if (is_single())
            {
                the_content();
            }
            else if ($pos = strpos($post->post_content, '<!--more-->'))
            {
                the_content("Read more...");
            }
            else
            {
                the_content();
                ?>
                <a href="<?php the_permalink(); ?>" class="more-link" title='<?php get_the_title(); ?>'>Read more...</a>
                <?php
            }

            if (current_user_can('edit_posts'))
            {
                edit_post_link('Edit Post');
            }
            ?>
        </div>
    </div>
    <?php
}
