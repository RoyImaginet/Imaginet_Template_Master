<?php

/*****************************************
 **  Languages
 *****************************************/
add_action('after_setup_theme', 'imaginet_theme_textdomain');
function imaginet_theme_textdomain()
{
    load_theme_textdomain('imaginet', THEME . '/languages'); // Localisation Support
}
/*****************************************
 **  Define
 *****************************************/
if (!defined('THEME')) {
    define("THEME", get_template_directory_uri());
}

if (!defined('TEMPLATEPATH')) {
    define('TEMPLATEPATH', get_template_directory());
}

/*****************************************
 **  Includes
 ****************************************/
get_template_part("includes/enqueue");
get_template_part("includes/types-and-taxonomies");
// uncomment line below to activate woocommerce 
// get_template_part("includes/woocommerce-functions");
//get_template_part("includes/ajax-functions");

/*****************************************
 **  Ajax
 ****************************************/

add_action( 'wp_enqueue_scripts', 'add_frontend_ajax_javascript_file' );
function add_frontend_ajax_javascript_file() {
    wp_enqueue_script( 'ajax_custom_script', THEME . '/assets/js/ajax-functions.js', array('jquery') );
    wp_localize_script( 'ajax_custom_script', 'ajaxurl', admin_url( 'admin-ajax.php' ));
}
/*****************************************
 **  Theme Support
 *****************************************/
if (function_exists('add_theme_support')) {
    // Add Menu Support
    add_theme_support('menus');
    // Add custom logo
    add_theme_support('custom-logo');
    // Add title tag in wp_head
    add_theme_support('title-tag');
    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    // override media setting - Image sizes
    add_image_size('small', 300, '', true); // Small Thumbnail
    add_image_size('medium', 640, '', true); // Medium Thumbnail
    add_image_size('large', 1024, '', true); // Large Thumbnail
    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');
    // Enable support for wp galleries with figure tag
    add_theme_support( 'html5', array( 
	    'comment-list', 
	    'comment-form', 
	    'search-form', 
	    'gallery', 
	    'caption', 
	    'style', 
	    'script' 
	) );
	add_theme_support( 'post-formats', array( 
        'aside', 
        'gallery', 
        'image', 
        'video', 
        'quote', 
        'link' 
    ) );
}
/*****************************************
 **  Security
 *****************************************/
//============== Hide Wordpress version ===========
function remove_wp_version() {
    return '';
}
add_filter( 'the_generator', 'remove_wp_version' );
//============== Disable  xmlrpc ===========
add_filter( 'xmlrpc_enabled', '__return_false' );

//===== Completely disable comments on media/attachments. =============
function imaginet_close_comments_on_new_uploads( $data ) {
    if ( isset( $data['post_type'] ) && 'attachment' === $data['post_type'] ) {
        $data['comment_status'] = 'closed';
        $data['ping_status']    = 'closed'; // Closes trackbacks/pings too
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'imaginet_close_comments_on_new_uploads' );

function imaginet_force_close_existing_media_comments( $open, $post_id ) {
    $post = get_post( $post_id );
    if ( $post && 'attachment' === $post->post_type ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'imaginet_force_close_existing_media_comments', 10, 2 );

add_action( 'template_redirect', function() {
    if ( is_attachment() ) {
        add_filter( 'comments_template', '__return_false', 99 );
    }
});
//============= Block the activation and installation of known file managers and IDE plugins. ===============
 
function imaginet_blacklist_file_managers( $plugin, $redirect = false ) {
	// List of known file manager / IDE plugin main file paths
	$blacklist = array(
		'wp-file-manager/wp-file-manager.php',
		'advanced-file-manager/wp-file-manager.php',
		'file-manager-advanced/file-manager-advanced.php',
		'wpide/wpide.php',
		'real-media-library/index.php',
		'filester/filester.php',
	);

	if ( in_array( $plugin, $blacklist, true ) ) {
		deactivate_plugins( $plugin );
		
		wp_die( 
			__( 'Security Restriction: File Manager and IDE plugins are strictly prohibited on this installation.', 'imaginet' ), 
			__( 'Plugin Blocked', 'imaginet' ), 
			array( 'back_link' => true ) 
		);
	}
}
add_action( 'activate_plugin', 'imaginet_blacklist_file_managers', 10, 1 );
add_action( 'activated_plugin', 'imaginet_blacklist_file_managers', 10, 1 );




//============== Register menus ===========
register_nav_menus(array( // Using array to specify more menus if needed
    'main-menu' => __('Main Menu', 'imaginet'), // Main Navigation
    'mobile-menu' => __('Mobile Menu', 'imaginet') // Mobile Navigation
));
// Register sidebars
if (function_exists('register_sidebar')) {
    $sidebar_array = array(
        array('name' => 'Main Sidebar', 'id' => 'main_sidebar'),
        array('name' => 'Blog', 'id' => 'blog_sidebar')
    );
    foreach ($sidebar_array as $sidebar) {
        register_sidebar(array(
            'name' => $sidebar['name'],
            'id' => $sidebar['id'],
            'description' => __('Drag here menu widgets to put in the sidebar', 'imaginet'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title' => '<h2 class="widget_title">',
            'after_title' => '</h2>'
        ));
    }
}

// Add body classes
if (!function_exists('add_body_class')) {
    function add_body_class($classes)
    {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if ($is_lynx) $classes[] = 'lynx';
        elseif ($is_gecko) $classes[] = 'gecko';
        elseif ($is_opera) $classes[] = 'opera';
        elseif ($is_NS4) $classes[] = 'ns4';
        elseif ($is_safari) $classes[] = 'safari';
        elseif ($is_chrome) $classes[] = 'chrome';
        elseif ($is_IE) {
            $classes[] = 'ie';
            if (preg_match('/MSIE ( [0-11]+ )( [a-zA-Z0-9.]+ )/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                $classes[] = 'ie' . $browser_version[1];
        } else $classes[] = 'unknown';
        if ($is_iphone) $classes[] = 'iphone';
        if (stristr($_SERVER['HTTP_USER_AGENT'], "mac")) {
            $classes[] = 'osx';
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "linux")) {
            $classes[] = 'linux';
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], "windows")) {
            $classes[] = 'windows';
        }
        return $classes;
    }
    add_filter('body_class', 'add_body_class');
}

// Advanced Custom Fields Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'	=> 'Theme General Settings',
        'menu_title'    => 'General Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'		=> false,
		'position'      => 60,
    ));
}
// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function imaginet_pagination()
{
    global $wp_query;

    // Stop if there is only 1 page of posts
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    echo '<nav class="pagination">';
    echo paginate_links( array(
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => __( '&laquo; Prev', 'my-theme' ),
        'next_text' => __( 'Next &raquo;', 'my-theme' ),
        'type'      => 'list', // Outputs clean semantic <ul> and <li> tags instead of raw links
    ) );
    echo '</nav>';
}

