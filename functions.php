<?php

use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

function owce_debug ( $data, $die = false ) {
	echo '<pre>';
	print_r( $data );
	echo '</pre>';
	$die ? die : null;
}

function owce_setup_theme() {
    add_image_size( 'owl_elementor_thumbnail', 600, 450 );
    add_image_size( 'owl_elementor_team', 350, 450, true );
    add_image_size( 'owl_elementor_testimonial', 100, 100, true );
}
add_action( 'init', 'owce_setup_theme' );

function owce_get_text_with_tag ( $widget, $html_tag, $key, $attrs = [] ) {
	$widget->add_render_attribute( $key, array_map( 'esc_attr', $attrs ));
	return sprintf( '<%1$s %2$s>%3$s</%1$s>', esc_html($html_tag),
		$widget->get_render_attribute_string( $key ), $key );
}

function owce_get_img_with_size ( $settings, $img_size, $img_key, $widget = null, $lightbox = [] ) {

	$defaults = [
		'show_lightbox'                => false,
		'show_lightbox_title'          => true,
		'show_lightbox_description'    => false,
		'disable_lightbox_editor_mode' => true
	];

	$options = wp_parse_args( $lightbox, $defaults );

	extract( $options );

	if ( $widget && $show_lightbox ) {

		$link = [
			'url' => $settings[$img_key]['url'],
			'id'  => $settings[$img_key]['id']
		];

		$img_id   = $link['id'];
		$img_link = $link['url'];

		$widget->add_link_attributes( $img_link, $link );
		$widget->add_lightbox_data_attributes( $img_link, $img_id, 'yes', $widget->get_id() );

		// enable/disable click on image to open lightbox in edit mode
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			if ( $disable_lightbox_editor_mode ) {
				$widget->add_render_attribute( $img_link, [ 'class' => 'js-elementor-not-clickable' ] );
			}
			else {
				$widget->add_render_attribute( $img_link, [ 'class' => 'elementor-clickable' ] );
			}
		}

		// empty title value
		if ( ! $show_lightbox_title ) {
			$widget->add_render_attribute( $img_link, [ 'data-elementor-lightbox-title' => '' ], null, true );
		}

		// empty description value
		if ( ! $show_lightbox_description ) {
			$widget->add_render_attribute( $img_link, [ 'data-elementor-lightbox-description' => '' ], null, true );
		}

		$img_html = "<a " . $widget->get_render_attribute_string( $img_link ) . ">";
		$img_html .= Group_Control_Image_Size::get_attachment_image_html( $settings, $img_size, $img_key );
		$img_html .= "</a>";

		return $img_html;
	}

	return Group_Control_Image_Size::get_attachment_image_html( $settings, $img_size, $img_key );
}

