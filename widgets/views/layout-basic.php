<?php
if ( ! empty( $settings ) ) :
	if ( $settings['items_list'] ) :
		foreach ( $settings['items_list'] as $item ) : ?>
			<div class="item carousel-item-<?php echo esc_attr( $item['_id'] ); ?>">
				<div class="owl-thumb">
					<?php
					// individual (pro)
					// echo owce_get_img_with_size($item, 'thumbnail', 'item_image');

					// push option(image) key in the settings array because owce_get_img_with_size functon will look for that key in the $settings array
					$settings['item_image_temp'] = $item['item_image'];

					echo owce_get_img_with_size(
						$settings, $field_prefix . 'thumbnail',
						'item_image_temp',
						$this,
						[
							'show_lightbox'                => $settings[$field_prefix . 'lightbox'],
							'show_lightbox_title'          => $settings[$field_prefix . 'lightbox_title'],
							'show_lightbox_description'    => $settings[$field_prefix . 'lightbox_description'],
							'disable_lightbox_editor_mode' => $settings[$field_prefix . 'lightbox_editor_mode'],
						]
					);
					?>
				</div>
				<?php
				if ( ! $settings[$field_prefix . 'content_hide'] ) {
					echo owce_get_text_with_tag( $this,  $settings[$field_prefix . 'content_tag'] , $item['item_content'], ['class' => 'owl-content', 'data-setting' => 'item_content'] );
				}
				?>
			</div>
		<?php
		endforeach;
	endif;
endif;
