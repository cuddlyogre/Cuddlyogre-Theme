<?php

function limitExcerptMaxLength()
{
    wp_enqueue_script('ajaxExcerptMaxLength', get_template_directory_uri() . '/functions/maxExcerptLength/ajaxExcerptMaxLength.js', array('jquery'), '', 'true');
}

add_action('admin_enqueue_scripts', 'limitExcerptMaxLength');
