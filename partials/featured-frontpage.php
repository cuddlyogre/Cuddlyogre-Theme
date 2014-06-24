<div id="postfeaturedcontent" class="frontpage">
    <div class="postimage clearfix">
        <?php
        $postsToFeature = 18;
        $loop = new WP_Query(array('posts_per_page' => $postsToFeature, 'post_type' => array('picture', 'video')));
        if ($loop->have_posts()) :
            $i = 0;
            while ($loop->have_posts()) :
                $loop->the_post();
                if ($i == 0) :
                    get_template_part('partials/featured', 'frontpage_primary');
                elseif ($i == 1 || $i == 2):
                    get_template_part('partials/featured', 'frontpage_secondary');
                elseif ($i > 2 && $i <= $loop->query_vars[posts_per_page]):
                    get_template_part('partials/featured', 'frontpage_tertiary');
                endif;
                $i++;
            endwhile;
        endif;
        ?>
    </div>
</div>

<?php

function getLinkAndThumbnail($size = "thumbnail", $featuredDescTitle = ' ', $alt = ' ', $class = '')
{
    $attr = array('alt' => $alt, 'title' => $featuredDescTitle);
    if (has_featured_image())
    {
        ?>
        <a class="<?php echo $class; ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail($size, $attr); ?></a>
        <?php
    }
    else if (has_featured_video())
    {
        ?>
        <a class="<?php echo $class; ?>" href="<?php the_permalink(); ?>"><?php the_youtube_thumbnail($attr); ?></a>
        <?php
    }
    else if (!has_featured_image()) :
        getDefaultThumbnail($size, $class, $attr);
    endif;
}

function getDefaultThumbnail($size = "thumbnail", $class = '', $attr = array())
{
    if ($size == 'featuredprimary' || $size == 'featuredsecondary' || $size == 'featuredtertiary') :
        $path = get_template_directory_uri() . '/images/' . $size . '.jpg';
        ?>
        <a class="<?php echo $class; ?>" href="<?php the_permalink(); ?>"><img src="<?php echo $path; ?>" alt="<?php echo $attr['alt']; ?>" title="<?php echo $attr['title']; ?>"></a>
        <?php
    endif;
}