// Remove the excerpt more 'read more btn'
function imaginet_custom_excerpt_more( $more ) {
    return '&hellip;'; // Outputs an elegant … instead of [...]
}
add_filter( 'excerpt_more', 'imaginet_custom_excerpt_more' );

/**
 * Force Custom Brand Colors inside ACF TinyMCE WYSIWYG Editors
 */
function my_theme_acf_wysiwyg_colors( $init ) {
    
    $custom_colours = array(
        '16b1af', 'Turquoise',
        'df7d28', 'Orange',
        'a7cf3e', 'Light Green',
        '2f9de0', 'Blue Sky',
        'ffffff', 'White',
        '7d7d7d', 'Light Gray',
        '555555', 'Dark Gray'
    );

    $init['textcolor_map'] = json_encode( $custom_colours );

    $init['textcolor_rows'] = 1; 

    return $init;
}
add_filter( 'tiny_mce_before_init', 'my_theme_acf_wysiwyg_colors' );


/**
 * Securely log PHP data to the private wp-content/debug.log file
 */
function imaginet_theme_log( $data ) {
    if ( WP_DEBUG === true ) {
        if ( is_array( $data ) || is_object( $data ) ) {
            error_log( print_r( $data, true ) );
        } else {
            error_log( $data );
        }
    }
}
/**
 * Customize Flamingo Capabilities to allow Editors/Authors to view form submissions.
 */
function my_theme_custom_flamingo_caps( $caps, $cap, $user_id, $args ) {
    // Map Flamingo's strict admin capabilities to standard post capabilities
    $meta_caps = array(
        'flamingo_edit_contact'           => 'edit_posts',
        'flamingo_edit_contacts'          => 'edit_posts',
        'flamingo_delete_contact'         => 'edit_posts',
        'flamingo_edit_inbound_message'   => 'publish_posts',
        'flamingo_edit_inbound_messages'  => 'publish_posts',
        'flamingo_delete_inbound_message' => 'publish_posts',
        'flamingo_delete_inbound_messages'=> 'publish_posts',
        'flamingo_spam_inbound_message'   => 'publish_posts',
        'flamingo_unspam_inbound_message' => 'publish_posts',
        'flamingo_edit_outbound_message'  => 'publish_posts',
        'flamingo_edit_outbound_messages' => 'publish_posts',
        'flamingo_delete_outbound_message'=> 'publish_posts',
    );

    // If the capability being checked belongs to Flamingo, swap it out
    if ( isset( $meta_caps[ $cap ] ) ) {
        $caps = array_diff( $caps, array_keys( $meta_caps ) );
        $caps[] = $meta_caps[ $cap ];
    }

    return $caps;
}

// Only hook this filtering system if Flamingo is actually installed and active
add_action( 'plugins_loaded', function() {
    if ( function_exists( 'flamingo_init' ) || class_exists( 'Flamingo_Inbound_Message' ) ) {
        remove_filter( 'map_meta_cap', 'flamingo_map_meta_cap' );
        add_filter( 'map_meta_cap', 'my_theme_custom_flamingo_caps', 9, 4 );
    }
});

/**
 * Safely allow Administrators to upload and preview SVG files
 */
function my_theme_enable_svg_uploads( $mimes ) {
    // Only allow Admins to bypass the restriction
    if ( ! current_user_can( 'administrator' ) ) {
        return $mimes;
    }

    // Explicitly inject the correct mime types
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml'; // Supports compressed SVGs too

    return $mimes;
}
add_filter( 'upload_mimes', 'my_theme_enable_svg_uploads' );

/**
 * Fix SVG thumbnails showing as blank squares in the Media Library grid view
 */
function my_theme_fix_svg_previews( $response, $attachment, $meta ) {
    if ( isset( $response['mime'] ) && $response['mime'] === 'image/svg+xml' ) {
        // Force the display URL to point to the raw SVG file so it renders a thumbnail preview
        $response['sizes'] = array(
            'full' => array(
                'url' => $response['url'],
            ),
        );
    }
    return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'my_theme_fix_svg_previews', 10, 3 );

/**
 * Clean up frontend styles and remove legacy block optimization scripts
 */
function my_theme_cleanup_assets() {
    // Disable global styles rendering inline if you only use theme.json
    wp_dequeue_style( 'global-styles' );
    
    // Remove classic theme fallback styles
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_cleanup_assets', 100 );