function owce_common_controls_section ( $widget, $field, $label, $selector, $options = [], $tab = '' ) {

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$defaults = [
		'hide'               => true,
		'align'              => false,
		'tag'                => true,
		'default_tag'        => 'div',
		'color'              => true,
		'background'         => false,
		'background_type'    => [ 'classic', 'gradient', 'video' ],
		'background_exclude' => [],
		'padding'            => false,
		'margin'             => true,
		'gap'                => false,
		'typography'         => true,
		'font_size'          => false,
		'border'             => false,
		'border_radius'      => false,
		'icon'               => false,
		'size'               => false,
		'condition'          => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$tab_section = $tab == 'tab' ? Controls_Manager::TAB_CONTENT : Controls_Manager::TAB_STYLE;

	$widget->start_controls_section(
		$field_prefix . 'style_' . $field,
		[
			'label'     => __( $label, 'owl-carousel-elementor' ),
			'tab'       => $tab_section,
			'condition' => $condition
		]
	);

	if ( $hide ) {
		$hide_options            = $options;
		$hide_options['default'] = false;
		$_label                  = owce_key_value_exists( $options, 'hide_label', 'Hide' );
		owce_switcher_control( $widget, $field . '_hide', $_label, $hide_options );
	}

	if ( $icon ) {
		$_label = owce_key_value_exists( $options, 'icon_label', 'Icon' );
		owce_icons_control( $widget, $field, $_label );
	}

	if ( $tag ) {
		$_label = owce_key_value_exists( $options, 'tag_label', 'HTML Tag' );
		$_tags = [
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div',
			'span' => 'span',
			'p'    => 'p',
		];
		owce_select_control ( $widget, $field . '_tag', $_label, ['options' => $_tags, 'default' => $default_tag  ] );
	}

	if ( $color ) {
		$_label = owce_key_value_exists( $options, 'color_label', 'Color' );
		owce_color_control( $widget, $field . '_color', $_label, $selector );
	}

	if ( $background ) {
		$_label = owce_key_value_exists( $options, 'background_label', 'Background' );
		$widget->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => $field_prefix . $field . '_background',
				'label'    => __( $_label, 'owl-carousel-elementor' ),
				'exclude'  => $background_exclude,
				'types'    => $background_type,
				'selector' => '{{WRAPPER}} ' . $selector,
			]
		);
	}

	if ( $typography ) {
		$_label = owce_key_value_exists( $options, 'typography_label', 'Typography' );
		$widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $field_prefix . $field . '_typography',
				'label'    => __( $_label, 'owl-carousel-elementor' ),
				'selector' => '{{WRAPPER}} ' . $selector
			]
		);
	}

	if ( $gap ) {
		$default_gap = null;
		$_label      = owce_key_value_exists( $options, 'gap_label', 'Gap' );
		owce_slider_control( $widget, $field . '_gap', $_label, [ 'responsive' => true, 'property' => 'no-selector', 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'max' => 100 ] ], 'default' => [ 'size' => 10 ] ] );
	}

	if ( $font_size ) {
		$_label = owce_key_value_exists( $options, 'font_size_label', 'Size' );
		owce_slider_control( $widget, $field . '_font_size', $_label, [ 'property' => 'font-size', 'selector' => $selector ] );
	}

	if ( $align ) {
		$_label = owce_key_value_exists( $options, 'align_label', 'Align' );
		owce_choose_control ( $widget, $field . '_align', $_label, ['selector' => $selector] );
	}

	if ( $margin ) {
		$_label = owce_key_value_exists( $options, 'margin_label', 'Margin' );
		owce_dimension_control($widget, $field . '_margin', $_label, [ 'selector' => $selector ]);
	}

	if ( $padding ) {
		$_label = owce_key_value_exists( $options, 'padding_label', 'Padding' );
		owce_dimension_control($widget, $field . '_padding', $_label, [ 'type' => 'padding', 'selector' => $selector ]);
	}

	if ( $size ) {
		// width
		$_label = owce_key_value_exists( $options, 'width_label', 'Width' );
		owce_slider_control( $widget, $field . '_width', $_label, [ 'selector' => $selector ] );

		// height
		$_label = owce_key_value_exists( $options, 'height_label', 'Height' );
		owce_slider_control( $widget, $field . '_height', $_label, [ 'property' => 'height', 'size_units' => [ 'px' ], 'selector' => $selector, 'description' => 'in px only' ] );
	}

	if ( $border ) {
		$_label = owce_key_value_exists( $options, 'border_label', 'Border' );
		$widget->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $field_prefix . $field . '_border',
				'label'    => __( $_label, 'owl-carousel-elementor' ),
				'selector' => '{{WRAPPER}} ' . $selector,
			]
		);
	}

	if ( $border_radius ) {
		$_label = owce_key_value_exists( $options, 'border_radius_label', 'Border Radius' );

		// owce_slider_control( $widget, $field . '_border_radius', $_label, [ 'property' => 'border-radius', 'range' => [ 'px' => [ 'max' => 100 ], '%' => [ 'max' => 50 ] ], 'selector' => $selector ] );

		owce_dimension_control($widget, $field . '_border_radius', $_label, [ 'type' => 'border-radius', 'selector' => $selector ]);
	}

	$widget->end_controls_section();
}

