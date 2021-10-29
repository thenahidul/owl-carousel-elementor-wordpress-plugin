<div class="owl-carousel owl-theme elementowl-carousel-container">
	<h1>Carousel 2</h1>

	<?php
	if ( $settings['items_list'] ) :

		foreach ( $settings['items_list'] as $item ) : ?>

			<div class="item carousel-item-<?php echo esc_attr( $item['_id'] ); ?> ">
				<h3><?php esc_html_e( $item['item_title'] ); ?></h3>
				<div><?php echo wp_kses_post( $item['item_content'] ); ?></div>
			</div>

		<?php
		endforeach;

	endif;

	?>
</div>