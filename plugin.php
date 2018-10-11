<?php
/**
 * Plugin Name: Heather - Woocommerce Categories Gallery
 * Plugin URI: https://github.com/gubbigubbi
 * Description: Show beautiful project galleries in your editor
 * Author: gubbigubbi
 * Author URI: https://breezydesigns.com.au/
 * Version: 0.2
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
