<?php
/*
  Plugin Name: FSP Registration
  Version: 1.0
  Author: Ramkumar.S
  Author URI: http://farshore.com
  Description: An Custom registeration and login for wordpress front end.
 */

function registration_form($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio) {
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    <div>
    <label for="username">Username <strong>*</strong></label>
    <input type="text" name="username" value="' . ( isset($_POST['username']) ? $username : null ) . '">
    </div>
     
    <div>
    <label for="password">Password <strong>*</strong></label>
    <input type="password" name="password" value="' . ( isset($_POST['password']) ? $password : null ) . '">
    </div>
     
    <div>
    <label for="email">Email <strong>*</strong></label>
    <input type="text" name="email" value="' . ( isset($_POST['email']) ? $email : null ) . '">
    </div>
     
    <div>
    <label for="website">Website</label>
    <input type="text" name="website" value="' . ( isset($_POST['website']) ? $website : null ) . '">
    </div>
     
    <div>
    <label for="firstname">First Name</label>
    <input type="text" name="fname" value="' . ( isset($_POST['fname']) ? $first_name : null ) . '">
    </div>
     
    <div>
    <label for="website">Last Name</label>
    <input type="text" name="lname" value="' . ( isset($_POST['lname']) ? $last_name : null ) . '">
    </div>
     
    <div>
    <label for="nickname">Nickname</label>
    <input type="text" name="nickname" value="' . ( isset($_POST['nickname']) ? $nickname : null ) . '">
    </div>
     
    <div>
    <label for="bio">About / Bio</label>
    <textarea name="bio">' . ( isset($_POST['bio']) ? $bio : null ) . '</textarea>
    </div>
    <input type="hidden" name="register_nonce" value="' . wp_create_nonce('register_nonce') . '"/>
    <input type="submit" name="register_submit" value="Register"/>
    </form>
    ';
}

function registration_validation($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio) {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if (empty($username) || empty($password) || empty($email)) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if (4 > strlen($username)) {
        $reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
    }
    if (username_exists($username)) {
        $reg_errors->add('user_name', 'Sorry, that username already exists!');
    }
    if (!validate_username($username)) {
        $reg_errors->add('username_invalid', 'Sorry, the username you entered is not valid');
    }
    if (5 > strlen($password)) {
        $reg_errors->add('password', 'Password length must be greater than 5');
    }

    if (!is_email($email)) {
        $reg_errors->add('email_invalid', 'Email is not valid');
    }
    if (email_exists($email)) {
        $reg_errors->add('email', 'Email Already in use');
    }
    if (!empty($website)) {
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $reg_errors->add('website', 'Website is not a valid URL');
        }
    }
    if (is_wp_error($reg_errors)) {

        foreach ($reg_errors->get_error_messages() as $error) {

            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';
        }
    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'user_url' => $website,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'nickname' => $nickname,
            'description' => $bio,
        );
        $user = wp_insert_user($userdata);
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/login">login page</a>.';
    }
}

function custom_registration_function() {
    if (isset($_POST['register_submit']) && isset($_POST['username']) && wp_verify_nonce($_POST['register_nonce'], 'register_nonce')) {
        registration_validation(
                $_POST['username'], $_POST['password'], $_POST['email'], $_POST['website'], $_POST['fname'], $_POST['lname'], $_POST['nickname'], $_POST['bio']
        );

        // sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $username = sanitize_user($_POST['username']);
        $password = esc_attr($_POST['password']);
        $email = sanitize_email($_POST['email']);
        $website = esc_url($_POST['website']);
        $first_name = sanitize_text_field($_POST['fname']);
        $last_name = sanitize_text_field($_POST['lname']);
        $nickname = sanitize_text_field($_POST['nickname']);
        $bio = esc_textarea($_POST['bio']);

        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
                $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio
        );
    }

    registration_form(
            $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio
    );
}

// Register a new shortcode: [fsp_custom_registration]
add_shortcode('fsp_custom_registration', 'custom_registration_shortcode');

// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}

/** Custom Login form
  Author: Ramkumar S
  Date: May 19 2016
 * */
// user login form
function fspr_login_form() {

    if (!is_user_logged_in()) {

        global $fspr_load_css;

        // set this to true so the CSS is loaded
        $fspr_load_css = true;

        $output = fspr_login_form_fields();
    } else {
        // could show some logged in user info here
        $output = 'You are logged in';
    }
    return $output;
}

add_shortcode('fsp_custom_login', 'fspr_login_form');

