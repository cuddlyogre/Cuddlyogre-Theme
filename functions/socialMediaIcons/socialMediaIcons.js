jQuery(document).ready(function($) {
    doWidget($);
});

//http://wordpress.stackexchange.com/questions/117292/enabling-jquery-when-dragging-available-widget-to-sidebar-area
jQuery(document).ajaxComplete(function(event, XMLHttpRequest, ajaxOptions) {
    // determine which ajax request is this (we're after "save-widget")
    var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
    for (i in pairs) {
        split = pairs[i].split('=');
        request[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
    }
    // only proceed if this was a widget-save request
    if (request.action && (request.action === 'save-widget')) {
        // locate the widget block
        widget = jQuery('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');

        // trigger manual save, if this was the save request 
        // and if we didn't get the form html response (the wp bug)
        if (!XMLHttpRequest.responseText)
            wpWidgets.save(widget, 0, 1, 0);

        // we got an response, this could be either our request above,
        // or a correct widget-save call, so fire an event on which we can hook our js
        else
        {
            doWidget(jQuery);
        }
    }
});

function doWidget($)
{
    var widget_contents_id = params.widget_contents_id;
    var icons_list = widget_contents_id + " " + '.iconslist';
    var add_icon_link = widget_contents_id + " " + ".add_icon_link";
    var move_icon_up_link = widget_contents_id + " " + ".move_icon_up_link";
    var move_icon_down_link = widget_contents_id + " " + ".move_icon_down_link";
    var remove_icon_link = widget_contents_id + " " + ".remove_icon_link";
    var social_media_network_icon = ".social_media_network_icon";
    var base_social_media_network_icon = widget_contents_id + " " + ".base_social_media_network_icon" + " " + social_media_network_icon;
    var top_move_up_button = widget_contents_id + " " + ".iconslist p:first-child span:last-child span:first-child a";
    var bottom_move_down_button = widget_contents_id + " " + ".iconslist p:last-child span:last-child span:nth-child(2) a";

    $(add_icon_link).click('click', function() {
        console.log('add');

        var item = $(base_social_media_network_icon).last().clone(true);
        var selectName = item.find('.social_media_icon_network_select_ICON_ID').attr('name').replace('ICON_ID', $(icons_list).children().length);
        var addressName = item.find('.social_media_icon_network_address_ICON_ID').attr('name').replace('ICON_ID', $(icons_list).children().length);

        item.find('.social_media_icon_network_select_ICON_ID').attr('name', selectName);
        item.find('.social_media_icon_network_address_ICON_ID').attr('name', addressName);

        item.appendTo(icons_list); //true brings click events back
    });

    $(top_move_up_button).click(function(e) {
        e.preventDefault();
    });

    $(bottom_move_down_button).click(function(e) {
        e.preventDefault();
    });

    $(move_icon_up_link).click('click', function() {
        var item = $(this).closest(social_media_network_icon);
        if (item.prev().length > 0)
        {
            console.log('move up');
            item.insertBefore(item.prev());
        }
    });

    $(move_icon_down_link).click('click', function() {
        var item = $(this).closest(social_media_network_icon);
        if (item.next().length > 0)
        {
            console.log('move down');
            item.insertAfter(item.next());
        }
    });

    $(remove_icon_link).click('click', function() {
        console.log('remove');
        var item = $(this).closest(social_media_network_icon);
        item.remove();
    });
}