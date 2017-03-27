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



/**
 * Add custom options menu for setting and changing system variables
 */
add_action( 'admin_menu', 'egw_add_admin_menu' );
add_action( 'admin_init', 'egw_settings_init' );
function egw_add_admin_menu(  ) { 
    add_submenu_page( 'options-general.php', 'Evergreen Wellness', 'Evergreen Wellness', 'manage_options', 'evergreen_wellness', 'egw_options_page' );
}
function egw_settings_init(  ) { 
    register_setting( 'pluginPage', 'egw_settings' );
    add_settings_section(
        'egw_section', 
        __( 'Your section description', 'egw' ), 
        'egw_settings_section_callback', 
        'egw_settings_page'
    );
    add_settings_field( 
        'egw_text_field_0', 
        __( 'Settings field description', 'egw' ), 
        'egw_text_field_0_render', 
        'egw_settings_page', 
        'egw_section' 
    );
    add_settings_field( 
        'egw_text_field_1', 
        __( 'Settings field description', 'egw' ), 
        'egw_text_field_1_render', 
        'egw_settings_page', 
        'egw_section' 
    );
    add_settings_field( 
        'egw_text_field_2', 
        __( 'Settings field description', 'egw' ), 
        'egw_text_field_2_render', 
        'egw_settings_page', 
        'egw_section' 
    );
}
function egw_text_field_0_render() { 
    $options = get_option( 'egw_settings' );
    ?>
    <input type='text' name='egw_settings[egw_text_field_0]' value='<?php echo $options['egw_text_field_0']; ?>'>
    <?php
}
function egw_text_field_1_render() { 
    $options = get_option( 'egw_settings' );
    ?>
    <input type='text' name='egw_settings[egw_text_field_1]' value='<?php echo $options['egw_text_field_1']; ?>'>
    <?php
}
function egw_text_field_2_render() { 
    $options = get_option( 'egw_settings' );
    ?>
    <input type='text' name='egw_settings[egw_text_field_2]' value='<?php echo $options['egw_text_field_2']; ?>'>
    <?php
}
function egw_settings_section_callback() { 
    echo __( 'This section description', 'egw' );
}
function egw_options_page() { 
    ?>
    <form action='options.php' method='post'>
        <h2>Evergreen Wellness</h2>
        <?php
        settings_fields( 'egw_settings_page' );
        do_settings_sections( 'egw_settings_page' );
        submit_button();
        ?>
    </form>
    <?php
}