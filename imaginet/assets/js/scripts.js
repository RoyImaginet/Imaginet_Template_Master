jQuery(document).ready(function($) {
    var headerHeight = $('header').outerHeight();
    var footerHeight = $('footer').outerHeight();
    if ($('#wpadminbar').length) {
        adminBar = $('#wpadminbar').outerHeight();
    }else{
        adminBar = 0;
    }
    $('#offCanvas').css({
        'top': 0,
        'padding-top': headerHeight
    });
    $('img').each(function () {
        var $img = $(this);
        var filename = $img.attr('alt')
        $img.attr('title', filename);
    });
    /*======================== Mobile menu =========================*/
    $('.menu-toggle').on('click', function() {
        var $nav = $('.site-navigation');
        var $toggle = $(this);

        var isOpen = $nav.hasClass('is-open');

        $nav.toggleClass('is-open');

        $toggle.attr('aria-expanded', !isOpen);
    });

    
    $(window).on('resize scroll', function() {
    
    });
})
