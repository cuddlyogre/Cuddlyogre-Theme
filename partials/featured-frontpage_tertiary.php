<div class="featuredtertiary fpfeaturedcontent">
    <?php getLinkAndThumbnail('featuredtertiary', get_the_title(), $post->post_excerpt, "featuredimage"); ?>
    <div class="frontpagedescription tertiaryfrontpagedescription">
        <a class="frontpagedescriptionlink tertiaryfrontpagedescriptionlink" href="<?php the_permalink(); ?>" title="<?php echo $post->post_excerpt; ?>">
            <h3><?php the_title(); ?></h3>
        </a>
    </div> 
</div>