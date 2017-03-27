<?php
/**
 * Evergreen Wellness
 *
 * @author      Evergreen Wellness, LLC
 * @copyright   2016 Evergreen Wellness, LLC
 * @license     GPL-2.0+
 * @package     WordPress
 * @wordpress-plugin
 * Plugin Name: Evergreen Wellness
 * Plugin URI:  https://myevergreenwellness.com
 * Description: Custom features for myEvergreenWellness and subsites. Do not delete, uninstall, or deactivate.
 * Version:     1.0.0
 * Author:      Marketing In Color
 * Author URI:  http://marketingincolor.com
 * Text Domain: Evergreen Wellness
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Network: True
 */

/*
    Copyright (C) 2016  Marketing In Color  developer@marketingincolor.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*-------------------------------------------------------------------------------
 * Table of Contents
 * 1.0.0		Admin Functions
 * 2.0.0        WP_MAIL Functions
 * 
 *-------------------------------------------------------------------------------
 */


/* ------------------------------------------------------------------------------
* 1.0.0 Admin Functions
* ------------------------------------------------------------------------------
*/

/**
* Description: Evergreen Wellness Admin Theme
* Purpose - For Adding Evergreen Wellness - The Villages Admin Theme
* @author Doe
* @since   1.0.0
*/
function egw_admin_color_scheme() {
    $theme_dir = get_stylesheet_directory_uri();
    wp_admin_css_color(
        'evergreen', __('Evergreen Wellness - The Villages'),
        $theme_dir . '/admin-colors/evergreen/colors.css',
        array( '#bed743', '#f89d38', '#3a7d3b', '#7d7d7d')
        );
}       
add_action('admin_init', 'egw_admin_color_scheme');

/**
 * Description: Default Villages Subsite Admin Theme
 * Purpose - For Setting Default Color Scheme of new users to Evergreen Wellness - The Villages
 * @author Doe
 * @since   1.0.0
 */
if (get_current_blog_id() == '2')
{
	function set_default_admin_color($user_id) {
	    $args = array(
	        'ID' => $user_id,
	        'admin_color' => 'Evergreen Wellness - The Villages'
	    );
	    wp_update_user( $args );
	}
	add_action('user_register', 'set_default_admin_color');
}

/**
 * Description: Production ID Admin sort and search
 * Purpose: Add custom sort column for Production ID to POST and VIDEO content types
 * Notes: Funtion relies on ACF plugin to work properly, field name must be "production_id"
 * @author  etwilbeck
 * @since  1.0.0
 */
add_filter('manage_posts_columns', 'egw_columns_head');
function egw_columns_head($columns) {
    $columns['prodid'] =__('Prod ID');
    return $columns;
}
add_action('manage_posts_custom_column', 'egw_columns_content', 10, 2);
function egw_columns_content( $column_name, $post_id ) {
    if ( 'prodid' != $column_name )
        return;
    $prodid = get_post_meta($post_id, 'production_id', true);
    echo intval($prodid);
}
add_filter( 'manage_edit-videos_sortable_columns', 'egw_sortable_production_column' );
add_filter( 'manage_edit-post_sortable_columns', 'egw_sortable_production_column' );
function egw_sortable_production_column( $columns ) {
    $columns['prodid'] = 'prod';
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
    return $columns;
}
/**
 * Add custom sort column for Production ID to POST and VIDEO content types via SEARCH component in edit view
 */
/*function custom_search_query( $query ) {
    $custom_fields = array(
        'production_id'
    );
    $searchterm = $query->query_vars['s'];
    $query->query_vars['s'] = "";
    if ($searchterm != "") {
        $meta_query = array('relation' => 'OR');
        foreach($custom_fields as $cf) {
            array_push($meta_query, array(
                'key' => $cf,
                'value' => $searchterm,
                'compare' => 'LIKE'
            ));
        }
        $query->set("meta_query", $meta_query);
    };
}
add_filter( "pre_get_posts", "custom_search_query");*/


/* ------------------------------------------------------------------------------
* 2.0.0 WP-MAIL Functions
* change the return path in your WordPress email settings match from address
* ------------------------------------------------------------------------------
*/
add_action( 'phpmailer_init', 'fix_egw_email_return_path' );
 
function fix_egw_email_return_path( $phpmailer ) {
    $phpmailer->Sender = $phpmailer->From;
}