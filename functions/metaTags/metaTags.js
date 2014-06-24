jQuery(document).ready(function($) {
    $('#save_meta_tag_settings_button').click('click', function() {
        var meta_tag_value = $('#description_meta_tag').val();

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'ajax_save_meta_tag_settings',
                meta_tag_value: meta_tag_value,
                post_id: params.post_id
            },
            beforeSend: function() {
                enable_spinner();
            }
        }).done(function() {
            console.log('Saved meta tag settings.');
            disable_save_button();
            disable_spinner();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });

    $('#description_meta_tag').on('input', function() {
        enable_save_button();
    });

    function enable_save_button()
    {
        $('#save_meta_tag_settings_button').attr("disabled", false);
    }

    function disable_save_button()
    {
        $('#save_meta_tag_settings_button').attr("disabled", true);
    }

    function enable_spinner()
    {
        $('#meta_tag_settings_metabox .spinner').css('display', 'initial');
    }

    function disable_spinner()
    {
        $('#meta_tag_settings_metabox .spinner').css('display', 'none');
    }
});