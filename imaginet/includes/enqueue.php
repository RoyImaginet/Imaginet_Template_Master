<?php
// ENQUEUE STYLES
function imaginet_enqueue_styles()
{
    wp_enqueue_style(
        'style',
        THEME . '/style.css',
        array(), 
        time(),
        'all'    
    );
    wp_enqueue_style(
        'style',
        THEME . '/assets/css/style.css',
        array(), 
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
