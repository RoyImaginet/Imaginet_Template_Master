<?php
// ENQUEUE STYLES
function imaginet_enqueue_styles()
{
    wp_enqueue_style(
        'style',
        get_template_directory_uri() . '/style.css',
        array(), 
        time(),
        'all'    
    );

    $custom_style_path = is_rtl() ? '/assets/scss/style-rtl.css' : '/assets/scss/style.css';

    wp_enqueue_style(
        'scss',
        get_template_directory_uri() . $custom_style_path,
        array('style'), 
        time(),
        'all'    
    );
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
