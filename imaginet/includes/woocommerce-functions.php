<?php
function imaginet_woocommerce_support() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 6,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'imaginet_woocommerce_support' ); 

//------------- Remove product tabs ---------------------//

add_filter( 'woocommerce_product_tabs', 'imaginet_remove_all_product_tabs', 98 );
 
function imaginet_remove_all_product_tabs( $tabs ) {
  unset( $tabs['description'] );  
  unset( $tabs['reviews'] );  
  unset( $tabs['additional_information'] ); 
  return $tabs;
}
