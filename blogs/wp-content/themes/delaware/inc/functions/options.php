<?php
/**
 * Get post meta
 *
 * @since  1.0
 *
 * @param  string $key
 * @param  array $args
 * @param  int $post_id
 *
 * @return mixed
 */
function delaware_get_meta( $key, $args = array(), $post_id = null ) {
	if ( function_exists( 'rwmb_meta' ) ) {
		return rwmb_meta( $key, $args, $post_id );
	}

	/**
	 * Base on Meta Box plugin function
	 */
	$post_id = empty($post_id) ? get_the_ID() : $post_id;
	$args = wp_parse_args( $args, array('type' => 'text',) );

	// Set 'multiple' for fields based on 'type'
	if ( !isset($args['multiple']) ) {
		$args['multiple'] = in_array( $args['type'], array('checkbox_list', 'file', 'file_advanced', 'image', 'image_advanced', 'plupload_image', 'thickbox_image') );
	}

	$meta = get_post_meta( $post_id, $key, !$args['multiple'] );

	// Get uploaded files info
	if ( in_array( $args['type'], array('file', 'file_advanced') ) ) {
		if ( is_array( $meta ) && !empty($meta) ) {
			$files = array();
			foreach ( $meta as $id ) {
				$path = get_attached_file( $id );
				$files[$id] = array('ID' => $id, 'name' => basename( $path ), 'path' => $path, 'url' => wp_get_attachment_url( $id ), 'title' => get_the_title( $id ),);
			}
			$meta = $files;
		}
	} // Get uploaded images info
	elseif ( in_array( $args['type'], array('image', 'plupload_image', 'thickbox_image', 'image_advanced') ) ) {
		global $wpdb;

		$meta = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = '%s' ORDER BY meta_id ASC", $post_id, $key ) );

		if ( is_array( $meta ) && !empty($meta) ) {
			$images = array();
			foreach ( $meta as $id ) {
				$images[$id] = fastlat_get_image_info( $id, $args );
			}
			$meta = $images;
		}
	} // Get terms
	elseif ( 'taxonomy_advanced' == $args['type'] ) {
		if ( !empty($args['taxonomy']) ) {
			$term_ids = array_map( 'intval', array_filter( explode( ',', $meta . ',' ) ) );

			// Allow to pass more arguments to "get_terms"
			$func_args = wp_parse_args( array('include' => $term_ids, 'hide_empty' => false,), $args );
			unset($func_args['type'], $func_args['taxonomy'], $func_args['multiple']);
			$meta = get_terms( $args['taxonomy'], $func_args );
		} else {
			$meta = array();
		}
	} // Get post terms
	elseif ( 'taxonomy' == $args['type'] ) {
		$meta = empty($args['taxonomy']) ? array() : wp_get_post_terms( $post_id, $args['taxonomy'] );
	}

	return $meta;
}