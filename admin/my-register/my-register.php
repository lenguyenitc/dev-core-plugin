<?php
add_action('login_header', 'mr_add_template');
function mr_add_template(){
    require_once __DIR__ . '/template.php';
}

add_action('login_enqueue_scripts', 'mr_add_script');
function mr_add_script(){
    wp_deregister_script( 'jquery-core' );
    wp_register_script( 'jquery-core', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    wp_enqueue_script( 'jquery-core' );

    wp_enqueue_script('mr-my-register-js', plugins_url('assets/my-register.js', __FILE__), ['jquery-core']);
    wp_localize_script('mr-my-register-js', 'my_reg_obj', [
    	'form_logo' => (get_theme_mod('logo_file') !== false && get_theme_mod('logo_file') !== "") ? wp_get_attachment_image_url(get_theme_mod('logo_file'), 'full') : wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
        'show_form_logo' => (get_theme_mod('logo_show') !== false) ? get_theme_mod('logo_show') : 'false',
	    'custom_logo' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
        'site_url' => site_url()
    ]);
	/***JQuery UI***/
	wp_enqueue_style('login-jquery-ui-css', plugins_url('assets/jquery-ui.css', __FILE__), '', '', 'all');
	wp_enqueue_script('login-jquery-ui-js', plugins_url('assets/jquery-ui.min.js', __FILE__), array('jquery-core'), '', false );

	if ( $GLOBALS['pagenow'] === 'wp-login.php' && $_REQUEST['action'] === 'register') {
		wp_enqueue_script( 'mr-form-animation', plugins_url( 'assets/form-animation.js', __FILE__ ), [ 'jquery-core' ] );
	}

}

add_action( 'login_footer', 'add_custom_block_to_login_footer' );
function add_custom_block_to_login_footer() {
	?>
    <script>
        jQuery(document).ready(function($) {
            $('#user_login').attr('placeholder', 'Username or Email Address');
            $('#user_pass').attr('placeholder', 'Password');

            $('#rememberme').checkboxradio();
        });
    </script>
	<?php
}


add_action('login_form', 'add_message_to_login_form');
function add_message_to_login_form()
{
	if($GLOBALS['pagenow'] === 'wp-login.php' && $_GET['reg'] == 'confirm'):?>
        <style>
            #confirm_reg{
                font-size: 14px;
                text-align: center;
                color: #fff;
            }
            p#reg_passmail {
                display: none;
            }
            #wp-submit {
                margin-bottom: 20px;
            }
            p#confirm_reg {
                color: <?=get_theme_mod('text_site_color');?>;
            }
        </style>
        <script>
            jQuery(document).ready(function ($){
                $('p.submit').after('<br><br><p id="confirm_reg">Your account has been created. A confirmation has been sent to your email.</p>');
            });
        </script>
        <?php
    endif;
}
add_filter( 'login_message', 'filter_messages_on_reg_and_forgot_pages' );
function filter_messages_on_reg_and_forgot_pages($message){
	if ( get_theme_mod( 'info_border_color' ) !== false ) {
		$border_left = '4px solid ' . get_theme_mod( 'info_border_color' );
	} else $border_left = '4px solid #843b3f';

	if ( get_theme_mod( 'info_back_color' ) !== false ) {
		$back = get_theme_mod( 'info_back_color' );
	} else $back = '#ffffff';

	if ( get_theme_mod( 'info_text_color' ) !== false ) {
		$color = get_theme_mod( 'info_text_color' );
	} else $color = '#a86148';

	if ( $GLOBALS['pagenow'] === 'wp-login.php' && @$_REQUEST['action'] === 'register'):
        ?>
		<style>
            p#reg_passmail {
                margin-top: 10px;
                font-size: 14px;
                text-align: center;
                color: #fff;
                display: none;
            }
            #wp-submit {
                margin-top: 0px;
            }
            #old_reg_passmail {
                text-align: center;
                margin-top: 10px;
            }
            #nsl-custom-login-form-main .nsl-container-login-layout-below {
                padding-top: 10px !important;
            }
		</style>
        <script>
            jQuery(document).ready(function ($){
               $('p.submit').after('<br><br><p id="old_reg_passmail">Registration confirmation will be emailed to you</p>');
                $("#registerform #user_login").attr("placeholder", "Username");
            });
        </script>
		<?php
		$message = '';
	endif;

	if ( $GLOBALS['pagenow'] === 'wp-login.php' && @$_REQUEST['action'] === 'lostpassword') {
		$message = '';
	}
	return $message;
}

