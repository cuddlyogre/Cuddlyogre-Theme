<?php

// Enable font size & font family selects in the editor
//http://www.wpexplorer.com/wordpress-tinymce-tweaks/
if (!function_exists('wpex_mce_buttons'))
{

    function wpex_mce_buttons($buttons)
    {
        array_unshift($buttons, 'fontselect'); // Add Font Select
        array_unshift($buttons, 'fontsizeselect'); // Add Font Size Select
        return $buttons;
    }

}
add_filter('mce_buttons_2', 'wpex_mce_buttons');

// Customize mce editor font sizes
if (!function_exists('wpex_mce_text_sizes'))
{

    function wpex_mce_text_sizes($initArray)
    {
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
        return $initArray;
    }

}
add_filter('tiny_mce_before_init', 'wpex_mce_text_sizes');

// Add custom Fonts to the Fonts list
if (!function_exists('wpex_mce_google_fonts_array'))
{

    function wpex_mce_google_fonts_array($initArray)
    {
        $initArray['font_formats'] = 'Lato=Lato;'
                . 'Andale Mono=andale mono,times;'
                . 'Arial=arial,helvetica,sans-serif;'
                . 'Arial Black=arial black,avant garde;'
                . 'Book Antiqua=book antiqua,palatino;'
                . 'Comic Sans MS=comic sans ms,sans-serif;'
                . 'Courier New=courier new,courier;'
                . 'Georgia=georgia,palatino;'
                . 'Helvetica=helvetica;'
                . 'Impact=impact,chicago;'
                . 'Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;'
                . 'Terminal=terminal,monaco;'
                . 'Times New Roman=times new roman,times;'
                . 'Trebuchet MS=trebuchet ms,geneva;'
                . 'Verdana=verdana,geneva;'
                . 'Webdings=webdings;'
                . 'Wingdings=wingdings,zapf dingbats';
        return $initArray;
    }

}
add_filter('tiny_mce_before_init', 'wpex_mce_google_fonts_array');

// Add Formats Dropdown Menu To MCE
if (!function_exists('wpex_style_select'))
{

    function wpex_style_select($buttons)
    {
        array_push($buttons, 'styleselect');
        return $buttons;
    }

}
add_filter('mce_buttons', 'wpex_style_select');

// Add new styles to the TinyMCE "formats" menu dropdown
if (!function_exists('wpex_styles_dropdown'))
{

    function wpex_styles_dropdown($settings)
    {

        // Create array of new styles
        $new_styles = array(
            array(
                'title' => __('Custom Styles', 'wpex'),
                'items' => array(
                    array(
                        'title' => __('Theme Button', 'wpex'),
                        'selector' => 'a',
                        'classes' => 'theme-button'
                    ),
                    array(
                        'title' => __('Highlight', 'wpex'),
                        'inline' => 'span',
                        'classes' => 'text-highlight',
                    ),
                ),
            ),
        );

        // Merge old & new styles
        $settings['style_formats_merge'] = true;

        // Add new styles
        $settings['style_formats'] = json_encode($new_styles);

        // Return New Settings
        return $settings;
    }

}
add_filter('tiny_mce_before_init', 'wpex_styles_dropdown');
