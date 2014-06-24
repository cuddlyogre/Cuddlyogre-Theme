<div class="postcontent">
    <div <?php post_class('clearfix'); ?>>
        <?php
        the_content();
        wp_link_pages();
        ?>
    </div>
    <?php
    if (canCatPaginate() && is_single())
        catpagination("postpagination");
    ?>
</div>