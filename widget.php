<?php

namespace OwlCarouselElementor;

defined( 'ABSPATH' ) || exit;

/**
 * Class Widget
 *
 * @since 1.0.0
 */
class Widget {

	/**
	 * widget_scripts
	 *
	 * Load required widget core files
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function widget_scripts () {
		// JS
		wp_register_script( 'owce-carousel', OWCE_PLUGIN_ASSETS . '/js/owl.carousel.min.js', [ 'jquery' ], '2.3.4', true );

		wp_register_script( 'owce-custom', OWCE_PLUGIN_ASSETS . '/js/custom.js', [ 'jquery', 'owce-carousel', 'elementor-frontend' ], time(), true );

		// CSS
		wp_register_style( 'owce-carousel', OWCE_PLUGIN_ASSETS . '/css/owl.carousel.min.css', null, '2.3.4' );

		wp_register_style( 'owce-custom',  OWCE_PLUGIN_ASSETS . '/css/custom.css', null, time() );

		wp_register_style( 'owce-animate', OWCE_PLUGIN_ASSETS . '/css/animate.min.css', null, '3.7.0' );

		// wp_register_style( 'owce-basic',  OWCE_PLUGIN_ASSETS . '/css/layout/basic.css', null, time() );
		// wp_register_style( 'owce-testimonial',  OWCE_PLUGIN_ASSETS . '/css/layout/testimonial.css', null, time() );
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts() {
		wp_enqueue_script( 'owce-editor', OWCE_PLUGIN_ASSETS . '/js/editor.js', [ 'jquery', 'elementor-editor' ], time(), true );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function include_widgets_files () {
		require_once( __DIR__ . '/widgets/owl-carousel.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function register_widgets () {
		// It is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Owl_Carousel() );
	}

	/**
	 *  Widget class constructor
	 *
	 * Register Widget action hooks and filters
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function __construct () {
		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );
	}
}

/**
 * Initilizing \OwlCarouselElementor\Widget
 *
 * @return Widget;
 */
new Widget();
