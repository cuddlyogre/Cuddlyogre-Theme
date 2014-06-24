<div id="postfeaturedcontent" class="marginbottom">
    <div class="postimage clearfix">
        <?php $src = wp_get_attachment_image_src(get_the_ID(), ''); ?>
        <a href="<?php echo $src[0]; ?>"><?php echo remove_width_attribute(wp_get_attachment_image(get_the_ID(), 'single')); ?></a>
    </div>
    <?php if (!empty($post->post_excerpt)) : ?>
        <div class="attachmentcaption"><?php echo get_the_excerpt(); ?></div> 
    <?php endif; ?>
</div>