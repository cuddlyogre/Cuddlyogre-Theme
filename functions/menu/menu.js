jQuery(document).ready(function($) {
    $('.sub-menu').parent().css('cursor', 'default');
    $('.sub-menu').siblings('a').removeAttr('href');
    $('.sub-menu').siblings('a').append(' &raquo;');

    $('.menu-item-has-children > a').click(function() {
        $(this).parent().siblings().find('.sub-menu').hide();
        $(this).next('.sub-menu').find('.sub-menu').hide();
        $(this).next('.sub-menu').toggle();
    });

    $(document).mouseup(function(e)
    {
        if (!$(e.target).is('.menu-container *'))
        {
            $('.sub-menu').hide();
        }
    });
});