<?php
/**
 * Purpose: Add Columns to Posts
 * Author: AD
 * Date: 02/16/2017
 */

	// ADD SPONSOR NAME CONTENT
	function egwsp_add_content($column_name, $post_ID) {
		$post_author_id = get_post_field( 'post_author', $post_id );
		$company_name = get_the_author_meta( 'egwsp_company_name', $post_author_id );
		$company_link = 

		$attachment = egwsp_get_logo($post_author_id);
	    if ($column_name == 'egwsp_sponsor_name') {
	    	echo '<a href="' . get_edit_user_link() . '">' . $company_name . '</a>';
	    }
	    if ($column_name == 'egwsp_sponsor_logo') { ?>
	    	<a href="<?php echo get_edit_user_link(); ?>"><img id="image_upload_preview" src="<?php echo $attachment[0]; ?>" width="50" height="50"/></a>
	    <?php }
	}

	// MANAGE COLUMNS
	function egwsp_add_column($columns) {
		$egwsp_columns = array(
			'expirationdate' => __('Expiration Date'),
			'egwsp_sponsor_name' => __('Sponsor Name'),
			'egwsp_sponsor_logo' => __('Logo')
		);
		$columns = array_merge($columns, $egwsp_columns);
	    unset (
	    	$columns['3wp_broadcast']
	    );

	    //Reorder
		$reorder = array(
			'cb', 
			'egwsp_sponsor_logo', 
			'egwsp_sponsor_name', 
			'title', 
			'date', 
			'expirationdate', 
			'tags', 'categories', 
			'wpseo-score', 
			'wpseo-score-readability'
		);

		foreach ($reorder as $colname)
		$new[$colname] = $columns[$colname];    
		return $new;
	}

	add_filter('manage_edit-sponsored_posts_columns', 'egwsp_add_column');
	add_action( 'manage_posts_custom_column', 'egwsp_add_content', 10, 2 );

	function egwsp_get_logo($post_author_id) {
		$custom_avatar_meta_data = get_user_meta($post_author_id, 'custom_avatar');
		if (isset($custom_avatar_meta_data) && !empty($custom_avatar_meta_data[0])) {
			$attachment = wp_get_attachment_image_src($custom_avatar_meta_data[0]);
		}
		else {
			$attachment = get_avatar_url($post_author_id);
		}
		return $attachment;
	}
