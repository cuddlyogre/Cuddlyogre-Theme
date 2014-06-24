<?php

//http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress
function create_posttype_picture()
{
    register_post_type('picture', array(
        'labels' => array(
            'name' => __('Pictures'),
            'singular_name' => __('Picture'),
            'menu_name' => __('Pictures'),
            'name_admin_bar' => __('Picture'),
            'all_items' => __('All Pictures'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Picture'),
            'edit_item' => __('Edit Picture'),
            'new_item' => __('New Picture'),
            'view_item' => __('View Picture'),
            'search_items' => __('Search Pictures'),
            'not_found' => __('No pictures found'),
            'not_found_in_trash' => __('No pictures found in Trash'),
            'parent_item_colon' => __('Parent Picture'),
        ),
        'description' => 'A post with a featured image at the top.',
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'pictures'), //flush_rewrite_rules(); or change, save, and revert permalink structure
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail'),
        'can_export' => true,
        'hierarchical' => false, //true makes it like a page, add 'page-attributes' to supports to give it pagey options
        'taxonomies' => array('category', 'post_tag'),
            )
    );
}

add_action('init', 'create_posttype_picture');

//http://wordpress.stackexchange.com/questions/23839/using-add-theme-support-inside-a-plugin
function addPostThumbnailsForPicturesCPT()
{
    global $_wp_theme_features;

    if (empty($_wp_theme_features['post-thumbnails']))
        $_wp_theme_features['post-thumbnails'] = array(array('picture'));

    elseif (true === $_wp_theme_features['post-thumbnails'])
        return;

    elseif (is_array($_wp_theme_features['post-thumbnails'][0]))
        $_wp_theme_features['post-thumbnails'][0][] = 'picture';
}

add_action('init', 'addPostThumbnailsForPicturesCPT');

function setup_featured_image_settings()
{
    add_meta_box('featured_image_settings', 'Featured Image Options', 'show_featured_image_settings', 'picture', 'side', 'high');
}

add_action('add_meta_boxes', 'setup_featured_image_settings');

function show_featured_image_settings()
{
    wp_nonce_field(basename(__FILE__), 'featured_image_settings_nonce');

    $show_featured_image = (
            getPostMeta(get_the_ID(), '_show_featured_image') == 'TRUE' ||
            getPostMeta(get_the_ID(), '_show_featured_image') == '') ? 'checked' : '';

    $params = array('post_id' => get_the_ID());
    wp_localize_script('ajaxFeaturedImageUpdates', 'params', $params);
    ?>
    <div id="featured_image_settings_metabox">
        <p class="optionssection">
            <label for="show_featured_image">Show Featured Image: </label><input type="checkbox" id='show_featured_image' name="show_featured_image_checkbox"  <?php echo $show_featured_image; ?>>
        </p>
        <button type='button' id="save_featured_image_settings_button" class="button button-primary button-large" disabled='disabled'>Update</button>
        <span class='spinner'></span>
    </div>
    <?php
}

function custom_post_type_picture_enqueue_scripts($hook)
{
    if (get_post_type(get_the_ID()) == 'picture')
    {
        wp_enqueue_script('ajaxFeaturedImageUpdates', get_template_directory_uri() . '/functions/customPostTypes/postTypePicture/postTypePicture.js', array('jquery'), '', 'true');
        wp_enqueue_style('custom_post_type_picture_metabox_style', get_template_directory_uri() . '/functions/customPostTypes/postTypePicture/postTypePicture.css');
    }
}

add_action('admin_enqueue_scripts', 'custom_post_type_picture_enqueue_scripts');

function ajax_save_featured_image_settings()
{
    $post_id = absint($_GET['post_id']); //set by ajaxFeaturedImageUpdates.js
//    if (!isset($_POST['featured_image_settings_nonce']) || !wp_verify_nonce($_POST['featured_image_settings_nonce'], basename(__FILE__)))
//        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $show_featured_image = esc_attr($_GET['show_featured_image']);

    update_post_meta($post_id, '_show_featured_image', $show_featured_image);
}

add_action('wp_ajax_ajax_save_featured_image_settings', 'ajax_save_featured_image_settings');

function save_featured_image_settings($post_id)
{
    if (!isset($_POST['featured_image_settings_nonce']) || !wp_verify_nonce($_POST['featured_image_settings_nonce'], basename(__FILE__)))
        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $showfeatured_image = (isset($_POST['show_featured_image_checkbox'])) ? 'TRUE' : 'FALSE';
    update_post_meta($post_id, '_showfeaturedimage', $showfeatured_image);
}

add_action('edit_post', 'save_featured_image_settings');
add_action('save_post', 'save_featured_image_settings');
add_action('publish_post', 'save_featured_image_settings');
add_action('pre_post_update', 'save_featured_image_settings');

function has_featured_image()
{
    return has_post_thumbnail();
}

function show_featured_image()
{
    return getPostMeta(get_the_ID(), '_show_featured_image') != 'FALSE';
}

function the_featured_image()
{
    $attr = array('alt' => get_the_title(), 'title' => get_the_title());
    the_post_thumbnail('single', $attr);
}

//http://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
// GET FEATURED IMAGE
function get_featured_image($post_ID)
{
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id)
    {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featuredtertiary');
        return $post_thumbnail_img[0];
    }
}

function get_featured_image_attachment_page($post_ID)
{
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id)
    {
        $post_thumbnail_attchment_url = get_attachment_link($post_thumbnail_id);
        return $post_thumbnail_attchment_url;
    }
}

// ADD NEW COLUMN
function post_type_picture_column_head($columns)
{
    $columns['featured_image'] = 'Featured Image';
    return $columns;
}

//manage_posts_columns
add_filter('manage_picture_posts_columns', 'post_type_picture_column_head');

// SHOW THE FEATURED IMAGE
function featured_image_column_content($column_name, $post_ID)
{
    if ($column_name == 'featured_image')
    {
        $post_featured_image = get_featured_image($post_ID);
        $post_thumbnail_attchment_url = get_featured_image_attachment_page($post_ID);
        if ($post_featured_image)
        {
            ?>
            <a href="<?php echo $post_thumbnail_attchment_url; ?>">
                <img src="<?php echo $post_featured_image; ?>">
            </a>
            <?php
        }
    }
}

add_action('manage_picture_posts_custom_column', 'featured_image_column_content', 10, 2);

function add_user_pictures_column($columns)
{
    $columns['pictures_count num'] = get_post_type_object('picture')->labels->name;
    return $columns;
}

add_filter('manage_users_columns', 'add_user_pictures_column');

function user_pictures_column_content($output, $column_name, $user_id)
{
    if ($column_name == 'pictures_count num')
    {
        return '<a class="edit" href="' . get_admin_url(null, 'edit.php?post_type=picture&author=' . $user_id) . '">' . count_user_posts_by_type($user_id, 'picture') . '</a>';
    }
    return $output;
}

add_filter('manage_users_custom_column', 'user_pictures_column_content', 10, 3);

function addPicturePostCountToAtAGlance()
{
    add_custom_post_counts(array('picture'));
}

add_action('dashboard_glance_items', 'addPicturePostCountToAtAGlance');

function get_picture_posts($query)
{
    if ((is_home() || is_author()) && $query->is_main_query())
    {
        $query->query_vars['post_type']['picture'] = 'picture';
    }

    return $query;
}

add_filter('pre_get_posts', 'get_picture_posts');

function widget_posts_args_add_picture($params)
{
    $params['post_type'][] = 'picture';
    return $params;
}

add_filter('widget_posts_args', 'widget_posts_args_add_picture');
