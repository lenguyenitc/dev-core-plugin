<?php
function custom_admin_init() {
	if(isset($_POST["custom_config"])) {
		custom_options_validate($_POST["custom_config"]);
	}
	add_settings_section('custom_permalinks', 'Authentication Permalinks', 'custom_permalinks_section', 'permalink');
	add_settings_field('custom_login_url', 'Login URL', 'custom_login_url_input', 'permalink', 'custom_permalinks');
	add_settings_field('custom_register_url', 'Registration URL', 'custom_register_url_input', 'permalink', 'custom_permalinks');
	add_settings_field('custom_lostpassword_url', 'Lost Password URL', 'custom_lostpassword_url_input', 'permalink', 'custom_permalinks');
	add_settings_field('custom_logout_url', 'Logout URL', 'custom_logout_url_input', 'permalink', 'custom_permalinks');

	add_settings_section('custom_redirects', 'Authentication Redirects', 'custom_redirects_section', 'permalink');
	add_settings_field('custom_login_redirect', 'Login Redirect URL', 'custom_login_redirect_input', 'permalink', 'custom_redirects');
	add_settings_field('custom_logout_redirect', 'Logout Redirect URL', 'custom_logout_redirect_input', 'permalink', 'custom_redirects');
}

function custom_options_validate($input) {
	$options = get_option('custom_config');
	if(!is_array($options)) {
		$options = array();
	}
	$params = array('login', 'register', 'lostpassword', 'logout',
		"redirect_login", "redirect_logout"
	);
	foreach($params as $action) {
		$value = trim($input[$action]);
		if(!empty($value)) {
			$options[$action] = "/".ltrim($value, "/");
		} else {
			$options[$action] = null;
		}
	}
	update_option("custom_config", $options);
}

function custom_permalinks_section() {
}

function custom_redirects_section() {
}

function custom_login_url_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_login_url' name='custom_config[login]' size='40' type='text' value='<?php esc_attr_e($options["login"]) ?>' placeholder="/wp-login.php" />
	<?php
}

function custom_register_url_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_register_url' name='custom_config[register]' size='40' type='text' value='<?php esc_attr_e($options["register"]) ?>' placeholder="/wp-login.php?action=register" />
	<?php
}

function custom_lostpassword_url_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_lostpassword_url' name='custom_config[lostpassword]' size='40' type='text' value='<?php esc_attr_e($options["lostpassword"]) ?>' placeholder="/wp-login.php?action=lostpassword" />
	<?php
}

function custom_logout_url_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_logout_url' name='custom_config[logout]' size='40' type='text' value='<?php esc_attr_e($options["logout"]) ?>' placeholder="/wp-login.php?action=logout" />
	<?php
}

function custom_login_redirect_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_login_redirect' name='custom_config[redirect_login]' size='40' type='text' value='<?php esc_attr_e($options["redirect_login"]) ?>' placeholder="/wp-admin/" />
	<?php
}

function custom_logout_redirect_input() {
	$options = get_option('custom_config');
	?>
	<code><?php esc_html_e(site_url()) ?></code>
	<input id='custom_logout_redirect' name='custom_config[redirect_logout]' size='40' type='text' value='<?php esc_attr_e($options["redirect_logout"]) ?>' placeholder="/" />
	<?php
}