/***add captcha box***/
add_action('register_form','add_google_captcha_register_form');
function add_google_captcha_register_form(){
	$siteKey = xbox_get_field_value( 'my-theme-options', 'reCaptcha-settings1');
	$secret = xbox_get_field_value( 'my-theme-options', 'reCaptcha-settings2');
	if (xbox_get_field_value( 'my-theme-options', 'enable-recaptcha' ) == 'on' && $siteKey != '' && $secret != '' ) {
		echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" style="transform:scale(0.9); transform-origin:0;" data-sitekey="' . $siteKey . '"></div>';
	}
	echo '<script>
    jQuery(document).ready(function ($) {
        $("#user_email").attr("placeholder", "Email");
       })</script>';
}
add_filter( 'registration_errors', 'add_registration_check_captcha' );
function add_registration_check_captcha($errors) {
	$siteKey = xbox_get_field_value( 'my-theme-options', 'reCaptcha-settings1' );
	$secret  = xbox_get_field_value( 'my-theme-options', 'reCaptcha-settings2' );
	if ( xbox_get_field_value( 'my-theme-options', 'enable-recaptcha' ) == 'on' && $siteKey != '' && $secret != '' ) {
		$captcha  = sanitize_text_field( $_POST["g-recaptcha-response"] );
		$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha );
		$response = json_decode( $response["body"], true );

		if ( isset( $response['error-codes'] ) && $response['error-codes'] ) {
			$errors = new WP_Error();
			$errors->add( 'error_register_google_captcha', __( 'Please click on the reCAPTCHA box.' ) );
		}
	}
    return $errors;
}
/*** [end] add captcha box***/

/*** Reset button text, label, message***/
add_action('resetpass_form', 'reset_button_text');
function reset_button_text(){ ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#resetpassform p.submit input#wp-submit').val("Set Password");
            $('p.reset-pass').css('display', 'none');
            $('#resetpassform label').text('Set a password');
        });
    </script>
<?php
 }
/*** [end] reset button text, label, message***/

/**** Change message set password page***/
add_filter( 'login_message', 'password_set_message' );
function password_set_message(){
    if($GLOBALS['pagenow'] === 'wp-login.php' && $_REQUEST['action'] === 'resetpass'):
	    $message = '<p class="message reset-pass">Your password has been set. You can start using your account now. ';
        $message .= '<a href="' . wp_login_url() . '">Login</a>';
        $message .= '</p>';
    endif;
	return $message;
}
/**** [end] change message set password page***/

/**** Remove form and add a notification text****/
add_action( 'lostpassword_form', 'lostpass_notification_text' );
function lostpass_notification_text(){
	if(@$_GET['send'] == true) :?>
    <style>
        input#user_login,
        label[for=user_login],
        input#wp_submit{
            display:none;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
           $('p.submit').text('We have send you an email with the link to set your new password.');
        });
    </script>
    <?php
    endif;
}
/**** [end] Remove form and add a notification text****/



/** Clean BD */
$users = get_users( [
    'blog_id'      => $GLOBALS['blog_id'],
    'orderby'      => 'login',
    'order'        => 'ASC',
    'paged'        => 1,
    'count_total'  => false,
    'fields'       => ['ID', 'user_activation_key', 'user_registered'],
    'has_published_posts' => null,
    'date_query'   => array()
] );
$i = 0;
require_once ABSPATH . 'wp-admin/includes/user.php';
foreach( $users as $user ){
	$method = get_user_meta($user->ID, 'method_register', true);
    if((bool)$user->user_activation_key == true && $method == 'standart'){
        $current_time_stamp = time();
        $reg_date_in_seconds = strtotime($user->user_registered);

        if(($current_time_stamp - $reg_date_in_seconds) > 604800 ){ //
            wp_delete_user( $user->ID );
        }

        if($i>30)break;
    }
}