<?php


// TODO: ADD OPTION TO LEAD TO PAGE SHARE URL AND NOT JUST THE PAGE
function displaySocialMediaIcons($links = array())
{
    ?>
    <ul class="socialmediaicons">
        <?php
        foreach ($links as $link)
        {
            $network_name = $link[0];
            $address = $link[1];
            $link_title = $network_name;
            $link_alt = $network_name . " " . "icon";
            $icon_name = strtolower(str_replace(' ', '-', str_replace('+', 'plus', $network_name)));
            $image_url = current_dir_url(__FILE__) . '/images/' . $icon_name . '.png';
            ?>
            <li>
                <a href="<?php echo $address; ?>" title="<?php echo $link_title; ?>">
                    <img alt="<?php echo $link_alt; ?>" src="<?php echo $image_url; ?>">
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
}

//http://code.tutsplus.com/articles/building-custom-wordpress-widgets--wp-25241
function register_my_widget()
{
    register_widget('Social_Media_Icons_Widget');
}

add_action('widgets_init', 'register_my_widget');

class Social_Media_Icons_Widget extends WP_Widget
{

    function __construct()
    {
        $id_base = 'social_media_icons_widget';
        $name = 'Social Media Icons';
        $widget_options = array('classname' => 'socialmediaicons', 'description' => 'A widget that displays icons for various social media networks');
        $control_options = array('width' => 550, 'height' => 350, 'id_base' => 'social_media_icons_widget');

        parent::__construct($id_base, $name, $widget_options, $control_options);
    }

// display the widget
    function widget($args, $instance)
    {
        wp_enqueue_style('socialMediaIconsStyle', current_dir_url(__FILE__) . '/socialMediaIcons.css');
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];

        // Display the widget title
        if ($title)
            echo $args['before_title'] . $title . $args['after_title'];

        //var_dump_pre($instance);
        displaySocialMediaIcons($instance['links']);

        echo $args['after_widget'];
    }

    // update the widget
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['links'] = $this->links_in_new_instance($new_instance); // $links;

        return $instance;
    }

    function links_in_new_instance($new_instance)
    {
        //http://stackoverflow.com/questions/4979238/php-get-all-keys-from-a-array-that-start-with-a-certain-string
        foreach ($new_instance as $key => $value)
        {
            $exp_key = explode('_', $key);
            if ($exp_key[0] == 'select' && is_numeric($exp_key[1]))
            {
                $selected[] = $value;
            }
            else if ($exp_key[0] == 'link' && is_numeric($exp_key[1]))
            {
                $address[] = $value;
            }
        }

        for ($i = 0; $i < count($selected); $i++)
        {
            $links[] = array($selected[$i], $address[$i]);
        }

        return $links;

        //the reason that items are saved in the proper order is because items are added to #_POST and $instance['links'] in the order they POST
        //wp_die(var_dump($new_instance));
    }

    // and of course the form for the widget options
    function form($instance)
    {
        $widget_contents_id = $this->id . "_contents";

        wp_localize_script('socialmediaiconswidget', 'params', array('widget_contents_id' => '#' . $widget_contents_id));

        $defaults = array('title' => '', 'links' => array());

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <div id="<?php echo $widget_contents_id; ?>" class="social_media_network_icon_contents">
            <span class="base_social_media_network_icon">
                <?php $this->new_social_media_listing($widget_contents_id); ?>
            </span>
            <p class="section">

                <label>Title: 
                    <input id="<?php echo $this->get_field_id('title'); ?>" class="title" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
                </label>

                <span class="maintenancelinks">
                    <span class="add_remove_link"><a class="add_icon_link">Add Icon</a></span>
                </span>

            </p>
            <div class="iconslist">
                <?php
                for ($i = 0; $i < count($instance['links']); $i++)
                {
                    $this->new_social_media_listing($widget_contents_id, $i, $instance['links'][$i][0], $instance['links'][$i][1]);
                }
                ?>
            </div>
        </div>
        <?php
    }

    function new_social_media_listing($widget_contents_id, $icon_id = 'ICON_ID', $selected_value = '', $address = '')
    {
        ?>
        <p class="social_media_network_icon">
            <label class="social_media_icon_network_select_label">
                Icon: <select name="<?php echo $this->get_field_name('select' . "_" . $icon_id); ?>" class="social_media_icon_network_select<?php echo '_' . $icon_id; ?>">
                    <option value="bloggr">Bloggr</option>
                    <option value="deviantart">DeviantArt</option>
                    <option value="digg">Digg</option>
                    <option value="dribbble">Dribbble</option>
                    <option value="email">Email</option>
                    <option value="evernote">Evernote</option>
                    <option value="facebook">Facebook</option>
                    <option value="flickr">Flickr</option>
                    <option value="forrst">Forrst</option>
                    <option value="google-plus">Google Plus</option>
                    <option value="instagram">Instagram</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="pinterest">Pinterest</option>
                    <option value="rss">RSS</option>
                    <option value="share">Share</option>
                    <option value="skype">Skype</option>
                    <option value="stumbleupon">StumbleUpon</option>
                    <option value="tumblr">Tumblr</option>
                    <option value="twitter">Twitter</option>
                    <option value="vimeo">Vimeo</option>
                    <option value="youtube">Youtube</option>
                </select>
            </label>

            <?php
            if (is_numeric($icon_id))
            {
                $social_media_network_dropdown_box = '#' . $widget_contents_id . ' ' . '.social_media_icon_network_select' . '_' . $icon_id;
                ?><script>jQuery("<?php echo $social_media_network_dropdown_box; ?>").val("<?php echo $selected_value; ?>");</script><?php
            }
            ?>

            <label class="social_media_icon_network_input_label">Address: <input type="text" class="social_media_icon_network_address<?php echo '_' . $icon_id; ?>" name="<?php echo $this->get_field_name('link' . "_" . $icon_id); ?>" value="<?php echo $address; ?>"></label>
            <span class="maintenancelinks">
                <span class="add_remove_link"><a class="move_icon_up_link">Move Up</a></span>
                <span class="add_remove_link"><a class="move_icon_down_link">Move Down</a></span>
                <span class="add_remove_link"><a class="remove_icon_link">Remove</a></span>
            </span>
        </p>
        <?php
    }

}

//http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
function social_media_icons_widget_enqueue_scripts($hook)
{
    if ($hook != 'widgets.php')
        return;
    wp_enqueue_script('socialmediaiconswidget', current_dir_url(__FILE__) . '/socialMediaIcons.js', array('jquery'), '', 'true');
    wp_enqueue_style('socialmediaiconsstyle', current_dir_url(__FILE__) . '/socialMediaIconsAdmin.css');
}

add_action('admin_enqueue_scripts', 'social_media_icons_widget_enqueue_scripts');
