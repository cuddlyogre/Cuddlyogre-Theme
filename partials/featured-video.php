<?php
if (has_featured_video())
{
    if (show_featured_video())
    {
        ?>
        <div id="postfeaturedcontent">
            <div class="postimage clearfix">
                <?php the_youtube_video(); ?> 
            </div>
        </div>
        <?php
    }
}