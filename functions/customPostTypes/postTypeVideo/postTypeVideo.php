<?php

//http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress
function create_posttype_video()
{
    register_post_type('video', array(
        'labels' => array(
            'name' => __('Videos'),
            'singular_name' => __('Video'),
            'menu_name' => __('Videos'),
            'name_admin_bar' => __('Video'),
            'all_items' => __('All Videos'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Video'),
            'edit_item' => __('Edit Video'),
            'new_item' => __('New Video'),
            'view_item' => __('View Video'),
            'search_items' => __('Search Videos'),
            'not_found' => __('No videos found'),
            'not_found_in_trash' => __('No videos found in Trash'),
            'parent_item_colon' => __('Parent Video'),
        ),
        'description' => 'A post with a featured video at the top.',
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'videos'), //flush_rewrite_rules(); or change, save, and revert permalink structure
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail'),
        'can_export' => true,
        'hierarchical' => false, //true makes it like a page
        'taxonomies' => array('category', 'post_tag'),
            )
    );
}

add_action('init', 'create_posttype_video');

function setup_featured_video_settings()
{
    add_meta_box('featured_video_settings', 'Featured Video', 'show_featured_video_settings', 'video', 'side', 'high');
}

add_action('add_meta_boxes', 'setup_featured_video_settings');

//http://organicthemes.com/tutorial/add-a-featured-video/
function show_featured_video_settings()
{
    wp_nonce_field(basename(__FILE__), 'youtube_settings_nonce');

    $video_embed_code = getPostMeta(get_the_ID(), '_video_embed_code');
    $youtube_vid_id = getPostMeta(get_the_ID(), '_youtube_vid_id');
    $youtube_vid_quality = getPostMeta(get_the_ID(), '_youtube_vid_quality');
    $youtube_vid_width = getPostMeta(get_the_ID(), '_youtube_vid_width');
    $youtube_vid_height = getPostMeta(get_the_ID(), '_youtube_vid_height');
    $show_featured_video = (
            getPostMeta(get_the_ID(), '_show_featured_video') == 'TRUE' ||
            getPostMeta(get_the_ID(), '_show_featured_video') == '') ? 'checked' : '';

    $params = array('post_id' => get_the_ID());
    wp_localize_script('ajaxPostVideoUpdates', 'params', $params);
    ?>
    <div id="featured_video_settings_metabox">
        <p>
            <label for="video_embed_code">Video Embed Code</label>
            <textarea id='video_embed_code' name='video_embed_code_textarea' class='videoembedcode'><?php echo $video_embed_code; ?></textarea>
        </p>
        <p>
            <label for="youtube_vid_id">Youtube video ID: </label><input id="youtube_vid_id" type="text" name="youtube_vid_id_input" value="<?php echo $youtube_vid_id; ?>" size="12">
        </p>
        <p>
            <label for="youtube_vid_quality">Video Quality: </label>
            <select name='video_quality_list_select' id="youtube_vid_quality">
                <option value="default">Default</option>
                <option value="youtube720">HD 720</option>
                <option value="youtube1080">HD 1080</option>
            </select> 
            <script>jQuery("#youtube_vid_quality").val("<?php echo $youtube_vid_quality; ?>");</script>
        </p>
        <p>
            <label for="youtube_vid_width">Width: </label><input id="youtube_vid_width" type="text" name="youtube_vid_width_input" value="<?php echo $youtube_vid_width; ?>" size="4" maxlength="4">
            <label for="youtube_vid_height">Height: </label><input id="youtube_vid_height" type="text" name="youtube_vid_height_input" value="<?php echo $youtube_vid_height; ?>" size="4" maxlength="4">
        </p>
        <p class="optionssection">
            <label for="show_featured_video">Show Featured Video: </label><input type="checkbox" id='show_featured_video' name="show_featured_video_checkbox"  <?php echo $show_featured_video; ?>>
        </p>
        <button type='button' id="save_youtube_settings_button" class="button button-primary button-large" disabled='disabled'>Update</button>
        <span class='spinner'></span>
    </div>
    <?php
}

function custom_post_type_video_enqueue_scripts($hook)
{
    if (get_post_type(get_the_ID()) == 'video')
    {
        wp_enqueue_script('ajaxPostVideoUpdates', get_template_directory_uri() . '/functions/customPostTypes/postTypeVideo/postTypeVideo.js', array('jquery'), '', 'true');
        wp_enqueue_style('custom_post_type_video_metabox_style', get_template_directory_uri() . '/functions/customPostTypes/postTypeVideo/postTypeVideo.css');
    }
}

add_action('admin_enqueue_scripts', 'custom_post_type_video_enqueue_scripts');

