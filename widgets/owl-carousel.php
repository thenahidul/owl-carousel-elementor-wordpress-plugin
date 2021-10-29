<?php

namespace OwlCarouselElementor\Widgets;

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

/**
 * Owl Carousel
 *
 * Elementor widget for 'Owl Carousel for Elementor'
 *
 * @since 1.0.0
 */
class Owl_Carousel extends Widget_Base {

	/**
	 * Control Settings field prefix
	 *
	 * @since 1.0.0
	 */
	const FIELD_PREFIX = 'carousel_';

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name () {
		return 'owl-carousel-elementor';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Owl Carousel for Elementor', 'owl-carousel-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'eicon-slides';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since  2.1.0
	 * @access public
	 *
	 */
	public function get_keywords () {
		return [ 'testimonial', 'slider', 'carousel', 'owl', 'owlcarousel' ];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories () {
		return [ 'general' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends () {
		return [ 'owce-carousel', 'owce-custom' ];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @return array Widget styles dependencies.
	 * @since  1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_style_depends () {
		return [ 'owce-carousel', 'owce-custom', 'owce-animate' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls () {

		$field_prefix = self::FIELD_PREFIX;

		$this->start_controls_section(
			$field_prefix . 'content',
			[
				'label' => __( 'Items', 'owl-carousel-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);

		$layouts = [
			'basic'       => __( 'Basic', 'owl-carousel-elementor' ),
			'image'       => __( 'Image Only', 'owl-carousel-elementor' ),
			'testimonial' => __( 'Testimonial', 'owl-carousel-elementor' ),
			'team'       => __( 'Team', 'owl-carousel-elementor' ),
			'four'        => __( 'Four', 'owl-carousel-elementor' )
		];

		owce_select_control ( $this, 'layout', 'Layout',['options' => $layouts, 'default' =>'basic', 'classes' => 'js_carousel_layout', /*'selector' => 'no-refresh'*/  ] );


		$this->start_controls_tabs(
			$field_prefix . 'items_tabs'
		);

		$this->start_controls_tab(
			$field_prefix . 'items_tab',
			[
				'label' => __( 'Items', 'owl-carousel-elementor' ),
			]
		);

		$repeater = new Repeater();

		// owce_text_control( $repeater, 'item_title', 'Title', [ 'selectors' => [ '' ], 'classes' => 'js_repeater_single js_item_title js_hide_on_layout_basic js_hide_on_layout_image' ] );

		owce_text_control( $repeater, 'item_title', 'Title', [ 'selectors' => [ '' ], 'classes' => 'js_repeater_single js_hide_on_layout_basic js_hide_on_layout_image' ] );

		owce_text_control( $repeater, 'item_subtitle', 'Sub title', [ 'selectors' => [ '' ], 'classes' => 'js_repeater_single js_hide_on_layout_basic js_hide_on_layout_image', ] );

		owce_text_control( $repeater, 'item_content', 'Content', [ 'type' => 'textarea', 'selectors' => [ '' ], 'classes' => 'js_repeater_single js_hide_on_layout_image js_hide_on_layout_team' ] );

		owce_image_control( $repeater, 'item_image', 'Upload photo', [ 'selectors' => [ '' ], 'classes' => 'js_repeater_single' ] );

		// pro
		// $repeater->add_group_control(
		// 	Group_Control_Image_Size::get_type(),
		// 	[
		// 		'name' => 'thumbnail', // can be custom name like my_img_size
		// 		'exclude' => [ 'custom' ],
		// 		'include' => [],
		// 		'default' => 'medium'
		// 	]
		// );
		// pro

		owce_slider_control( $repeater, 'item_rating', 'Rating', [ 'property' => 'no-selector', 'size_units' => [ '' ], 'range' => [ '' => [ 'min' => 1, 'max' => 5, 'step' => 1 ] ], 'default' => [ 'unit' => '', 'size' => 5 ], 'classes' => 'js_repeater_single js_hide_on_layout_basic js_hide_on_layout_image' ] );

		// temp
		//		owce_debug( $this->get_controls( 'carousel_layout' ) );
		//		owce_debug( $this->get_data() );

		$this->add_control(
			'items_list',
			[
				'label'       => __( 'Caoursel items', 'owl-carousel-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'classes'     => 'js_items_list_repeater',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'item_title' => __( 'Item #1', 'owl-carousel-elementor' )
					],
					[
						'item_title' => __( 'Item #2', 'owl-carousel-elementor' )
					],
					[
						'item_title' => __( 'Item #3', 'owl-carousel-elementor' )
					]
				],
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->end_controls_tab(); // $field_prefix . 'items_tab'

		$this->start_controls_tab(
			$field_prefix . 'items_options',
			[
				'label' => __( 'Options', 'owl-carousel-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => $field_prefix . 'thumbnail',
				'exclude' => [ 'custom' ], // pro
				'default' => 'owl_elementor_thumbnail'
			]
		);

		owce_number_control( $this, 'items_count', 'Number of Items', [ 'responsive' => true, 'default' => 3, 'tablet_default' => 2, 'mobile_default' => 1, 'min' => 1, 'max' => 12, 'step' => 1, 'description' => 'The number of items visible on the screen at a time' ] );

		owce_switcher_control( $this, 'autoplay', 'Autoplay' );

		owce_number_control( $this, 'autoplay_timeout', 'Autoplay timeout', [ 'default' => 5000, 'step' => 50, 'condition' => [ $field_prefix . 'autoplay' => '1', ] ] );

		owce_switcher_control( $this, 'autoplay_hover_pause', 'Autoplay pause on hover', [ 'default' => false, 'condition' => [ $field_prefix . 'autoplay' => '1' ] ] );

		owce_switcher_control( $this, 'rewind', 'Rewind', [ 'description' => 'Go backwards when the boundary has reached', 'default' => false, 'condition' => [ $field_prefix . 'enable_loop!' => '1' ] ] );

		owce_switcher_control( $this, 'enable_loop', 'Loop', [ 'description' => 'Infinity loop. Duplicate last and first items to get loop illusion.', 'responsive' => true, 'default' => false, 'condition' => [ $field_prefix . 'rewind!' => '1' ] ] );

		owce_number_control( $this, 'smart_speed', 'Slide speed', [ 'default' => 500, 'step' => 50, 'description' => 'Duration of change of per slide' ] );

		owce_switcher_control( $this, 'show_nav', 'Show next/prev', [ 'responsive' => true, 'default' => false] );

		owce_switcher_control( $this, 'show_dots', 'Show dots', [ 'responsive' => true ] );

		owce_switcher_control( $this, 'mouse_drag', 'Mouse drag' );
		owce_switcher_control( $this, 'touch_drag', 'Touch drag' );

		owce_switcher_control( $this, 'lazyLoad', 'LazyLoad', [ 'default' => false ] );

		owce_switcher_control( $this, 'lightbox', 'Lightbox', [ 'description' => 'Enable lightbox effect to images', 'default' => false ] );

		owce_switcher_control( $this, 'lightbox_title', 'Lightbox title', [ 'description' => 'Show image title in the lightbox mode. <a target="_blank" href="https://prnt.sc/15sqxgc">see screenshot</a>', 'default' => true, 'condition' => [ $field_prefix . 'lightbox' => '1' ] ] );

		owce_switcher_control( $this, 'lightbox_description', 'Lightbox Description', [ 'description' => 'Show image description in the lightbox mode. <a target="_blank" href="https://prnt.sc/15sqxgc">see screenshot</a>', 'default' => false, 'condition' => [ $field_prefix . 'lightbox' => '1' ] ] );

		owce_switcher_control( $this, 'lightbox_editor_mode', 'Disable Lightbox in Editor', [ 'description' => 'Disable open image in lightbox in the editor mode', 'default' => true, 'condition' => [ $field_prefix . 'lightbox' => '1' ] ] );

		owce_switcher_control( $this, 'auto_height', 'Auto height', [ 'default' => false, 'description' => 'Works only with 1 item on screen. Calculate all visible items and change height according to heighest item.', 'condition' => [ $field_prefix . 'items_count' => 1 ] ] );

		$this->end_controls_tab(); // $field_prefix . 'items_options'

		$this->end_controls_tabs(); // $field_prefix . 'items_tabs'

		$this->end_controls_section(); // $field_prefix . 'content'

		owce_common_controls_section( $this, 'items_single', 'Items', '.item', [ 'align' => true, 'tag' => false, 'color' => false, 'border' => true, 'border_radius' => true, 'typography' => false, 'hide' => false, 'margin' => false, 'padding' => true, 'gap' => 'right', 'background' => true, 'background_type' => [ 'classic' ], 'background_exclude' => [ 'image' ] ] );

		owce_common_controls_section( $this, 'title', 'Title', '.owl-title', [ 'default_tag' => 'h3', 'condition' => [ $field_prefix . 'layout' => [ 'testimonial', 'team' ] ] ] );

		owce_common_controls_section( $this, 'subtitle', 'Sub Title', '.owl-subtitle', [ 'default_tag' => 'h4', 'condition' => [ $field_prefix . 'layout' => [ 'testimonial', 'team' ] ] ] );

		owce_common_controls_section( $this, 'content', 'Content', '.owl-content', [ 'condition' => [ $field_prefix . 'layout' => [ 'basic', 'testimonial' ] ] ] );

		owce_common_controls_section( $this, 'image', 'Image', '.owl-thumb img', [ 'tag' => false, 'color' => false, 'border_radius' => true, 'typography' => false, 'size' => true ] );

		owce_common_controls_section( $this, 'icon', 'Rating icon', '.owl-rating-icon i', [ 'icon' => true, 'font_size' => true, 'tag' => false, 'typography' => false, 'condition' => [ $field_prefix . 'layout' => [ 'testimonial' ] ] ] );

		owce_common_controls_section( $this, 'navigation', 'Navigation', '.owl-nav i', [ 'tag' => false, 'background' => true, 'background_type' => [ 'classic' ], 'background_exclude' => [ 'image' ], 'typography' => false, 'hide' => false ] );

		owce_common_controls_section( $this, 'dots', 'Dots', '.owl-dot span', [ 'tag' => false, 'background' => true, 'background_type' => [ 'classic' ], 'background_exclude' => [ 'image' ], 'typography' => false, 'hide' => false ] );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 *
	 * @access protected
	 */
	protected function render () {
		$settings = $this->get_settings_for_display();
		// owce_debug($settings);

		$field_prefix = self::FIELD_PREFIX;

		$layout   = $this->get_owl_settings( 'layout' );
		$show_nav = $this->get_owl_settings( 'show_nav' );

		$settings_js = [
			'field_prefix'         => $field_prefix,
			'layout'               => $layout,
			'items_count'          => $this->get_owl_settings( 'items_count' ),
			'items_count_tablet'   => $this->get_owl_settings( 'items_count_tablet' ),
			'items_count_mobile'   => $this->get_owl_settings( 'items_count_mobile' ),
			'margin'               => $this->get_owl_settings( 'items_single_gap' )['size'],
			'margin_tablet'        => $this->get_owl_settings( 'items_single_gap_tablet' )['size'],
			'margin_mobile'        => $this->get_owl_settings( 'items_single_gap_mobile' )['size'],
			'nav'                  => $show_nav,
			'nav_tablet'           => $this->get_owl_settings( 'show_nav_tablet' ),
			'nav_mobile'           => $this->get_owl_settings( 'show_nav_mobile' ),
			'dots'                 => $this->get_owl_settings( 'show_dots' ),
			'dots_tablet'          => $this->get_owl_settings( 'show_dots_tablet' ),
			'dots_mobile'          => $this->get_owl_settings( 'show_dots_mobile' ),
			'autoplay'             => $this->get_owl_settings( 'autoplay' ),
			'autoplay_timeout'     => $this->get_owl_settings( 'autoplay_timeout' ),
			'autoplay_hover_pause' => $this->get_owl_settings( 'autoplay_hover_pause' ),
			'loop'                 => $this->get_owl_settings( 'enable_loop' ),
			'loop_tablet'          => $this->get_owl_settings( 'enable_loop_tablet' ),
			'loop_mobile'          => $this->get_owl_settings( 'enable_loop_mobile' ),
			'smart_speed'          => $this->get_owl_settings( 'smart_speed' ),
			'lazyLoad'             => $this->get_owl_settings( 'lazyLoad' ),
			'auto_height'          => $this->get_owl_settings( 'auto_height' ),
			'mouse_drag'           => $this->get_owl_settings( 'mouse_drag' ),
			'touch_drag'           => $this->get_owl_settings( 'touch_drag' ),
		];

		$this->add_render_attribute(
			'carousel-options',
			[
				'id'           => 'owce-carousel-' . $this->get_id(),
				'class'        => 'owl-carousel owl-theme js-owce-carousel owce-carousel owce-carousel-' . $layout,
				'data-options' => [ wp_json_encode( $settings_js ) ]
			]
		);

		$css_classes = $show_nav != '1' ? 'owce-carousel-no-nav' : '';

		echo "<div class='js-owce-carousel-container owce-carousel-container " . esc_attr( $css_classes ) . "'>";
		echo "<div " . $this->get_render_attribute_string( 'carousel-options' ) . ">";

		require OWCE_PLUGIN_PATH . '/widgets/views/layout-' . $layout . '.php';

		echo "</div></div>";
	}

	/**
	 * Get Settings.
	 *
	 * @param string $key required. The key of the requested setting.
	 *
	 * @return string A single value.
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function get_owl_settings ( $key ) {
		return $this->get_settings( self::FIELD_PREFIX . $key );
	}
}
