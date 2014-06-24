//http://rosspenman.com/pushstate-jquery/

jQuery(document).ready(function($)
{
    String.prototype.decodeHTML = function() {
        return $("<div>", {html: "" + this}).html();
    };

    var init = function() {
    };

    var loadTitle = function(href) {
        $.get(href, function(data) {
            var title = $(data).filter('title').text();
            document.title = title;
        });
    };

    var loadMenu = function(href) {
        $.get(href, function(data) {
            var menu = $(data).find('.menucontainer');
            $('.menucontainer').replaceWith(menu);
        });
    };

    //http://stackoverflow.com/questions/3203035/jquery-use-load-to-append-data-instead-of-replace
    var loadPage = function(href) {
        $.get(href, function(data) {
            var content = $(data).find('#content');
            $('#content').replaceWith(content);
        });
    };

    init();

    $(window).on("popstate", function(e) {
        if (e.originalEvent.state !== null) {
            loadPage(location.href);
        }
    });

    $(document).on("click", "a, area", function(e) {

        var href = $(this).attr('href');

        //if ($(this).closest('#wpadminbar').length < 1)
        if (href.toLowerCase().indexOf("wp-") < 0)
        {
            if (href.indexOf(document.domain) > -1
                    || href.indexOf(':') === -1)
            {
                history.pushState({}, '', href);
                loadTitle(href);
                //loadMenu(href);
                loadPage(href);
                e.preventDefault();
            }
        }
    });
});

//if (document.location.pathname.indexOf("/about/") == 0) {
//Code goes here
//}

//$("#content").css("display", "none");

//$("#content").fadeIn(2000);

/*$("a.transition").click(function(event) {
 event.preventDefault();
 linkLocation = $("a.transition").attr('href');
 history.pushState({}, '', linkLocation);
 //$("#content").fadeOut(1000, redirectPage);
 redirectPage();
 });
 
 function redirectPage() {
 jQuery(document).ready(function($)
 {
 $('#contents').load(linkLocation + ' #contents');
 //$("#content").fadeIn(2000);
 });
 }*/
