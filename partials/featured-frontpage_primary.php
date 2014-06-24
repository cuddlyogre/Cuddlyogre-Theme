<div id="featuredprimary" class="fpfeaturedcontent">
    <?php getLinkAndThumbnail('featuredprimary', get_the_title(), $post->post_excerpt, "featuredimage"); ?>
    <div class="frontpagedescription">
        <a class="frontpagedescriptionlink" href="<?php the_permalink(); ?>" title="<?php echo $post->post_excerpt; ?>">
            <h1><?php the_title(); ?></h1>
            <?php if ($post->post_excerpt): ?>
                <p class="primaryexcerpt"><?php echo $post->post_excerpt; ?></p>
            <?php endif; ?>
        </a>
    </div>
</div>