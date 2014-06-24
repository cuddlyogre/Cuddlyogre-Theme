<?php

function getThemeSetting($name)
{
    switch ($name)
    {
        case "showfeaturedimage":
            return "TRUE";
        case "youtube_vid_id":
            return "";
        case "youtube_vid_quality":
            return "default";
        case "youtube_vid_width":
            return "940";
        case "youtube_vid_height":
            return "529";
    }
}

function getPostMeta($postID, $setting)
{
    $setting_value = get_post_meta($postID, $setting, TRUE);

    if (!isset($setting_value) OR empty($setting_value))
    {
        //updates post with the requested default if it doesn't have it set
        update_post_meta($postID, $setting, getThemeSetting($setting));
    }
    return get_post_meta($postID, $setting, TRUE);
}