// login form fields
function fspr_login_form_fields() {

    ob_start();
    ?>
    <div class="login-container">
        <h3 class="fspr_header" style="text-transform: none; color: #4c4d4f; font-weight: 700;" ><?php _e('Log into your branch'); ?></h3>

        <?php
        // show any error messages after form submission
        fspr_show_error_messages();
        ?>

        <form id="fspr_login_form"  class="fspr_form"action="" method="post">
            <fieldset>
                <ul>
                    <li><input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
                        <div class="lg-fm-lft"><label for="fspr_user_Login">Email</label></div>
                        <div class="lg-fm-rgt"><input name="fspr_user_login" id="fspr_user_login" class="required" type="text"/></div>
                    </li>
                    <li>
                        <div class="lg-fm-lft"><label for="fspr_user_pass">Password</label></div>
                        <div class="lg-fm-rgt"><input name="fspr_user_pass" id="fspr_user_pass" class="required" type="password"/></div>
                    </li>
                </ul>
                <p>
                    <input type="hidden" name="fspr_login_nonce" value="<?php echo wp_create_nonce('fspr-login-nonce'); ?>"/>
                    <input id="fspr_login_submit" name="fspr_login_submit" type="submit" value="Login" class="fsplogin_btn"/>
                </p>
                <div class="fs_forgot_password">
                    <a href="<?php echo home_url('/register') ?>">Register</a> |
                    <a href="<?php echo home_url('/forgot-password') ?>">Forgot your password?</a>                 
                </div>
            </fieldset>
        </form>

		<script type="text/javascript">
		    var __ss_noform = __ss_noform || [];
		    __ss_noform.push(['baseURI', 'https://app-3QMYANU21K.marketingautomation.services/webforms/receivePostback/MzawMDG2NDQxAwA/']);
		    __ss_noform.push(['endpoint', 'ae4bfb37-9df4-45a7-a93b-6d8ce9e4f287']);
		</script>
		<script type="text/javascript" src="https://koi-3QMYANU21K.marketingautomation.services/client/noform.js?ver=1.24" ></script>

    </div>
    <?php
    return ob_get_clean();
}

// logs a member in after submitting a form
function fspr_login_member() {

    if (isset($_POST['fspr_user_login']) && isset($_POST['fspr_login_submit']) && wp_verify_nonce($_POST['fspr_login_nonce'], 'fspr-login-nonce')) {

        // Validate email
        $trimlogin = trim($_POST['fspr_user_login']);
        if (!filter_var($_POST['fspr_user_login'], FILTER_VALIDATE_EMAIL) === false) {
            //echo("is a valid email address");
            $userinfo = login_with_email_address($trimlogin);
        } else {
            //echo("is not a valid email address");
            $userinfo = $trimlogin;
        }

        // this returns the user ID and other info from the user name
        $trimpass = trim($_POST['fspr_user_pass']);
        $user = get_userdatabylogin($userinfo); //password or Email is a parameter.
        if (!$user) {
            fspr_errors()->add('wrong_username', __('Invalid Username or Password'));
        } else if (!wp_check_password($trimpass, $user->user_pass, $user->ID)) {
            fspr_errors()->add('wrong_password', __('Invalid Username or Password'));
        }

        // retrieve all error messages
        $errors = fspr_errors()->get_error_messages();

        // only log the user in if there are no errors
        if (empty($errors)) {

            wp_setcookie($userinfo, $trimpass, true);
            wp_set_current_user($user->ID, $userinfo);
            do_action('wp_login', $userinfo);
            //wp_redirect(home_url('/user-profile'));
            $user_blog_id=get_user_meta($user->ID,'primary_blog',true);
            if($user_blog_id!=1)
                $meta_data=get_user_meta($user->ID,'wp_'.$user_blog_id.'_capabilities',true);
            else 
                $meta_data=get_user_meta($user->ID,'wp_capabilities',true);
            
            if (is_super_admin()) {                                
                wp_redirect(home_url('/wp-admin'));
            } else {    
                $site_url = other_user_profile_redirection();
                if(isset($meta_data['subscriber'])){ 
                    
                    //Redirect to welcome page when user login first time                     
                    $first_login = get_user_meta( $user->ID, 'first_login', true );
                    if( ! $first_login ) {                        
                        update_user_meta( $user->ID, 'first_login', 'true', '' );                                               
                        wp_redirect($site_url.'/welcome'); 
                        exit;
                    }
                    
                    //Redirect to home or referrer url after user login
                    $location = $_POST['redirect']; // referral URL fetch from post value
                    $findblog_page = url_to_postid($location); // Get Post ID from referral URL
                    $getwhichIs = get_post_type($findblog_page); // Find Post Type using Post ID                    
                    if ($getwhichIs == "videos" || $getwhichIs == "post") {
                        if ($site_url)  
                            wp_redirect($site_url); 
                        else    
                            wp_redirect($location);                        
                    } else {                        
                        if ($site_url)  
                            wp_redirect($site_url);
                        else   
                            wp_redirect(home_url());                        
                    }
                    
                } else {   
                    //Redirect to admin page if user role is admin
                    if ($site_url)
                        wp_redirect($site_url . '/wp-admin');
                    else
                        wp_redirect(home_url('/wp-admin'));                    
                }
            }
            exit;
        }
    }
}