function ajax_save_youtube_settings()
{
    $post_id = absint($_GET['post_id']);

//    if (!isset($_POST['youtube_settings_nonce']) || !wp_verify_nonce($_POST['youtube_settings_nonce'], basename(__FILE__)))
//        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $video_embed_code = esc_attr($_GET['video_embed_code']);
    $youtube_vid_id = esc_attr($_GET['youtube_vid_id']);
    $youtube_vid_quality = esc_attr($_GET['youtube_vid_quality']);
    $youtube_vid_width = absint($_GET['youtube_vid_width']);
    $youtube_vid_height = absint($_GET['youtube_vid_height']);
    $show_featured_video = esc_attr($_GET['show_featured_video']);

    update_post_meta($post_id, '_video_embed_code', $video_embed_code);
    update_post_meta($post_id, '_youtube_vid_id', $youtube_vid_id);
    update_post_meta($post_id, '_youtube_vid_quality', $youtube_vid_quality);
    update_post_meta($post_id, '_youtube_vid_width', $youtube_vid_width);
    update_post_meta($post_id, '_youtube_vid_height', $youtube_vid_height);
    update_post_meta($post_id, '_show_featured_video', $show_featured_video);
}

add_action('wp_ajax_ajax_save_youtube_settings', 'ajax_save_youtube_settings');

function save_youtube_settings($post_id)
{
    if (!isset($_POST['youtube_settings_nonce']) || !wp_verify_nonce($_POST['youtube_settings_nonce'], basename(__FILE__)))
        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $video_embed_code = esc_attr($_POST['video_embed_code_textarea']);
    $youtube_vid_id = esc_attr($_POST['youtube_vid_id_input']);
    $youtube_vid_quality = esc_attr($_POST['video_quality_list_select']);
    $youtube_vid_width = absint($_POST['youtube_vid_width_input']);
    $youtube_vid_height = absint($_POST['youtube_vid_height_input']);
    $show_featured_video = esc_attr($_POST['show_featured_video']);

    update_post_meta($post_id, '_video_embed_code', $video_embed_code);
    update_post_meta($post_id, '_youtube_vid_id', $youtube_vid_id);
    update_post_meta($post_id, '_youtube_vid_quality', $youtube_vid_quality);
    update_post_meta($post_id, '_youtube_vid_width', $youtube_vid_width);
    update_post_meta($post_id, '_youtube_vid_height', $youtube_vid_height);
    update_post_meta($post_id, '_show_featured_video', $show_featured_video);
}

add_action('edit_post', 'save_youtube_settings');
add_action('save_post', 'save_youtube_settings');
add_action('publish_post', 'save_youtube_settings');
add_action('pre_post_update', 'save_youtube_settings');

function has_featured_video()
{
    return get_post_meta(get_the_ID(), '_youtube_vid_id', TRUE) . get_post_meta(get_the_ID(), '_video_embed_code', TRUE) != '';
}

function show_featured_video()
{
    return $show_video = get_post_meta(get_the_ID(), '_show_featured_video', TRUE) != 'FALSE';
}

function the_youtube_thumbnail($attr = array())
{
    ?>
    <img class="youtubethumbnail" src="http://img.youtube.com/vi/<?php echo get_post_meta(get_the_ID(), '_youtube_vid_id', TRUE); ?>/1.jpg" alt="<?php echo $attr['alt']; ?>" title="<?php echo $attr['title']; ?>">
    <?php
}

function the_youtube_video()
{
    $video_embed_code = get_post_meta(get_the_ID(), '_video_embed_code', TRUE);
    if ($video_embed_code !== '')
    {
        echo $video_embed_code;
    }
    else
    {
        $id = (get_post_meta(get_the_ID(), '_youtube_vid_id', TRUE));
        $width = (get_post_meta(get_the_ID(), '_youtube_vid_width', TRUE));
        $height = (get_post_meta(get_the_ID(), '_youtube_vid_height', TRUE));

        $hd = '';
        $youtube_vid_quality = get_post_meta(get_the_ID(), '_youtube_vid_quality', TRUE);

        if ($youtube_vid_quality == 'youtube1080')
        {
            $hd = '&vq=hd1080';
        }
        elseif ($youtube_vid_quality == 'youtube720')
        {
            $hd = '&vq=hd720';
        }
        ?>
        <iframe width="<?php echo $width; ?>" height="<?php echo $height ?>" frameborder="0" src="http://www.youtube.com/embed/<?php echo $id ?>?wmode=opaque<?php echo $hd ?>"></iframe>
        <?php
    }
}

function add_user_videos_columns($columns)
{
    $columns['videos_count num'] = get_post_type_object('video')->labels->name;
    return $columns;
}

add_filter('manage_users_columns', 'add_user_videos_columns');

function user_videos_column_content($output, $column_name, $user_id)
{
    if ($column_name == 'videos_count num')
    {
        return '<a class="edit" href="' . get_admin_url(null, 'edit.php?post_type=video&author=' . $user_id) . '">' . count_user_posts_by_type($user_id, 'video') . '</a>';
    }
    return $output;
}

add_filter('manage_users_custom_column', 'user_videos_column_content', 10, 3);

function addVideoPostCountToAtAGlance()
{
    add_custom_post_counts(array('video'));
}

add_action('dashboard_glance_items', 'addVideoPostCountToAtAGlance');

function get_video_posts($query)
{
    if ((is_home() || is_author()) && $query->is_main_query())
    {
        $query->query_vars['post_type']['video'] = 'video';
    }

    return $query;
}

add_filter('pre_get_posts', 'get_video_posts');

function widget_posts_args_add_video($params)
{
    $params['post_type'][] = 'video';
    return $params;
}

add_filter('widget_posts_args', 'widget_posts_args_add_video');
