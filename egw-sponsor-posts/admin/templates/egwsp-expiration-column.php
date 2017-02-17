<?php
/**
 * Purpose: Add Columns to Posts
 * Author: AD
 * Date: 02/16/2017
 */

	// Get Expiration Date
	function egwsp_get_expiration_date($post_ID) {
		global $post;
	    $expiration_date = get_post_meta($post->ID, 'egwsp_expiration_date' );
	    if ($expiration_date) {
	        return $expiration_date;
	    }
	}

	//Add Expiration Column to Post Table
	function egwsp_column_contents($column_name, $post_ID) {
	    if ($column_name == 'expiration_date') {
	        $expiration_date = egwsp_get_expiration_date($post_ID);
	        if ($expiration_date) {
	            // Has Expiration Date
	            echo $expiration_date;
	        }
	        else {
	            //No Expiration Date
	            echo '—';
	        }
	    }
	}

	// ADD NEW COLUMN
	function egwsp_expiration_column_head($columns) {
		$egwsp_columns = array(
			'egwsp_expiration_date' => __('Expiration Date'),
			'egwsp_sponsor_name' => __('Sponsor'),
		);
		$columns = array_merge($columns, $egwsp_columns);
	    unset (
	    	$columns['3wp_broadcast']
	    );
	    return $columns;
	}
	 
	// SHOW THE Expiration Date
	function egwsp_expiration_date_content($column_name, $post_ID) {
	    if ($column_name == 'expiration_date') {
	        $egwsp_expiration_date = egwsp_get_expiration_date($post_ID);
	        if ($egwsp_expiration_date) {
	            echo $egwsp_expiration_date;
	        }
	    }
	}

	add_filter('manage_edit-sponsored_posts_columns', 'egwsp_expiration_column_head');
	add_action('manage_edit-sponsored_posts_custom_column', 'egwsp_expiration_date_content');

