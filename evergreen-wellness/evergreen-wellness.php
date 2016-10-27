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
 *
 * 1.0.0		Post Functions
 * 2.0.0		Admin Functions
 * 3.0.0		Analytics Functions
 * 
 *-------------------------------------------------------------------------------
 */

/* ------------------------------------------------------------------------------
 * 1.0.0 Post Functions
 * ------------------------------------------------------------------------------
 */

if(!function_exists('egw_category_shortcode')) {
/**
 * Description: Learn more shortcode
 * Purpose - Adds learn more to the bottom of posts.
 * @param  array $atts {
 *     @param  string Used for manually setting the learn more category.
 *  }
 * @return string For adding learn more snippets to bottom of posts
 * @since   1.0.0
 */
	function egw_category_shortcode($atts)
	{ 
	    $yoast_cat = new WPSEO_Primary_Term('category', get_the_ID());
	    $yoast_cat = $yoast_cat->get_primary_term();
	    $yoast_catName = get_cat_name($yoast_cat);
		$yoast_catLink = get_category_link($yoast_cat);
	    //No atts passed. Defaults to use yoast primary category.
		//Also allows for shortcode to be used without Yoast.
	    if ( $atts == null || $atts == '' )
	    {
	        if ( $yoast_catName && $yoast_catLink )
	        {   
	            $build_shortcode = '<div class="egw-learn-more"><p>';
	            $build_shortcode .= '<a href='. $yoast_catLink. '>Learn more about ' . strtolower($yoast_catName) . ' >></a>';
	            $build_shortcode .= "</p></div>";
	            return $build_shortcode;
	        }
	        elseif ( $yoast_cat == null || $yoast_cat == '')
	        {
	            $category = get_the_category();
	            $first_category_name = $category[0]->cat_name;
	            $category_id = get_cat_ID( $first_category_name );
	            $category_link  = get_category_link($category_id); 
	            $build_shortcode = '<div class="egw-learn-more"><p>';
	            $build_shortcode .= '<a href='. $category_link. '>Learn more about ' . strtolower($first_category_name) . ' >></a>';
	            $build_shortcode .= "</p></div>";
	            return $build_shortcode;
	        }
	    }
	    //Attributes are set. Use them.
	    elseif( isset($atts)) 
	    {
	        extract(shortcode_atts(array('cat' => $atts,), $atts));
	        $category_id = get_cat_ID($cat);
	        $category_link = get_category_link($category_id);
	        $build_shortcode = '<div class="egw-learn-more"><p>';
	        $build_shortcode .= '<a href="'. $category_link .'">Learn more about '. strtolower($cat) . '>></a>';
	        $build_shortcode .= '</p></div>';
	        return $build_shortcode;   
	    }
	    else {
	        return;
	    }
	}
	add_shortcode('egw-learn-more', 'egw_category_shortcode');
}

if(!function_exists('add_last_updated')) {
/**
 * Description: Last Modified Time Stamps
 * Purpose - Adds time stamp to bottom of posts based on date created/updated
 * @author Doe
 * @return string HTML container for timestamp
 * @since   1.0.0
 */
	function add_last_updated()
	{
	    $post_date_number = strtotime(get_the_date());
	    $mod_date_number = strtotime(get_the_modified_date());
	    $modified_date = get_the_modified_date('m.d.Y');
	    $post_date = get_the_date('m.d.Y');
	    $display_date = ($post_date_number > $mod_date_number ? $post_date : $modified_date);
	    /* Get both time variables for post*/
	    if (($mod_date_number != null && $post_date_number) != null && ($post_date_number != $mod_date_number))
	    {
	        echo 'Last updated: ' . $display_date;
	    }
	    /*If post time is missing use modified time*/
	    elseif($modified_date)
	    {
	        echo '<div class="posted-on">Last updated : ' . $modified_date . '</div>';
	    }
	    else
	    {
	        return;
	    }
	}
	add_action( 'last_updated', 'add_last_updated' );
}

if(!function_exists('my_filter_cdata')) {
/**
 * Description: Removes cdata from post save
 * Purpose - Fixes an issue where video embeds would get removed.
 * @author Twilbeck
 * @return string commented out cdata
 * @since   1.0.0
 */
	function my_filter_cdata( $content ) {
	  $content = str_replace( '// <![CDATA[', '', $content );
	  $content = str_replace( '// ]]>', '', $content );
	  return $content;
	}
	add_filter( 'content_save_pre', 'my_filter_cdata', 9, 1 );
}



/* ------------------------------------------------------------------------------
* 2.0.0 Admin Functions
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

/* ------------------------------------------------------------------------------
* 3.0.0 Analytics Functions
* ------------------------------------------------------------------------------
*/

if(!function_exists('custom_sponsor_contact_form'))
{
/**
 * Description: Sponsor Contact Success
 * Purpose - Pushes variables into the data layer if on 'sponsor-contact-success' page
 * @author Doe
 * @since   1.0.0
 */
	function custom_sponsor_contact_form()
	{

	    if (is_page('sponsor-contact-success')) {
	        echo "<script>
	                window.dataLayer = window.dataLayer || [];
	                dataLayer.push({
	                    'egwSponsorContactFormCategory' : 'Form',
	                    'egwSponsorContactFormAction' : 'Submitted',
	                    'egwSponsorContactFormLabel' : 'Sponsor'
	                });
	            </script>";
	    }
	}
	add_action('wp_footer', 'custom_sponsor_contact_form');
}