function owce_text_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'type'        => 'text',
		'input_type'  => 'text', // text,email,number etc
		'description' => '',
		'show_label'  => true,
		'label_block' => true,
		'default'     => '',
		'placeholder' => '',
		'classes'     => '',
		'selectors'   => '',
		'condition'   => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( $default, 'owl-carousel-elementor' ),
		'show_label'  => $show_label,
		'label_block' => $label_block,
		'placeholder' => __( $placeholder, 'owl-carousel-elementor' ),
		'classes'     => $classes,
		'selectors'   => $selectors,
		'condition'   => $condition
	];

	if ( $type == 'text' ) {
		$args['input_type'] = $input_type;
	}

	if ( $type == 'textarea' ) {
		$args['type'] = Controls_Manager::TEXTAREA;
	}

	if ( $type == 'wysiwyg' ) {
		$args['type'] = Controls_Manager::WYSIWYG;
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';
	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

function owce_image_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'description' => '',
		'classes'     => '',
		'condition'   => '',
		'default'     => [
			'url' => Utils::get_placeholder_image_src()
		],
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		[
			'label'       => __( $label, 'owl-carousel-elementor' ),
			'description' => __( $description, 'owl-carousel-elementor' ),
			'type'        => Controls_Manager::MEDIA,
			'classes'     => $classes,
			'condition'   => $condition,
			'default'     => $default
		]
	);
}

