<?php
// ENQUEUE STYLES
function imaginet_enqueue_styles()
{
    wp_enqueue_style(
        'base-style',
        get_template_directory_uri() . '/style.css',
        array(), 
        time(),
        'all'    
    );

    wp_enqueue_style(
        'custom-style',
        get_template_directory_uri() . '/assets/scss/style.css',
        array('base-style'), 
        time(),
        'all'    
    );

    if ( is_rtl() ) {
        wp_enqueue_style(
            'custom-rtl-style',
            get_template_directory_uri() . '/assets/scss/style-rtl.css',
            array('custom-style'), 
            time(),
            'all'    
        );
    }
}
add_action('wp_enqueue_scripts', 'imaginet_enqueue_styles'); 

// ENQUEUE SCRIPTS
function imaginet_enqueue_scripts()
{
    wp_enqueue_script(
        'scripts', 
        THEME . '/assets/js/scripts.js?v=' . time(), 
        array( 'jquery' ),
        NULL, 
        true 
    );
}
add_action('wp_enqueue_scripts', 'imaginet_enqueue_scripts');
