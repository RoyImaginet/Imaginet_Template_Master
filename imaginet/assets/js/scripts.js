jQuery(document).ready(function($) {
    
    $('img').each(function () {
        var $img = $(this);
        var filename = $img.attr('alt')
        $img.attr('title', filename);
    });
    /*======================== Mobile menu =========================*/
    $('.menu-toggle').on('click', function() {
        var $nav = $('.site-navigation');
        var $button = $('.triggerMobileMenu');
        var $toggle = $(this);

        var isOpen = $nav.hasClass('is-open');

        $nav.toggleClass('is-open');
        $button.toggleClass('open');

        $toggle.attr('aria-expanded', !isOpen);
    });
    
    
    $('li.menu-item-has-children a').click(function(e) {
        if ($(window).width() < 991) {
            if ($(this).closest('ul').hasClass('sub-menu')) {
                return; 
            }
            e.preventDefault();
            $(this).next('ul.sub-menu').slideToggle();
        }
    });
    

    
    $(window).on('resize scroll', function() {
    
    });
})
