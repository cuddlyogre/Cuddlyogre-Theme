<?php

// [bartag foo="foo-value"]
function sizes_handler($atts)
{
    ob_start();

    extract(shortcode_atts(array(
        'path' => 'missing_path',
        'class' => '',
        'title' => '',
                    ), $atts));

    $uploadspath = WP_CONTENT_DIR . "/uploads";
    $uploadsurl = wp_upload_dir();
    $uploadurlbase = sanitize_text_field($uploadsurl['baseurl']);

    $filepath = $uploadspath . $path;
    $fileurl = $uploadurlbase . $path;

    if (file_exists($filepath))
    {
        $wall1 = "1920x1080";
        $wall2 = "1600x900";
        $wall3 = "1366x768";
        $wall4 = "1344x756";
        $wall5 = "1280x720";

        $path_parts = pathinfo($filepath);
        $url_parts = pathinfo($fileurl);
        ?>
        <div class='shortcode_image_sizes <?php echo $class; ?>'>
            <p><?php echo $title; ?></p>
            <?php
            displayImageSize($path_parts, $url_parts, $wall1);
            displayImageSize($path_parts, $url_parts, $wall2);
            displayImageSize($path_parts, $url_parts, $wall3);
            displayImageSize($path_parts, $url_parts, $wall4);
            displayImageSize($path_parts, $url_parts, $wall5);
            ?>
        </div>
        <?php
    }

    return ob_get_clean();
    //return "foo = {$foo}";
}

add_shortcode('show_sizes_links', 'sizes_handler');

function

displayImageSize($path_parts, $url_parts, $size)
{
    $pathdirname = $path_parts['dirname'];
    $urldirname = $url_parts['dirname'];
    $filename = $path_parts['filename'];
    $extension = $path_parts['extension'];

    $wallpath = $pathdirname . "/" . $filename . "-" . $size . "." . $extension;
    $wallurl = $urldirname . "/" . $filename . "-" . $size . "." . $extension;

    if (file_exists($wallpath))
    {
        ?>
        <p>
            <a title='<?php echo $filename; ?>-<?php echo $size; ?>' target="_blank" href="<?php echo $wallurl; ?>"><?php echo $size; ?></a>
        </p>
        <?php
    }
}
