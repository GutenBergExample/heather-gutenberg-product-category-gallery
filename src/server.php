<?php
function heather_register_block() {

	// Only load if Gutenberg is available.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type('cgb/heather', array(
		'render_callback' => 'heather_render_callback',
			'attributes' => array(
				'numberCategories' => array(
					'type' 		=> 'number',
					'default' => 4
				),
				'blockTitle' => array(
					'type'	=> 'string',
					'default'	=> 'Browse our products',
				)
			)
		)
	);
}

add_action('init', 'heather_register_block');

function heather_render_callback( array $attributes ){

	$numberCategories	= $attributes[ 'numberCategories' ];
	$blockTitle	= $attributes[ 'blockTitle' ];

	$args = array(
		'number' 		 => $numberCategories,
		'orderby' 	 => 'name',
		'order'   	 => 'ASC',
		'parent'  	 => 0,
		'taxonomy' 	 => 'product_cat',
		'hide_empty' => true,
		'exclude'		 => '15' // dont show uncategorized
	);

	$categories = get_categories( $args );

	if ( empty( $categories ) ) {
			return 'no properties found';
	}

	$container = '<div class="wp-block-cgb-heather">';

	$listContainer = '<ul><h6 class="heather-block-title">'.$blockTitle.'</h6>';
	$imageContainer = '<div>';

	$loop = 0;

	foreach ($categories as $key => $category) {

		$id	= $category->term_id;
		$name	= $category->cat_name;
		$thumb_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
		$thumb_src = wp_get_attachment_url($thumb_id, 'square-thumb') ? wp_get_attachment_url($thumb_id, 'square-thumb') : wp_get_attachment_url($thumb_id, 'woocommerce_single'); 
		$image	= $thumb_src ? $thumb_src : wc_placeholder_img_src();
		$link = get_category_link($id);

		$class = 'heather-filter';

		if($loop == 0) {
				$first = array(
				'name'			=> $name,
				'thumb_src'	=> $image
			);
			$class .= ' active';
		}

		$listContainer .= '
			<li><a href="'.$link.'" class="'.$class.'" data-src="'.$image.'">'.$name.'</a></li>
		';

		$loop++;
	}

	$imageContainer .= '<div class="heather-result"><img class="heather-result__img" alt="'.$first['name'].'" src="'.$first['thumb_src'].'" /></div>';

	return "{$container}{$listContainer}</ul>{$imageContainer}</div></div>";
	

}



	


// 	}
	
