<div class="featuredsecondary fpfeaturedcontent">
    <?php getLinkAndThumbnail('featuredsecondary', get_the_title(), $post->post_excerpt, "featuredimage"); ?>
    <div class="frontpagedescription secondaryfrontpagedescription">
        <a class="frontpagedescriptionlink secondaryfrontpagedescriptionlink" href="<?php the_permalink(); ?>" title="<?php echo $post->post_excerpt; ?>">
            <h2><?php the_title(); ?></h2>
        </a>
    </div>
</div>