<?php
/**
 * Author: Doe
 * Date: 05/12/2017
 * Purpose: Easier Implementation of Events Pre/During/Post
 */

function egw_live_event_metabox() {
  add_meta_box(
    'eduring-event-start-time',      // Unique ID
    esc_html__( 'EGW Plugin: Live Event Changer', 'example' ),    // Title
    'egw_live_event_metabox_callback',   // Callback function
    'ai1ec_event',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}
add_action( 'add_meta_boxes', 'egw_live_event_metabox' );

function egw_live_event_metabox_callback( $post ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'egw_live_events_nonce' ); ?>

	
	<p>
		<p class="row-title">Pre Event: </p>
		<label for="egw-pre-event-start-time"><?php _e( "Start Time: ", 'example' ); ?></label>
		<input type="text" name="egw-pre-event-start-time" id="egw-pre-event-start-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_pre_event_start_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<label for="egw-pre-event-start-time"><?php _e( "End Time: ", 'example' ); ?></label>
		<input type="text" name="egw-pre-event-end-time" id="egw-pre-event-end-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_pre_event_end_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<br />
		<textarea class="egw-pre-event-textarea egw-event-text-area" cols="40" rows="5" name="egw-pre-event-textarea" value=""><?php echo esc_attr( get_post_meta( $post->ID, 'egw_pre_event_textarea_meta_key', true ) ); ?></textarea> 
	</p>
	<p>
		<p class="row-title">During Event: </p>
		<label for="egw-during-event-start-time"><?php _e( "Start Time: ", 'example' ); ?></label>
		<input type="text" name="egw-during-event-start-time" id="egw-during-event-start-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_during_event_start_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<label for="egw-during-event-start-time"><?php _e( "End Time: ", 'example' ); ?></label>
		<input type="text" name="egw-during-event-end-time" id="egw-during-event-end-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_during_event_end_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<br />
		<textarea class="egw-during-event-textarea egw-event-text-area" cols="40" rows="5" name="egw-during-event-textarea" value=""><?php echo esc_attr( get_post_meta( $post->ID, 'egw_during_event_textarea_meta_key', true ) ); ?></textarea> 
	</p>
	<p>
		<p class="row-title">Post Event: </p>
		<label for="egw-post-event-start-time"><?php _e( "Start Time: ", 'example' ); ?></label>
		<input type="text" name="egw-post-event-start-time" id="egw-post-event-start-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_post_event_start_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<label for="egw-post-event-start-time"><?php _e( "End Time: ", 'example' ); ?></label>
		<input type="text" name="egw-post-event-end-time" id="egw-post-event-end-time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'egw_post_event_end_time_meta_key', true ) ); ?>" size="30" placeholder="YYYY/MM/DD 00:00:00"/>
		<br />
		<textarea class="egw-post-event-textarea egw-event-text-area" cols="40" rows="5" name="egw-post-event-textarea" value=""><?php echo esc_attr( get_post_meta( $post->ID, 'egw_post_event_textarea_meta_key', true ) ); ?></textarea> 
	</p>
<?php }


