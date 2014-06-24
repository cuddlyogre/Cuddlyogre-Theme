jQuery(document).ready(function($) {
    $('#featured_image_settings_metabox').appendTo('#postimagediv .inside');
    $('#featured_image_settings').remove();
    $('label[for="featured_image_settings-hide"]').remove();

    $('#save_featured_image_settings_button').click('click', function() {

        var show_featured_image = $('#show_featured_image').is(':checked').toString().toUpperCase();

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'ajax_save_featured_image_settings',
                show_featured_image: show_featured_image,
                post_id: params.post_id
            },
            beforeSend: function() {
                enable_spinner();
            }
        }).done(function() {
            console.log('Saved featured image settings.');
            disable_save_button();
            disable_spinner();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });

    $('#show_featured_image').change(function() {
        enable_save_button();
    });

    function enable_save_button()
    {
        $('#save_featured_image_settings_button').attr("disabled", false);
    }

    function disable_save_button()
    {
        $('#save_featured_image_settings_button').attr("disabled", true);
    }

    function enable_spinner()
    {
        $('#featured_image_settings_metabox .spinner').css('display', 'initial');
    }

    function disable_spinner()
    {
        $('#featured_image_settings_metabox .spinner').css('display', 'none');
    }
});