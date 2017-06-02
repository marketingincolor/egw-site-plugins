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
 * 3.0.0        Featured Personalities Widget
 * 4.0.0        Facebook Comments
 *-------------------------------------------------------------------------------
 */

define('EGW_PLUGIN', plugin_dir_url(__FILE__));
define('EGW_CSS', plugin_dir_url(__FILE__) . 'assets/css/'); 

/* ------------------------------------------------------------------------------
* 1.0.0 Admin Functions
* ------------------------------------------------------------------------------
*/

require 'admin/egw-admin-init.php';

/* ------------------------------------------------------------------------------
* 2.0.0 WP-MAIL Functions
* change the return path in your WordPress email settings match from address
* ------------------------------------------------------------------------------
*/

require 'egw-mail-functions.php';

/* ------------------------------------------------------------------------------
* 3.0.0  Featured Personalities Sidebar
* ------------------------------------------------------------------------------
*/
require 'egw-featured-personality-widget.php';

/* ------------------------------------------------------------------------------
* 4.0.0  Facebook Comments
* ------------------------------------------------------------------------------
*/
require 'egw-facebook-comments.php';

/* ------------------------------------------------------------------------------
* 5.0.0  Advertiser Widget
* ------------------------------------------------------------------------------
*/
require 'egw-advertiser-widget.php'; 
