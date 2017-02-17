<?php
/**
 * Purpose: For adding Metaboxes to Posts
 * Author: AD
 * Date: 02/16/2016
 */
	//Add jQuery Date Picker
	function egwsp_datepicker() {    
	    wp_enqueue_script( 'jquery-ui-datepicker' );
	    wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}
	add_action( 'admin_enqueue_scripts', 'egwsp_datepicker' );

	//Add Meta Box to Sponsored Post Type
	function egw_expire_metabox() {
	    add_meta_box( 
	        'egw_expire_metabox', 
	        'Expiration Date', 
	        'egw_expire_metabox_callback', 
	        'sponsored_posts', 
	        'side', 
	        'high'
	    );
	}
	add_action( 'add_meta_boxes', 'egw_expire_metabox' );


	//Add form to Metabox
	function egw_expire_metabox_callback( $post ) { 
		//Only save meta using this form
		wp_nonce_field( 'egw_expire_metabox_nonce', 'egw_expire_nonce' );

		?>
     
    <form action="" method="post">
         
        <?php        
        //retrieve metadata value if it exists
        $egw_expiration_date = get_post_meta( $post->ID, 'expires', true );
        ?>
         
        <label for "egw_expiration_date">Expiration Date:</label>
                 
        <input type="text" class="egw-expiration-date" name="egw_expiration_date" value=<?php echo esc_attr( $egw_expiration_date ); ?> / >
        <script type="text/javascript">
    		jQuery(document).ready(function() {
	        	jQuery('.egw-expiration-date').datepicker({
	            dateFormat : 'dd-mm-yy'
        		});
    		});
		</script>               
     
    </form>
     
	<?php }

	//Save Expiration Date
	function egw_expire_save_date( $post_id ) {

		if( !isset( $_POST['egw_expire_nonce'] ) || !wp_verify_nonce( $_POST['egw_expire_nonce'], 'egw_expire_metabox_nonce') ) 
		return;
     
	    // Check if the current user has permission to edit the post. */
	    if ( !current_user_can( 'edit_post', $post->ID ) )
	    return;
	     
	    if ( isset( $_POST['egwsp_expiration_date'] ) ) {        
	        $new_expiry_date = ( $_POST['egwsp_expiration_date'] );
	        update_post_meta( $post_id, 'expires', $new_expiry_date );      
	    }
     
	}
	add_action( 'save_post', 'egw_expire_save_date' );

	require_once 'egwsp-expiration-column.php';