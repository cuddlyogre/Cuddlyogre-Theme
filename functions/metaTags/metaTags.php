<?php

function inject_meta_tag_metabox()
{
    add_meta_box('add-meta-tag-metabox', 'Meta Tags', 'show_meta_tag_metabox', null, 'normal', 'default');
}

add_action('add_meta_boxes', 'inject_meta_tag_metabox');

function show_meta_tag_metabox()
{
    wp_nonce_field(basename(__FILE__), 'meta_tag_settings_nonce');

    $description_meta_tag_value = get_post_meta(get_the_ID(), '_description_meta_tag_value', TRUE);
    ?>
    <div id="meta_tag_settings_metabox">
        <p class="optionssection">
            <label for="description_meta_tag">Description:</label>
            <input id="description_meta_tag" type="text" name="description_meta_tag_input" value="<?php echo $description_meta_tag_value; ?>">
        </p>
        <button type='button' id="save_meta_tag_settings_button" class="button button-primary button-large" disabled='disabled'>Update</button>
        <span class='spinner'></span>
    </div>
    <?php
}

function meta_tag_metabox_enqueue_scripts()
{
    wp_enqueue_script('ajaxUpdateMetaTagSettings', get_template_directory_uri() . '/functions/metaTags/metaTags.js', array('jquery'), '', 'true');
    wp_enqueue_style('meta_tag_metabox_style', get_template_directory_uri() . '/functions/metaTags/metaTags.css');
}

add_action('admin_enqueue_scripts', 'meta_tag_metabox_enqueue_scripts');

function ajax_save_meta_tag_settings()
{
    $post_id = absint($_GET['post_id']);

//    if (!isset($_POST['meta_tag_settings_nonce']) || !wp_verify_nonce($_POST['meta_tag_settings_nonce'], basename(__FILE__)))
//        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $description_meta_tag_value = esc_attr($_GET['meta_tag_value']);
    update_post_meta($post_id, '_description_meta_tag_value', $description_meta_tag_value);
}

add_action('wp_ajax_ajax_save_meta_tag_settings', 'ajax_save_meta_tag_settings');

function save_meta_tag_settings($post_id)
{
    if (!isset($_POST['meta_tag_settings_nonce']) || !wp_verify_nonce($_POST['meta_tag_settings_nonce'], basename(__FILE__)))
        return;
    if (!current_user_can('edit_posts', $post_id))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    $description_meta_tag_value = esc_attr($_POST['description_meta_tag_input']);
    update_post_meta($post_id, '_description_meta_tag_value', $description_meta_tag_value);
}

add_action('edit_post', 'save_meta_tag_settings');
add_action('save_post', 'save_meta_tag_settings');
add_action('publish_post', 'save_meta_tag_settings');
add_action('pre_post_update', 'save_meta_tag_settings');

function place_meta_tags_in_head()
{
    ?>
    <meta name="description" content="<?php echo get_post_meta(get_the_ID(), '_description_meta_tag_value', TRUE); ?>">
    <?php
}

add_action('wp_head', 'place_meta_tags_in_head');
