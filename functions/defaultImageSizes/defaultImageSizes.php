<?php

function createDefaultImage($name, $width, $height, $overwrite = FALSE)
{
    $path = get_template_directory() . '/images/' . $name . '.jpg';
    if (!file_exists($path) || $overwrite == TRUE)
    {
        // Create a blank image and add some text 
        $im = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $white);
        //$text_color = imagecolorallocate($im, 255, 255, 255); 
        //imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color); 
        // Set the content type header - in this case image/jpeg 
        header('Content-Type: image/jpeg');
        // Output the image 
        imagejpeg($im, $path);
        // Free up memory 
        imagedestroy($im);
    }
}

function setupDefaultImages()
{
    add_image_size('featuredprimary', 650, 366, FALSE);
    add_image_size('featuredsecondary', 325, 183, FALSE);
    add_image_size('featuredtertiary', 195, 110, FALSE);
    add_image_size('single', 940);
    add_image_size('wall1', 1920, 1080);
    add_image_size('wall2', 1600, 900);
    add_image_size('wall3', 1366, 768);
    add_image_size('wall4', 1344, 756);
    add_image_size('wall5', 1280, 720);
    createDefaultImage('featuredprimary', 650, 366);
    createDefaultImage('featuredsecondary', 325, 183);
    createDefaultImage('featuredtertiary', 195, 110);
    createDefaultImage('header', 1005, 160);
}

add_action('init', 'setupDefaultImages');

//http://wordpress.stackexchange.com/questions/39004/add-size-in-gallery-settings-in-media-library
function dl_custom_image_sizes_add_settings($sizes)
{
    //unset( $sizes['thumbnail']); //comment to remove size if needed
    //unset( $sizes['medium']);// uncomment to remove size if needed
    //unset( $sizes['large']);// uncomment to restore size if needed
    //unset( $sizes['full'] ); // comment to remove size if needed
    $mynewimgsizes = array(
        "wall1" => __("1920x1080"),
        "wall2" => __("1600x900"),
        "wall3" => __("1366x768"),
        "wall4" => __("1344x756"),
        "wall5" => __("1280x720"),
    );
    $newimgsizes = array_merge($sizes, $mynewimgsizes);
    return $newimgsizes;
}

add_filter('image_size_names_choose', 'dl_custom_image_sizes_add_settings');