add_action('init', 'fspr_login_member');

// used for tracking error messages
function fspr_errors() {
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function fspr_show_error_messages() {
    if ($codes = fspr_errors()->get_error_codes()) {
        echo '<div class="fspr_errors">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = fspr_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}

/* * * Add a Coach user role
 * Author : Ramkumar S
 * Create Date: May 24 2016
 * Updated Date: May 25 2016
 */

function add_roles_on_plugin_activation() {
    $result = add_role('coach', __('Coach'), array(
        'read' => true, // true allows this capability
        'edit_posts' => true, // Allows user to edit their own posts
        'edit_pages' => true, // Allows user to edit pages
        'edit_others_posts' => false, // Allows user to edit others posts not just their own
        'create_posts' => true, // Allows user to create new posts
        'manage_categories' => true, // Allows user to manage post categories
        'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
        'edit_themes' => false, // false denies this capability. User can’t edit your theme
        'install_plugins' => false, // User cant add new plugins
        'update_plugin' => false, // User can’t update any plugins
        'update_core' => false // user cant perform core updates
    ));
}

register_activation_hook(__FILE__, 'add_roles_on_plugin_activation');


/* * * Login / Registeration Redirect 
 * Author : Ramkumar S
 * Create Date: May 25 2016
 * Updated Date: July 18 2016
 * Updated by  : Muthupandi
 */

function fsp_template_redirect() {
    
    if(is_user_logged_in()){               

        $user_blog_id=get_user_meta(get_current_user_id(),'primary_blog',true);
        if($user_blog_id!=1)
            $meta_data=get_user_meta(get_current_user_id(),'wp_'.$user_blog_id.'_capabilities',true);
        else 
            $meta_data=get_user_meta(get_current_user_id(),'wp_capabilities',true);
            
        if ((is_page('login') || is_page('register'))) {  
            
            if(wp_get_referer()){
                $location = wp_get_referer();
                $findblog_page = url_to_postid($location);
                $getwhichIs = get_post_type($findblog_page);
                if ($getwhichIs == "videos" || $getwhichIs == "post") {
                    wp_logout();
                }
            }
            if (is_super_admin()) {
                wp_redirect(home_url('/wp-admin'));
            } else {
                $webtype = "/wp-admin";
                if (isset($meta_data['subscriber'])) {
                    $webtype = "/user-profile";
                }
                $site_url = other_user_profile_redirection();
                if ($site_url) {
                    wp_redirect($site_url . $webtype);
                } else {
                    wp_redirect(home_url($webtype));
                }
            }
        } else if (is_page('user-profile')) {

            if (is_super_admin()) {
                wp_redirect(home_url('/wp-admin'));
            } else {
                $webtype = "/wp-admin";
                if (isset($meta_data['subscriber'])) {
                    $webtype = "/user-profile";
                }
                $site_url = other_user_profile_redirection();
                if ($site_url) {
                    wp_redirect($site_url . $webtype);
                }
            }
        }
    } else {
        if (is_page('user-profile')) {
            wp_redirect(home_url('/login'));
            exit();
        }
    }
}

function other_user_profile_redirection() {
    if (is_user_logged_in()) {
        $userid = get_current_user_id();
        $user_blog_id = get_user_meta($userid, 'primary_blog', true);
        $blog_id = get_current_blog_id();
        if ($blog_id != $user_blog_id) {
            $blog = get_blog_details($user_blog_id);
            return $blog->siteurl;
        }
    }
    return 0;
}

add_action('template_redirect', 'fsp_template_redirect');

add_action('wp_logout', create_function('', 'wp_redirect(home_url("/login"));exit();'));



/* * * User Profile Function
 * Author : Ramkumar S
 * Create Date: May 26 2016
 * Updated Date: May 26 2016
 */
/*

// Register a new shortcode: [fsp_user_profile]
add_shortcode('fsp_user_profile', 'user_profile_shortcode');

// The callback function that will replace [book]
function user_profile_shortcode() {
    ob_start();
    nocache_headers();
    global $userdata;
    global $current_user;
//    get_currentuserinfo();
    $current_user = wp_get_current_user();
    custom_user_profile($current_user, $userdata);
    return ob_get_clean();
}

function custom_user_profile($current_user, $userdata) {
//    print_r($userdata);
    $user_ID = $current_user->ID;

    if (isset($_POST['first_name']) && isset($_POST['profile_submit']) && wp_verify_nonce($_POST['update-profile-nonce'], 'update-profile-nonce')) {

        require_once(ABSPATH . 'wp-admin/includes/user.php');
        require_once(ABSPATH . WPINC . '/registration.php');

        check_admin_referer('update-profile_' . $user_ID);

        $errors = edit_user($user_ID);

        if (is_wp_error($errors)) {
            foreach ($errors->get_error_messages() as $message)
                $errmsg = "$message";
        }

        if ($errmsg == '') {
            do_action('personal_options_update', $user_ID);
            $d_url = $_POST['dashboard_url'];
            wp_redirect(get_option("siteurl") . '?page_id=' . $post->ID . '&updated=true');
        } else {
            $errmsg = '<div class="box-red">' . $errmsg . '</div>';
            $errcolor = 'style="background-color:#FFEBE8;border:1px solid #CC0000;"';
        }
    }

custom_user_profile_fields($current_user, $userdata,$user_ID);
}

function custom_user_profile_fields($current_user, $userdata,$user_ID){

    ob_start();
    ?>
    <form name="profile" action="" method="post" enctype="multipart/form-data">
        <?php
        wp_nonce_field('update-profile_' . $user_ID);
        //echo $user_ID;
        ?>
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
        <input type="hidden" name="dashboard_url" value="<?php echo get_option("dashboard_url"); ?>" />
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
        <ul>
            <?php
            if (isset($_GET['updated'])):
                $d_url = $_GET['d'];
                ?>
                <li>
                    <div align="center" colspan="2"><span style="color: #FF0000; font-size: 11px;">Your profile changed successfully</span></div>
                </li>
            <?php elseif ($errmsg != ""): ?>
                <li>
                    <div align="center" colspan="2"><span style="color: #FF0000; font-size: 11px;"><?php echo $errmsg; ?></span></div>
                </li>
    <?php endif; ?>
            <li>
                <td colspan="2" align="center"><h2>User profile</h2></div>
            </li>
            <li>
                <div>First Name</div>
                <div><input type="text" name="first_name" id="first_name" value="<?php echo $userdata->first_name ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Last Name</div>
                <div><input type="text" name="last_name" class="mid2" id="last_name" value="<?php echo $userdata->last_name ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Nick name <span style="color: #F00">*</span></div>
                <div><input type="text" name="nickname" class="mid2" id="nickname" value="<?php echo $userdata->nickname ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Email <span style="color: #F00">*</span></div>
                <div><input type="text" name="email" class="mid2" id="email" value="<?php echo $userdata->user_email ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>New Password </div>
                <div><input type="password" name="pass1" class="mid2" id="pass1" value="" style="width: 300px;" /></div>
            </li>
            <li>
                <div>New Password Confirm </div>
                <div><input type="password" name="pass2" class="mid2" id="pass2" value="" style="width: 300px;" /></div>
            </li>
            <li>
                <td align="right" colspan="2"><span style="color: #F00">*</span> <span style="padding-right:40px;">mandatory fields</span></div>
            </li>
            <li><td colspan="2"><h3>Extra profile information</h3></div></li>
            <li>
                <div>Facebook URL</div>
                <div><input type="text" name="facebook" id="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Twitter</div>
                <div><input type="text" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Date Of Birth</div>
                <div><input type="text" name="dob" id="dob" value="<?php echo esc_attr(get_the_author_meta('dob', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Phone</div>
                <div><input type="text" name="phone" id="phone" value="<?php echo esc_attr(get_the_author_meta('phone', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Address</div>
                <div><input type="text" name="address" id="address" value="<?php echo esc_attr(get_the_author_meta('address', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>City</div>
                <div><input type="text" name="city" id="city" value="<?php echo esc_attr(get_the_author_meta('city', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div>Postal Code</div>
                <div><input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr(get_the_author_meta('postalcode', $userdata->ID)); ?>" style="width: 300px;" /></div>
            </li>
            <li>
                <div align="center" colspan="2"><input type="submit" name="profile_submit" value="Update" /></div>
            </li>
        </ul>
        <input type="hidden" name="update-profile-nonce" value="<?php echo wp_create_nonce('update-profile-nonce'); ?>"/>
    </form>
    <?php
}*/
