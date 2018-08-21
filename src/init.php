<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Contants
define( 'HEATHER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * `wp-blocks`: includes block type registration and related functions.
 *
 * @since 1.0.0
 */
function heather_cgb_block_assets() {
	// Styles.
	wp_enqueue_style(
		'heather-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);
} // End function heather_cgb_block_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'heather_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function heather_cgb_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'heather-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'heather-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	);
} // End function heather_cgb_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'heather_cgb_editor_assets' );

/**
 * Enqueue assets for the frontend only
 */
function heather_client_assets() {
	wp_enqueue_script( 'heather-js', plugins_url( 'src/client.js', dirname( __FILE__ ) ), array('jquery'), true );
}

add_action( 'wp_enqueue_scripts', 'heather_client_assets' );

/**
 * Custom REST Points
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'heather/v1', '/getCategories/number/(?P<number>\d+)', array(  // \S+ for slug
			'methods'  => 'GET',
			'callback' => 'get_woocommerce_categories',
	) );
});

function get_woocommerce_categories ( $data ) {

	$args = array(
		'orderby' 	=> 'name',
		'order'   	=> 'ASC',
		'parent'  	=> 0,
		'taxonomy' 	=> 'product_cat',
		'hide_empty' => true,
		'exclude'		 => '15', // dont show uncategorized,
		'number'		 => $data['number'],
	);
	$results = [];

	$categories = get_categories( $args );

	if ( empty( $categories ) ) {
			return 'no properties found';
	}

	foreach ($categories as $key => $category) {

		$thumb_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
		$thumb_src = wp_get_attachment_url($thumb_id, 'full');

		$results['categories'][] = array(
			'id'		=> $category->term_id,
			'name' 	=> $category->cat_name,
			'image'	=> $thumb_src ? $thumb_src : wc_placeholder_img_src()
		);
	}

	return $results;
}

/**
 * Server Side Rendering
 */
require_once( HEATHER_PLUGIN_PATH . './server.php' );