function owce_dimension_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'type' => 'margin',
		'responsive' => true,
		'description' => '',
		'size_units'  => [ 'px', '%', 'em' ],
		'default'     => [
			'top'      => '',
			'right'    => '',
			'bottom'   => '',
			'left'     => '',
			'isLinked' => true,
		],
		'allowed_dimensions' => 'all',
		'classes'     => '',
		'selector'    => '',
		'condition'   => '',
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'description' => __( $description, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::DIMENSIONS,
		'size_units'  => $size_units,
		'selectors'   => [
			'{{WRAPPER}} ' . $selector => $type . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'allowed_dimensions' => $allowed_dimensions,
		'default'     => $default,
		'classes'     => $classes,
		'condition'   => $condition,
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';
	if ( $responsive ) {
		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	}
	else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

function owce_switcher_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'label_on'       => 'Yes',
		'label_off'      => 'No',
		'return_value'   => '1', // elementor doesn't return boolen true or int 1 after saving.
		'default'        => '1', // Rather it returns string 'true' or 'number'. for exmp: '1'
		'tablet_default' => '1',
		'mobile_default' => '1',
		'condition'      => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'        => __( $label, 'owl-carousel-elementor' ),
		'description'  => __( $description, 'owl-carousel-elementor' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => __( $label_on, 'owl-carousel-elementor' ),
		'label_off'    => __( $label_off, 'owl-carousel-elementor' ),
		'return_value' => $return_value,
		'default'      => $default,
		'condition'    => $condition
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	if ( $responsive ) {
		$args['tablet_default'] = $tablet_default;
		$args['mobile_default'] = $mobile_default;

		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	}
	else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

function owce_number_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'min'            => 1,
		'max'            => null,
		'step'           => 1,
		'default'        => null,
		'tablet_default' => null,
		'mobile_default' => null,
		'condition'      => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'description' => __( $description, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::NUMBER,
		'min'         => $min,
		'max'         => $max,
		'step'        => $step,
		'default'     => $default,
		'condition'   => $condition,
	];

	if ( $responsive ) {
		$args['tablet_default'] = $tablet_default;
		$args['mobile_default'] = $mobile_default;

		$widget->add_responsive_control(
			$widget::FIELD_PREFIX . $field,
			$args
		);
	}
	else {
		$widget->add_control(
			$widget::FIELD_PREFIX . $field,
			$args
		);
	}
}

function owce_color_control ( $widget, $field, $label, $selector ) {
	$widget->add_control(
		$widget::FIELD_PREFIX . $field,
		[
			'label'     => __( $label, 'owl-carousel-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} ' . $selector => 'color: {{VALUE}}'
			]
		]
	);
}

function owce_icons_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'default'   => [ 'library' => 'solid', 'value' => 'fas fa-star' ],
		'condition' => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$widget->add_control(
		$widget::FIELD_PREFIX . $field,
		[
			'label'   => __( $label, 'owl-carousel-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => $default
		]
	);
}

function owce_slider_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'property'       => 'width',
		'size_units'     => [ 'px', '%' ],
		'range'          => [
			'px' => [
				'min'  => 0,
				'max'  => 1920,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'default'        => [
			'unit' => 'px',
			'size' => '',
		],
		'classes'        => '',
		'selector'       => '',
		'tablet_default' => '',
		'mobile_default' => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'description' => __( $description, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::SLIDER,
		'size_units'  => $size_units,
		'range'       => $range,
		'default'     => $default,
		'classes'     => $classes
	];

	if ( $property == 'width' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'width: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'height' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'height: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'font-size' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'font-size: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'border-radius' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'border-radius: {{SIZE}}{{UNIT}};' ];
	}

	// if(!empty($tablet_default)) {
	// 	$args['tablet_default'] = $options['tablet_default'];
	// }

	// if(!empty($mobile_default)) {
	// 	$args['mobile_default'] = $options['mobile_default'];
	// }

	if ( $property == 'no-selector' ) {
		unset( $args['selectors'] );
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';
	if ( $responsive ) {
		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	}
	else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

function owce_choose_control ( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'description'    => '',
		'options' => [
			'left' => [
				'title' => __( 'Left', 'owl-carousel-elementor' ),
				'icon' => 'fa fa-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'owl-carousel-elementor' ),
				'icon' => 'fa fa-align-center',
			],
			'right' => [
				'title' => __( 'Right', 'owl-carousel-elementor' ),
				'icon' => 'fa fa-align-right',
			],
		],
		'default' => '',
		'toggle' => true,
		'classes'        => '',
		'selector'       => ''
	];

	$options = wp_parse_args( $options, $defaults );

	extract( $options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'description' => __( $description, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::CHOOSE,
		'options'  	  => $options,
		'default'     => $default,
		'classes'     => $classes,
		'selectors' => [
			'{{WRAPPER}} ' . $selector => 'text-align: {{VALUE}}'
		]
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

function owce_select_control ( $widget, $field, $label, $_options = [] ) {
	$defaults = [
		'description'    => '',
		'options'		 => [],
		'default'        => '',
		'classes'        => '',
		'selector'       => '',
	];

	$_options = wp_parse_args( $_options, $defaults );

	extract( $_options );

	$args = [
		'label'       => __( $label, 'owl-carousel-elementor' ),
		'description' => __( $description, 'owl-carousel-elementor' ),
		'type'        => Controls_Manager::SELECT,
		'options'	  => $options,
		'default'     => $default,
		'classes'     => $classes,

	];

	if($selector == 'no-refresh') {
		$args['selectors']   = [ '{{WRAPPER}} ' . $selector => '' ];
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}


function owce_render_icons ( $key, $length ) {
	for ( $i = 1; $i <= $length; $i++ ) {
		Icons_Manager::render_icon( $key, [ 'aria-hidden' => 'true' ] );
	}
}

// helpers
function owce_key_value_exists ( $arr, $key, $default ) {
	return ( ! empty( $arr[$key] ) && trim( $arr[$key] ) ) ? $arr[$key] : $default;
}

function owce_get_class_constant ( $class, $name ) {
	if ( is_string( $class ) ) {
		return defined( "$class::$name" );
	}
	else if ( is_object( $class ) ) {
		return defined( get_class( $class ) . "::$name" );
	}
	return false;
}

function owce_generate_random_string($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}


function owce_return_false ( $options = [] ) {
	$arr = [];
	foreach ( $options as $item ) {

		$arr[$item] = false;
	}
	return $arr;
}

// print_r( owce_return_false( [ 'margin', 'padding', 'typography' ] ) );