/* Save the meta box's post metadata. */
function egw_save_post_live_event_meta( $post_id, $post ) {
  if ( !isset( $_POST['egw_live_events_nonce'] ) || !wp_verify_nonce( $_POST['egw_live_events_nonce'], basename( __FILE__ ) ) )
    return $post_id;
  $post_type = get_post_type_object( $post->post_type );
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

	// PRE EVENT DATA
	$egw_pre_event_start_time_new_meta_value = ( isset( $_POST['egw-pre-event-start-time'] ) ? $_POST['egw-pre-event-start-time'] : '' );
	$egw_pre_event_start_time_meta_key = 'egw_pre_event_start_time_meta_key';
	$egw_pre_event_start_time_meta_value = get_post_meta( $post_id, $egw_pre_event_start_time_meta_key, true );

	$egw_pre_event_end_time_new_meta_value = ( isset( $_POST['egw-pre-event-end-time'] ) ? $_POST['egw-pre-event-end-time'] : '' );
	$egw_pre_event_end_time_meta_key = 'egw_pre_event_end_time_meta_key';
	$egw_pre_event_end_time_meta_value = get_post_meta( $post_id, $egw_pre_event_end_time_meta_key, true );

	$egw_pre_event_textarea_new_meta_value = ( isset( $_POST['egw-pre-event-textarea'] ) ? $_POST['egw-pre-event-textarea'] : '' );
	$egw_pre_event_textarea_meta_key = 'egw_pre_event_textarea_meta_key';
	$egw_pre_event_textarea_meta_value = get_post_meta( $post_id, $egw_pre_event_end_time_meta_key, true );

	if ( $egw_pre_event_start_time_new_meta_value && '' == $egw_pre_event_start_time_meta_value )
		add_post_meta( $post_id, $egw_pre_event_start_time_meta_key, $egw_pre_event_start_time_new_meta_value, true );
	elseif ( $egw_pre_event_start_time_new_meta_value && $egw_pre_event_start_time_new_meta_value != $egw_pre_event_start_time_meta_value )
		update_post_meta( $post_id, $egw_pre_event_start_time_meta_key, $egw_pre_event_start_time_new_meta_value );
	elseif ( '' == $egw_pre_event_start_time_new_meta_value && $egw_pre_event_start_time_meta_value )
		delete_post_meta( $post_id, $egw_pre_event_start_time_meta_key, $egw_pre_event_start_time_meta_value );

	if ( $egw_pre_event_end_time_new_meta_value && '' == $egw_pre_event_end_time_meta_value )
		add_post_meta( $post_id, $egw_pre_event_end_time_meta_key, $egw_pre_event_end_time_new_meta_value, true );
	elseif ( $egw_pre_event_end_time_new_meta_value && $egw_pre_event_end_time_new_meta_value != $egw_pre_event_end_time_meta_value )
		update_post_meta( $post_id, $egw_pre_event_end_time_meta_key, $egw_pre_event_end_time_new_meta_value );
	elseif ( '' == $egw_pre_event_end_time_new_meta_value && $egw_pre_event_end_time_meta_value )
		delete_post_meta( $post_id, $egw_pre_event_end_time_meta_key, $egw_pre_end_start_time_meta_value );

	if ( $egw_pre_event_textarea_new_meta_value && '' == $egw_pre_event_textarea_meta_value )
		add_post_meta( $post_id, $egw_pre_event_textarea_meta_key, $egw_pre_event_textarea_new_meta_value, true );
	elseif ( $egw_pre_event_textarea_new_meta_value && $egw_pre_event_textarea_new_meta_value != $egw_pre_event_textarea_meta_value )
		update_post_meta( $post_id, $egw_pre_event_textarea_meta_key, $egw_pre_event_textarea_new_meta_value );
	elseif ( '' == $egw_pre_event_textarea_new_meta_value && $egw_pre_event_textarea_meta_value )
		delete_post_meta( $post_id, $egw_pre_event_textarea_meta_key, $egw_pre_end_start_time_meta_value );

	// DURING EVENT DATA
	$egw_during_event_start_time_new_meta_value = ( isset( $_POST['egw-during-event-start-time'] ) ? $_POST['egw-during-event-start-time'] : '' );
	$egw_during_event_start_time_meta_key = 'egw_during_event_start_time_meta_key';
	$egw_during_event_start_time_meta_value = get_post_meta( $post_id, $egw_during_event_start_time_meta_key, true );

	$egw_during_event_end_time_new_meta_value = ( isset( $_POST['egw-during-event-end-time'] ) ? $_POST['egw-during-event-end-time'] : '' );
	$egw_during_event_end_time_meta_key = 'egw_during_event_end_time_meta_key';
	$egw_during_event_end_time_meta_value = get_post_meta( $post_id, $egw_during_event_end_time_meta_key, true );

	$egw_during_event_textarea_new_meta_value = ( isset( $_POST['egw-during-event-textarea'] ) ? $_POST['egw-during-event-textarea'] : '' );
	$egw_during_event_textarea_meta_key = 'egw_during_event_textarea_meta_key';
	$egw_during_event_textarea_meta_value = get_post_meta( $post_id, $egw_during_event_end_time_meta_key, true );

	if ( $egw_during_event_start_time_new_meta_value && '' == $egw_during_event_start_time_meta_value )
		add_post_meta( $post_id, $egw_during_event_start_time_meta_key, $egw_during_event_start_time_new_meta_value, true );
	elseif ( $egw_during_event_start_time_new_meta_value && $egw_during_event_start_time_new_meta_value != $egw_during_event_start_time_meta_value )
		update_post_meta( $post_id, $egw_during_event_start_time_meta_key, $egw_during_event_start_time_new_meta_value );
	elseif ( '' == $egw_during_event_start_time_new_meta_value && $egw_during_event_start_time_meta_value )
		delete_post_meta( $post_id, $egw_during_event_start_time_meta_key, $egw_during_event_start_time_meta_value );

	if ( $egw_during_event_end_time_new_meta_value && '' == $egw_during_event_end_time_meta_value )
		add_post_meta( $post_id, $egw_during_event_end_time_meta_key, $egw_during_event_end_time_new_meta_value, true );
	elseif ( $egw_during_event_end_time_new_meta_value && $egw_during_event_end_time_new_meta_value != $egw_during_event_end_time_meta_value )
		update_post_meta( $post_id, $egw_during_event_end_time_meta_key, $egw_during_event_end_time_new_meta_value );
	elseif ( '' == $egw_during_event_end_time_new_meta_value && $egw_during_event_end_time_meta_value )
		delete_post_meta( $post_id, $egw_during_event_end_time_meta_key, $egw_during_end_start_time_meta_value );

	if ( $egw_during_event_textarea_new_meta_value && '' == $egw_during_event_textarea_meta_value )
		add_post_meta( $post_id, $egw_during_event_textarea_meta_key, $egw_during_event_textarea_new_meta_value, true );
	elseif ( $egw_during_event_textarea_new_meta_value && $egw_during_event_textarea_new_meta_value != $egw_during_event_textarea_meta_value )
		update_post_meta( $post_id, $egw_during_event_textarea_meta_key, $egw_during_event_textarea_new_meta_value );
	elseif ( '' == $egw_during_event_textarea_new_meta_value && $egw_during_event_textarea_meta_value )
		delete_post_meta( $post_id, $egw_during_event_textarea_meta_key, $egw_during_end_start_time_meta_value );

	// POST EVENT DATA
	$egw_post_event_start_time_new_meta_value = ( isset( $_POST['egw-post-event-start-time'] ) ? $_POST['egw-post-event-start-time'] : '' );
	$egw_post_event_start_time_meta_key = 'egw_post_event_start_time_meta_key';
	$egw_post_event_start_time_meta_value = get_post_meta( $post_id, $egw_post_event_start_time_meta_key, true );

	$egw_post_event_end_time_new_meta_value = ( isset( $_POST['egw-post-event-end-time'] ) ? $_POST['egw-post-event-end-time'] : '' );
	$egw_post_event_end_time_meta_key = 'egw_post_event_end_time_meta_key';
	$egw_post_event_end_time_meta_value = get_post_meta( $post_id, $egw_post_event_end_time_meta_key, true );

	$egw_post_event_textarea_new_meta_value = ( isset( $_POST['egw-post-event-textarea'] ) ? $_POST['egw-post-event-textarea'] : '' );
	$egw_post_event_textarea_meta_key = 'egw_post_event_textarea_meta_key';
	$egw_post_event_textarea_meta_value = get_post_meta( $post_id, $egw_post_event_end_time_meta_key, true );

	if ( $egw_post_event_start_time_new_meta_value && '' == $egw_post_event_start_time_meta_value )
		add_post_meta( $post_id, $egw_post_event_start_time_meta_key, $egw_post_event_start_time_new_meta_value, true );
	elseif ( $egw_post_event_start_time_new_meta_value && $egw_post_event_start_time_new_meta_value != $egw_post_event_start_time_meta_value )
		update_post_meta( $post_id, $egw_post_event_start_time_meta_key, $egw_post_event_start_time_new_meta_value );
	elseif ( '' == $egw_post_event_start_time_new_meta_value && $egw_post_event_start_time_meta_value )
		delete_post_meta( $post_id, $egw_post_event_start_time_meta_key, $egw_post_event_start_time_meta_value );

	if ( $egw_post_event_end_time_new_meta_value && '' == $egw_post_event_end_time_meta_value )
		add_post_meta( $post_id, $egw_post_event_end_time_meta_key, $egw_post_event_end_time_new_meta_value, true );
	elseif ( $egw_post_event_end_time_new_meta_value && $egw_post_event_end_time_new_meta_value != $egw_post_event_end_time_meta_value )
		update_post_meta( $post_id, $egw_post_event_end_time_meta_key, $egw_post_event_end_time_new_meta_value );
	elseif ( '' == $egw_post_event_end_time_new_meta_value && $egw_post_event_end_time_meta_value )
		delete_post_meta( $post_id, $egw_post_event_end_time_meta_key, $egw_post_end_start_time_meta_value );

	if ( $egw_post_event_textarea_new_meta_value && '' == $egw_post_event_textarea_meta_value )
		add_post_meta( $post_id, $egw_post_event_textarea_meta_key, $egw_post_event_textarea_new_meta_value, true );
	elseif ( $egw_post_event_textarea_new_meta_value && $egw_post_event_textarea_new_meta_value != $egw_post_event_textarea_meta_value )
		update_post_meta( $post_id, $egw_post_event_textarea_meta_key, $egw_post_event_textarea_new_meta_value );
	elseif ( '' == $egw_post_event_textarea_new_meta_value && $egw_post_event_textarea_meta_value )
		delete_post_meta( $post_id, $egw_post_event_textarea_meta_key, $egw_post_end_start_time_meta_value );
}
add_action( 'save_post', 'egw_save_post_live_event_meta', 10, 2 );


function egw_live_events_admin_post_class( $classes ) {

  /* Get the current post ID. */
  $post_id = get_the_ID();

  /* If we have a post ID, proceed. */
  if ( !empty( $post_id ) ) {

    /* Get the custom post class. */
    $post_class = get_post_meta( $post_id, 'egw_live_events_admin_post_class', true );

    /* If a post class was input, sanitize it and add it to the post class array. */
    if ( !empty( $post_class ) )
      $classes[] = sanitize_html_class( $post_class );
  }

  return $classes;
}
add_filter( 'post_class', 'egw_live_events_admin_post_class' );