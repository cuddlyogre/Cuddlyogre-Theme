<?php

//https://gist.github.com/davejamesmiller/1966543
/* <a href="<?php echo esc_attr(admin_url('post-new.php?post_title=Default+title&category=category1&tags=tag1,tag2')) ?>">New post</a> */
function add_default_terms($terms, $object_ids, $taxonomies, $args)
{
    if (!$terms && basename($_SERVER['PHP_SELF']) == 'post-new.php')
    {
        // Category - note: only 1 category is supported currently
        if ($taxonomies == "'category'" && isset($_REQUEST['category']))
        {
            $id = get_cat_id($_REQUEST['category']);
            if ($id)
            {
                return array($id);
            }
        }
        // Tags
        if ($taxonomies == "'post_tag'" && isset($_REQUEST['tags']))
        {
            $tags = $_REQUEST['tags'];
            $tags = is_array($tags) ? $tags : explode(',', trim($tags, " \n\t\r\0\x0B,"));
            $term_ids = array();
            foreach ($tags as $term)
            {
                if (!$term_info = term_exists($term, 'post_tag'))
                {
                    // Skip if a non-existent term ID is passed.
                    if (is_int($term))
                        continue;
                    $term_info = wp_insert_term($term, 'post_tag');
                }
                $term_ids[] = $term_info['term_id'];
            }
            return $term_ids;
        }
    }
    return $terms;
}

add_filter('wp_get_object_terms', 'add_default_terms', 10, 4);

//add_default_terms in helpers.php
function admin_menu_new_items()
{
    global $submenu;

    //$submenu['edit.php'][11] = array('Add New Testimonial', 'manage_options', 'post-new.php?post_title=Testimonial+-+&category=testimonial');
    // WordPress treats $submenu as an associative array and does not sort it first.
    // We have to sort keys into the order we want them to show up.
    ksort($submenu['edit.php'], SORT_NUMERIC);
}

add_action('admin_menu', 'admin_menu_new_items', 1);
