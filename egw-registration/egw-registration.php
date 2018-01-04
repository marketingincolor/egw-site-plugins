<?php
/*
  Plugin Name: EGW Registration
  Version: 1.1
  Author: Ramkumar.S and Edd T.
  Description: A custom registration and login for wordpress front end.
 */

function registration_form($email, $first_name, $last_name, $postalcode, $agree, $zip_error, $form_error, $email_error) {
    $url = get_stylesheet_directory_uri() . "/assets/js/google-sheet.js";
    echo '
    <form id="egw-registration" action="' . $_SERVER['REQUEST_URI'] . '" method="post">

    <div class="join-field-row vc_col-xs-12">
        <label for="fname" class="join-field-label">First Name</label>
        <div class="join-field-horiz">
            <input type="text" name="fname" value="' . ( isset($_POST['fname']) ? $first_name : null ) . '">
        </div>
    </div>
     
    <div class="join-field-row vc_col-xs-12">
        <label for="lname" class="join-field-label">Last Name</label>
        <div class="join-field-horiz">
            <input type="text" name="lname" value="' . ( isset($_POST['lname']) ? $last_name : null ) . '">
        </div>
    </div>
     
    <div class="join-field-row vc_col-xs-12">
        <label for="email" class="join-field-label">Your Email <strong>*</strong></label>
            <span class="error register_error">' .  $email_error  . '</span>
        <div class="join-field-horiz">
            <input type="text" name="email" value="' . ( isset($_POST['email']) ? $email : null ) . '">
            <span style="font-size: 13px; display: block; line-height: 15px;">Please add <strong>support@myevergreenwellness.com</strong> to your address book to ensure your email doesn\'t go into your spam folder.</span>
        </div>
    </div>

    <div class="join-field-row vc_col-xs-12">
        <label for="age" class="join-field-label">Age <strong>*</strong></label>
        <div class="join-field-horiz">
            <input type="text" name="age" value="' . ( isset($_POST['age']) ? $age : null ) . '">
        </div>
    </div>

    <div class="join-field-row vc_col-xs-12">
        <label for="postalcode" class="join-field-label">Postal Code <strong>*</strong></label>
            <span class="error register_error">' . $zip_error . '</span>
        <div class="join-field-horiz">
            <input type="text" name="postalcode" value="' . ( isset($_POST['postalcode']) ? $postalcode : null ) . '">
        </div>
    </div>

    <div class="join-field-row vc_col-xs-12">
        <p><b>We ask for your age to help us better serve the interests of our members,</b> tailoring content we create that’s suitable for the age groups participating in our website. We promise not to tell anyone how young you are! ;-)</p>
        <p><br></p>
        <p>To become a member, you must agree to the <a href="https://myevergreenwellness.com/terms-and-conditions" target="_blank">Terms and Conditions</a> of this website.</p>
        <label class="agree-box">
        <input type="checkbox" name="agree" value="' . ( isset($_POST['agree']) ? $agree : 'yes' ) . '" style="display:inline;" class="agree-box"></input>I Agree*</label>
        <br/><span class="error register_error">' . $form_error . '</span>
    </div>
    <input type="hidden" name="register_nonce" value="' . wp_create_nonce('register_nonce') . '"/>
    <input type="submit" name="register_submit" value="Submit" class="register-submit-button"/>

    </form>'; 
    //<script type="text/javascript" src="'. $url . '"></script>'; 
}

function registration_validation($email, $first_name, $last_name, $postalcode, $agree) {
    global $reg_errors, $zip_error, $form_error, $email_error;
    $reg_errors = new WP_Error;
    if (empty($email) || empty($agree)) {
        $reg_errors->add('field', 'Required form fields are missing');
        $form_error = $reg_errors->get_error_message('field');
    }
    if (!is_email($email)) {
        $reg_errors->add('email_invalid', 'Email is not valid');
        $email_error = $reg_errors->get_error_message('email_invalid');
    }
    if (email_exists($email)) {
        $reg_errors->add('email', 'Email Already in use');
        $email_error = $reg_errors->get_error_message('email');
    }
    if (empty($postalcode)) {
        $reg_errors->add('postal', 'Postal Code is missing');
        $zip_error = $reg_errors->get_error_message('postal');
    }
}

function complete_registration() {
    global $reg_errors, $password, $email, $first_name, $last_name, $postalcode;
    $village_codes = array('32159', '32162', '32163');
    $password = wp_generate_password();

    if ( in_array( $postalcode, $village_codes )) {
        $userlocation = 'villages_member';
    } else {
        $userlocation = 'subscriber';
    }
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'postalcode' => $postalcode,
            'role' => $userlocation
        );
        $user = wp_insert_user($userdata);

        if ($user) {
            $addrole = new WP_User( $user );
            $addrole->add_role( 'subscriber' );
            egw_send_registration_email($email, $password, $first_name);
            add_user_meta( $user, 'primary_blog', 1);
            wp_redirect( home_url() . '/register-success');
        } else {
            echo 'Registration could not be completed. See error data above.';
        }

        //echo 'Registration complete. Goto <a href="' . get_site_url() . '/login">login page</a>.';
        //wp_redirect( home_url() . '/register-success');
    }
}

