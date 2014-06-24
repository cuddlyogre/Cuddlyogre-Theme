<?php

//http://codeseekah.com/2012/03/01/custom-post-type-archives-in-wordpress-menus-2/
//https://gist.github.com/bradryan13/11227455 - fixed spinner made $nav_menu_selected_id global
// revision 20120302.1
// Set-up Action and Filter Hooks
// inject cpt archives meta box
function inject_cpt_archives_menu_meta_box()
{
    add_meta_box('add-cpt', 'CPT Archives', 'wp_nav_menu_cpt_archives_meta_box', 'nav-menus', 'side', 'default');
}

add_action('admin_head-nav-menus.php', 'inject_cpt_archives_menu_meta_box');

// render custom post type archives meta box
function wp_nav_menu_cpt_archives_meta_box()
{
    global $nav_menu_selected_id;
    /* get custom post types with archive support */
    $post_types = get_post_types(array('show_in_nav_menus' => true, 'has_archive' => true), 'object');
    /* hydrate the necessary object properties for the walker */
    foreach ($post_types as &$post_type)
    {
        $post_type->classes = array();
        $post_type->type = $post_type->name;
        $post_type->object_id = $post_type->name;
        $post_type->title = $post_type->labels->name; // . ' ' . 'Archive'
        $post_type->object = 'cpt-archive';
        $post_type->menu_item_parent = 0;
        $post_type->url = 0;
        $post_type->target = 0;
        $post_type->attr_title = 0;
        $post_type->xfn = 0;
        $post_type->db_id = 0;
    }
    $walker = new Walker_Nav_Menu_Checklist(array());
    ?>

    <div id="cpt-archive" class="posttypediv">
        <div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">
            <ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
                <?php
                echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array('walker' => $walker));
                ?>
            </ul>
        </div><!-- /.tabs-panel -->
        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit"<?php disabled($nav_menu_selected_id, 0); ?> class="button-secondary right submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-ctp-archive-menu-item" id="submit-cpt-archive">
                <span class="spinner"></span>
            </span>
        </p>
    </div>
    <?php
}

function cpt_archive_menu_filter($items, $menu, $args)
{
    /* take care of the urls */
    /* alter the URL for cpt-archive objects */
    foreach ($items as &$item)
    {
        if ($item->object != 'cpt-archive')
            continue;
        $item->url = get_post_type_archive_link($item->type);
        /* set current */
        if (get_query_var('post_type') == $item->type)
        {
            $item->classes [] = 'current-menu-item';
            $item->current = true;
        }
    }
    return $items;
}

add_filter('wp_get_nav_menu_items', 'cpt_archive_menu_filter', 10, 3);

add_action('after_switch_theme', 'flush_rewrite_rules'); //flush rewrite rules on theme switch

function remove_user_posts_column($columns)
{
    unset($columns['posts']);
    return $columns;
}

add_filter('manage_users_columns', 'remove_user_posts_column');

function add_user_custom_post_type_columns($columns)
{
    //added num so it will be styled correctly
    //http://codex.wordpress.org/Function_Reference/get_post_type_object
    $columns['posts_count num'] = get_post_type_object('post')->labels->name;
    return $columns;
}

add_filter('manage_users_columns', 'add_user_custom_post_type_columns');

function user_custom_post_type_column_content($output, $column_name, $user_id)
{
    if ($column_name == 'posts_count num')
    {
        return '<a class="edit" href="' . get_admin_url(null, 'edit.php?post_type=post&author=' . $user_id) . '">' . count_user_posts_by_type($user_id, 'post') . '</a>';
    }
    return $output; //or else it won't display the post count/edit links for all but the last declared ctp
}

add_filter('manage_users_custom_column', 'user_custom_post_type_column_content', 10, 3);

//http://codex.wordpress.org/Function_Reference/count_user_posts
function count_user_posts_by_type($userid, $post_type = 'post')
{
    global $wpdb;
    $where = get_posts_by_author_sql($post_type, true, $userid);
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");
    return apply_filters('get_usernumposts', $count, $userid);
}

//ADD CUSTOM POST TYPES TO 'AT A GLANCE' WIDGET
//http://www.zighead.com/2013/12/customizing-wordpress-admin-panel-at-a-glance-widget/
function add_custom_post_counts($post_types = array('post'))
{
    // array of custom post types to add to 'At A Glance' widget
    foreach ($post_types as $pt) :
        $pt_info = get_post_type_object($pt); // get a specific CPT's details
        $num_posts = wp_count_posts($pt); // retrieve number of posts associated with this CPT
        $num = number_format_i18n($num_posts->publish); // number of published posts for this CPT
        $text = _n($pt_info->labels->singular_name, $pt_info->labels->name, intval($num_posts->publish)); // singular/plural text label for CPT
        echo '<li class="post-count ' . $pt_info->name . '-count"><a href="edit.php?post_type=' . $pt . '">' . $num . ' ' . $text . '</a></li>';
    endforeach;
}

//http://justintadlock.com/archives/2010/02/02/showing-custom-post-types-on-your-home-blog-page
function get_post_posts($query)
{
    if ((is_home() || is_author()) && $query->is_main_query())
    {
        $query->query_vars['post_type']['post'] = 'post';
    }

    return $query;
}

add_filter('pre_get_posts', 'get_post_posts');

//http://wordpress.stackexchange.com/questions/2405/including-custom-post-types-in-recent-posts-widget
function widget_posts_args_add_post($params)
{
    $params['post_type'][] = 'post';
    return $params;
}

add_filter('widget_posts_args', 'widget_posts_args_add_post');

require_once 'postTypePicture/postTypePicture.php';
require_once 'postTypeVideo/postTypeVideo.php';

function dh_add_help_tab($post)
{
    $id = 'test_help_tab';
    $title = 'Test Help Tab';
    $content = 'Test help tab content';
    $callback = null;

    $screen = get_current_screen();
    $screen->add_help_tab(array(
        'id' => $id, //unique id for the tab
        'title' => $title, //unique visible title for the tab
        'content' => $content, //actual help text
        'callback' => $callback //optional function to callback
    ));
}

//add_action('add_meta_boxes_{post-type}', 'dh_add_help_tab', 10);
//add_action('add_meta_boxes_picture', 'dh_add_help_tab');
