<?php
if ( ! empty( $settings ) ) :
	if ( $settings['items_list'] ) :
		foreach ( $settings['items_list'] as $item ) : //owce_debug($item); ?>
			<div class="item carousel-item-<?php echo esc_attr( $item['_id'] ); ?>">
				<?php
				if ( ! $settings[$field_prefix . 'image_hide'] ) : ?>
					<div class="owl-thumb">
						<?php
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
				<?php endif;

				if( ! $settings[$field_prefix . 'title_hide'] ) {
					echo owce_get_text_with_tag($this, $settings[$field_prefix . 'title_tag'], $item['item_title'], ['class' => 'owl-title', 'data-setting' => 'item_title']);
				}

				if( ! $settings[$field_prefix . 'subtitle_hide'] ) {
					echo owce_get_text_with_tag($this, $settings[$field_prefix . 'subtitle_tag'], $item['item_subtitle'], ['class' => 'owl-subtitle', 'data-setting' => 'item_subtitle']);
				}
				?>
			</div>
		<?php
		endforeach;
	endif;
endif;