function egw_send_registration_email($email, $password, $first_name) {
    $login_page = home_url('/login');
    $subject = "Important New Member Information";
    $message = "Hi, {$first_name},\r\n<br>";
    $message .= "<br>";
    $message .= "You have successfully joined Evergreen Wellness and can now enjoy the full benefits of our website!\r\n<br>";
    $message .= "<br>";
    $message .= "Please visit <a href='$login_page'>$login_page</a> and login with the following information: \r\n<br>";
    $message .= "<br>";
    $message .= "Your email: {$email} \r\n<br>";
    $message .= "Your password: {$password} \r\n<br>";
    $message .= "<br>";
    $message .= "We hope you enjoy the website. Here's to your happier, healthier life! \r\n<br>";
    $message .= "<br>";
    $message .= "The Evergreen Wellness Team";
    $sender = 'From: Evergreen Wellness <support@myevergreenwellness.com>' . "\r\n";
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = $sender;

    $success = wp_mail($email, $subject, $message, $headers);
    // email admin on success
    if ($success) {
        $admin_to = "admin@myevergreenwellness.com";
        $admin_sub = "EGW User Registration";
        $admin_msg = "User {$email} was registered on " . date('m.d.Y H:i:s');
        $admin_hdr = array('Content-Type: text/html; charset=UTF-8');
        $send_admin = wp_mail($admin_to, $admin_sub, $admin_msg, $admin_hdr);
    }
}

function custom_registration_function() {
    global $zip_error, $form_error, $email_error;
    if (isset($_POST['register_submit']) && isset($_POST['email']) && wp_verify_nonce($_POST['register_nonce'], 'register_nonce')) {
        registration_validation(
                $_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['postalcode'], $_POST['agree']
        );

        // sanitize user form input
        global $email, $first_name, $last_name, $postalcode, $agree;
        $email = sanitize_email($_POST['email']);
        $first_name = sanitize_text_field($_POST['fname']);
        $last_name = sanitize_text_field($_POST['lname']);
        $postalcode = sanitize_text_field($_POST['postalcode']);
        $agree = $_POST['agree'];

        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
                $email, $first_name, $last_name, $postalcode
        );
    }
    registration_form(
            $email, $first_name, $last_name, $postalcode, $agree, $zip_error, $form_error, $email_error
    );
}
// Register a new shortcode: [egw_custom_registration]
add_shortcode('egw_custom_registration', 'custom_registration_shortcode');

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

    // Check dev || production & send to correct SS backend
    $ss_form = (ENVIRONMENT_MODE == 1) ? 'ae4bfb37-9df4-45a7-a93b-6d8ce9e4f287' : 'e8803dde-8e73-4457-8504-0d11cbab927d'; 
    ?>
    <div class="login-container">
        <h3 class="fspr_header" style="text-transform: none; color: #4c4d4f; font-weight: 700;" ><?php _e('Log into your branch'); ?></h3>

        <?php
        // show any error messages after form submission
        fspr_show_error_messages();
        ?>

        <form id="fspr_login_form"  class="fspr_form" action="" method="post">
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
                    <a href="<?php echo home_url('/forgot-password') ?>">Forgot your password?</a>
                </div>
            </fieldset>
        </form>

		<script type="text/javascript">
		    var __ss_noform = __ss_noform || [];
		    __ss_noform.push(['baseURI', 'https://app-3QMYANU21K.marketingautomation.services/webforms/receivePostback/MzawMDG2NDQxAwA/']);
		    __ss_noform.push(['endpoint', '<?php echo $ss_form; ?>']);
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
            $user_blog_id = get_user_meta($user->ID, 'primary_blog', true);
            if($user_blog_id != 1)
                $meta_data = get_user_meta($user->ID, 'wp_'.$user_blog_id.'_capabilities', true);
            else 
                $meta_data = get_user_meta($user->ID, 'wp_capabilities', true);
            
            if (is_super_admin()) {
                wp_redirect(home_url('/wp-admin'));
            } else {
                $site_url = other_user_profile_redirection();
                if(isset($meta_data['administrator'])){
                    //Redirect to admin page if user role is admin
                    if ($site_url)
                        wp_redirect($site_url . '/wp-admin');
                    else
                        wp_redirect(home_url('/wp-admin'));
                } else {   
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
 * Updated Date: January 6 2017
 * Updated by  : MIC DEV
 * Notes: Removed redirect logic NEEDED by multisite setup due to single site change; Will need to be REPLACED should to use multisite again
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
        } else if (is_page('user-profile') && is_super_admin()) {
            //See original FSP Plugin for CORRECT multisite logic
            wp_redirect(home_url('/wp-admin'));
        }
    } else {
        if (is_page('user-profile')) {
            wp_redirect(home_url('/login'));
            exit();
        }
    }
}

function other_user_profile_redirection() {
    //if (is_user_logged_in()) {
    //    $userid = get_current_user_id();
    //    $user_blog_id = get_user_meta($userid, 'primary_blog', true);
    //    $blog_id = get_current_blog_id();
    //    if ($blog_id != $user_blog_id) {
    //        //$blog = get_blog_details($user_blog_id);
    //        $blog = get_blog_details($blog_id);
    //        return $blog->siteurl;
    //    }
    //}
    //return 0;
    return home_url();
}

add_action('template_redirect', 'fsp_template_redirect');

add_action('wp_logout', create_function('', 'wp_redirect(home_url("/login"));exit();'));

