$(function () {
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
    // mobile menu style
    $('.mobile_menu_button .triggerMobileMenu').click(function () {
        $(this).toggleClass('open');
        var targetID = $(this).data('toggle');
        $('#' + targetID).toggleClass('is-open');
        $(this).find('.tab').toggleClass('active');
        $('body').toggleClass('has-overflow');
    });
    $('ul#mobile-menu li a').click(function(){
        $('body').toggleClass('has-overflow');
        $('.off-canvas').toggleClass('is-open');
    });
    
    $(window).on('resize scroll', function() {
    
    });
})
