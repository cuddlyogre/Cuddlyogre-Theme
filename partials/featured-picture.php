<?php
if (has_featured_image())
{
    if (show_featured_image())
    {
        ?>
        <div id="postfeaturedcontent">
            <div class="postimage clearfix">
                <?php the_featured_image(); ?> 
            </div>
        </div>
        <?php
    }
}