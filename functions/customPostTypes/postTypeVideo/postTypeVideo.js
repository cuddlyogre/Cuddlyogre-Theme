jQuery(document).ready(function($) {
    $('#save_youtube_settings_button').click('click', function() {
        var video_embed_code = $('#video_embed_code').val();
        var youtube_vid_id = $('#youtube_vid_id').val();
        var youtube_vid_quality = $('#youtube_vid_quality').val();
        var youtube_vid_width = $('#youtube_vid_width').val();
        var youtube_vid_height = $('#youtube_vid_height').val();
        var show_featured_video = $('#show_featured_video').is(':checked').toString().toUpperCase();

        $.ajax({
            url: ajaxurl,
            data: {
                action: 'ajax_save_youtube_settings',
                video_embed_code: video_embed_code,
                youtube_vid_id: youtube_vid_id,
                youtube_vid_quality: youtube_vid_quality,
                youtube_vid_width: youtube_vid_width,
                youtube_vid_height: youtube_vid_height,
                show_featured_video: show_featured_video,
                post_id: params.post_id
            },
            beforeSend: function() {
                enable_spinner();
            }
        }).done(function() {
            console.log('Saved featured video settings.');
            disable_save_button();
            disable_spinner();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    });

    $('#video_embed_code').on('input', function() {
        if ($('#video_embed_code').val() !== '')
            disable_youtube_settings();
        else
            enable_youtube_settings();
    });

    $('#video_embed_code, #youtube_vid_id, #youtube_vid_width, #youtube_vid_height').on('input', function() {
        enable_save_button();
    });

    $('#youtube_vid_quality, #show_featured_video').change(function() {
        enable_save_button();
    });

    function disable_youtube_settings()
    {
        $('#youtube_vid_id, #youtube_vid_width, #youtube_vid_height, #youtube_vid_quality').attr("disabled", true);
    }

    function enable_youtube_settings()
    {
        $('#youtube_vid_id, #youtube_vid_width, #youtube_vid_height, #youtube_vid_quality').attr("disabled", false);
    }

    function enable_save_button()
    {
        $('#save_youtube_settings_button').attr("disabled", false);
    }

    function disable_save_button()
    {
        $('#save_youtube_settings_button').attr("disabled", true);
    }

    function enable_spinner()
    {
        $('#featured_video_settings_metabox .spinner').css('display', 'initial');
    }

    function disable_spinner()
    {
        $('#featured_video_settings_metabox .spinner').css('display', 'none');
    }
});