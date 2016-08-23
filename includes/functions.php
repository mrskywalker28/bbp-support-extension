<?php
/**
 * The bbPress Support Extension Plugin
 * Convert BBPress Forums into full fledges support system.
 * @package bbPress-support-extension
 * @subpackage Main
 */
if ( !defined( 'ABSPATH' ) ) exit;


function bbpse_support_forum_dropdown($args=''){
	echo bbpse_get_support_forum_dropdown( $args );
}

function bbpse_get_support_forum_dropdown($args = ''){

	// Backpat for handling passing of a forum ID
		if ( is_int( $args ) ) {
			$forum_id = (int) $args;
			$args     = array();
		} else {
			$forum_id = 0;
		}

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'select_id'    => 'bbpse_forum_status',
			'tab'          => bbp_get_tab_index(),
			'forum_id'     => $forum_id,
			'selected'     => false
		), 'bbpse_forum_status_select' );

		// No specific selected value passed
		if ( empty( $r['selected'] ) ) {

			// Post value is passed
			if ( bbp_is_post_request() && isset( $_POST[ $r['select_id'] ] ) ) {
				$r['selected'] = $_POST[ $r['select_id'] ];

			// No Post value was passed
			} else {

				// Edit topic
				if ( bbp_is_forum_edit() ) {
					$r['forum_id'] = bbp_get_forum_id( $r['forum_id'] );
					$r['selected'] = bbpse_get_forum_status( $r['forum_id'] );

				// New topic
				} else {
					$r['selected'] = bbpse_get_public_status_id();
				}
			}
		}

		// Used variables
		$tab = ! empty( $r['tab'] ) ? ' tabindex="' . (int) $r['tab'] . '"' : '';

		// Start an output buffer, we'll finish it after the select loop
		ob_start(); ?>

		<select name="<?php echo esc_attr( $r['select_id'] ) ?>" id="<?php echo esc_attr( $r['select_id'] ) ?>_select"<?php echo $tab; ?>>

			<?php foreach ( bbpse_get_forum_statuses() as $key => $label ) : ?>

				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $r['selected'] ); ?>><?php echo esc_html( $label ); ?></option>

			<?php endforeach; ?>

		</select>

		<?php

		// Return the results
		return apply_filters( 'bbpse_get_support_forum_dropdown', ob_get_clean(), $r );
}


function bbpse_get_forum_status($id){
	
	$status = get_post_meta($id,'bbpse_forum_status',true);
	
	if(empty($status))
		return '';

	return $status;
}

function bbpse_get_public_status_id($id){
	return '';
}

function bbpse_get_forum_statuses(){
	return apply_filters('bbpse_forum_status',array(
		''=>_x('Disable','Support Forum status setting','bbpse'),
		'1'=>_x('Enable','Support Forum status setting','bbpse'),
	));
}



function bbpse_topic_status_dropdown($args=''){
	echo bbpse_get_topic_status_dropdown( $args );
}

function bbpse_get_topic_status_dropdown($args = ''){

	// Backpat for handling passing of a forum ID
		if ( is_int( $args ) ) {
			$topic_id = (int) $args;
			$args     = array();
		} else {
			$forum_id = 0;
		}

		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'select_id'    => 'bbpse_topic_status',
			'tab'          => bbp_get_tab_index(),
			'topic_id'     => $topic_id,
			'selected'     => false
		), 'bbpse_topic_status_select' );

		// No specific selected value passed
		if ( empty( $r['selected'] ) ) {

			// Post value is passed
			if ( bbp_is_post_request() && isset( $_POST[ $r['select_id'] ] ) ) {
				$r['selected'] = $_POST[ $r['select_id'] ];

			// No Post value was passed
			} else {

				// Edit topic
				if ( bbp_is_forum_edit() ) {
					$r['topic_id'] = bbp_get_forum_id( $r['topic_id'] );
					$r['selected'] = bbpse_get_topic_status( $r['topic_id'] );

				// New topic
				} else {
					$r['selected'] = bbpse_get_public_topic_status_id();
				}
			}
		}

		// Used variables
		$tab = ! empty( $r['tab'] ) ? ' tabindex="' . (int) $r['tab'] . '"' : '';

		// Start an output buffer, we'll finish it after the select loop
		ob_start(); ?>

		<select name="<?php echo esc_attr( $r['select_id'] ) ?>" id="<?php echo esc_attr( $r['select_id'] ) ?>_select"<?php echo $tab; ?>>

			<?php foreach ( bbpse_get_topic_statuses() as $key => $label ) : ?>

				<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $r['selected'] ); ?>><?php echo esc_html( $label ); ?></option>

			<?php endforeach; ?>

		</select>

		<?php

		// Return the results
		return apply_filters( 'bbpse_get_support_topic_dropdown', ob_get_clean(), $r );
}


function bbpse_get_topic_status($id){
	
	$status = get_post_meta($id,'bbpse_topic_status',true);
	
	if(empty($status))
		return '';

	return $status;
}

function bbpse_get_public_topic_status_id($id){
	return '';
}

function bbpse_get_topic_statuses(){
	return apply_filters('bbpse_forum_status',array(
		''=>_x('Unresolved','Support Topic status setting','bbpse'),
		'1'=>_x('Resolved','Support Topic status setting','bbpse'),
	));
}