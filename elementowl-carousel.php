<?php

/**
 * Plugin Name: Owl Carousel for Elementor
 * Plugin URI: https://github.com/thenahidul/owl-carousel-elementor
 * Description: Owl carousel plugin addon for Elementor Page Builder. This plugin offers to use popular jQuery plugin
 * Version: 1.0.0
 * Requires at least: 5.0
 * Tested up to: 5.7
 * Requires PHP version: 7.0
 * Elementor tested up to: 3.2.5
 * Elementor Pro tested up to: 3.3.0
 * Author: TheNahidul
 * Author URI: https://www.linkedin.com/in/thenahidul
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: owl-carousel-elementor
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Owl Carousel for Elementor Class
 *
 * The init class that runs the Owl Carousel for Elementor plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 *
 * @since 1.0.0
 */
final class OWCE_Main {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.1.0';
	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';
	/**
	 * Instance
	 *
	 * @since  1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function __construct () {
		// create constants
		$this->define_constants();

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Define useful constants
	 *
	 * @return void
	 */
	public function define_constants () {
		define( "OWCE_VERSION", self::VERSION );
		define( "OWCE_PLUGIN", plugin_basename( __FILE__ ) );
		define( "OWCE_PLUGIN_FILE", __FILE__ );
		define( "OWCE_PLUGIN_PATH", __DIR__ );
		define( "OWCE_PLUGIN_URL", plugins_url( '', OWCE_PLUGIN_FILE ) );
		define( "OWCE_PLUGIN_ASSETS", OWCE_PLUGIN_URL . '/assets' );
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public static function getInstance () {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function i18n () {
		load_plugin_textdomain( 'owl-carousel-elementor' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init () {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, we've passed all validation checks so we can safely include our plugin and assetes
		require_once( 'functions.php' );
		require_once( 'widget.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin () {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'owl-carousel-elementor' ),
			'<strong>' . esc_html__( 'Owl Carousel for Elementor ', 'owl-carousel-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'owl-carousel-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version () {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'owl-carousel-elementor' ),
			'<strong>' . esc_html__( 'Owl Carousel for Elementor ', 'owl-carousel-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'owl-carousel-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version () {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'owl-carousel-elementor' ),
			'<strong>' . esc_html__( 'Owl Carousel for Elementor ', 'owl-carousel-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'owl-carousel-elementor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

/**
 * Initilizing the main plugin \OWCE_Main
 *
 * @return OWCE_Main;
 */
function owce_main () {
	return OWCE_Main::getInstance();
}

// The Plugin kicks-off
owce_main();
