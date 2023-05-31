<?php

function custom($url = null) {
	$custom = array(
		'login' => null,
		'logout' => null,
		'register' => null,
		'lostpassword' => null
	);

	$config = get_option("custom_config");

	if(is_array($config)) {
		$custom = $config;
	}

	$custom = apply_filters("custom", $custom);

	if($url === null) {
		return $custom;
	} elseif(isset($custom[$url]) and $custom[$url]) {
		return $custom[$url];
	} else {
		return false;
	}
}

function custom_sort($a, $b) {
	if(strlen($a) < strlen($b)) {
		return 1;
	} else {
		return -1;
	}
}

/*function custom_deactivate() {
	global $wp_rewrite;
	delete_option("custom_config");
	remove_action('generate_rewrite_rules', 'custom_generate_rewrite_rules');
	$wp_rewrite->flush_rules();
}*/

function custom_init_urls() {
	foreach(custom() as $k => $rewrite) {
		if(!is_null($rewrite)) {
			add_filter($k."_url", "custom_".$k."_url");
		}
	}

	if(custom("redirect_login")) {
		add_filter("login_redirect", "custom_login_redirect");
	}

	add_filter("site_url", "custom_site_url", 10, 3);
	add_filter("wp_redirect", "custom_wp_redirect", 10, 2);
}

function custom_login_redirect($url) {
	return site_url().custom("redirect_login");
}

function custom_wp_redirect($url, $status) {

	$login = custom("login");

	if(!$login) {
		return $url;
	}

	$trigger = array(
		"wp-login.php?checkemail=registered",
		"wp-login.php?checkemail=confirm"
	);

	foreach($trigger as $t) {
		if($url == $t) {
			return str_replace("wp-login.php", site_url().$login, $url);
		}
	}

	return $url;
}

function custom_site_url($url, $path, $scheme = null) {

	$from = array(
		'lostpassword' => '/wp-login.php?action=lostpassword',
		'register' => '/wp-login.php?action=register',
		'logout' => '/wp-login.php?action=logout',
		'login' => '/wp-login.php',
	);

	foreach($from as $k => $find) {
		if(custom($k)) {
			$url = str_replace($find, custom($k), $url);
		}
	}

	return $url;
}

function custom_generate_rewrite_rules() {
	global $wp_rewrite;

	$rewrite = custom();
	uasort($rewrite, "custom_sort");

	$from = array(
		'login' => 'wp-login.php',
		'lostpassword' => 'wp-login.php?action=lostpassword',
		'register' => 'wp-login.php?action=register',
		'logout' => 'wp-login.php?action=logout'
	);

	$non_wp_rules = array();

	// @todo: remove this
	unset($rewrite["registration"]);

	foreach(array_keys($from) as $k) {
		if(isset($rewrite[$k]) && !is_null($rewrite[$k])) {
			$non_wp_rules[ltrim($rewrite[$k], "/")] = $from[$k];
		}
	}

	$wp_rewrite->non_wp_rules = $non_wp_rules + $wp_rewrite->non_wp_rules;
}

function custom_login_url($login_url, $redirect = "") {
	$login_url = site_url( custom('login') );

	if ( !empty($redirect) ) {
		$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
	}

	return $login_url;
}

function custom_register_url($url) {
	return site_url( custom('register') );
}

function custom_lostpassword_url($lostpassword_url, $redirect = "") {
	$args = array();
	if ( !empty($redirect) ) {
		$args['redirect_to'] = $redirect;
	}

	$lostpassword_url = add_query_arg( $args, site_url( custom('lostpassword') ) );
	return $lostpassword_url;
}

function custom_logout_url($redirect = "") {
	$args = array();

	if ( custom("redirect_logout") ) {
		$args['redirect_to'] = site_url().custom("redirect_logout");
	} elseif ( !empty($redirect) ) {
		$args['redirect_to'] = site_url();
	}

	$logout_url = add_query_arg($args, site_url( custom('logout') ));
	$logout_url = wp_nonce_url( $logout_url, 'log-out' );

	return $logout_url;
}

function custom_init_redirect() {

	if(!isset($_SERVER["REQUEST_URI"])) {
		return;
	}

	$file = basename($_SERVER["REQUEST_URI"]);

	if(substr($file, 0, 12) != "wp-login.php") {
		return;
	}

	if(isset($_GET["action"])) {
		$action = $_GET["action"];
	} else {
		$action = "login";
	}

	if(isset($_GET["redirect_to"])) {
		$redirect = $_GET["redirect_to"];
	} else {
		$redirect = "";
	}

	if($action == "login" && custom("login")) {
		$url = custom_login_url("", $redirect);
	} elseif($action == "lostpassword" && custom("lostpassword")) {
		$url = custom_lostpassword_url("", $redirect);
	} elseif($action == "register" && custom("register")) {
		$url = custom_register_url("");
	} elseif($action == "logout" && custom("logout")) {
		$url = custom_logout_url($redirect);
	} else {
		$url = null;
	}

	if($url) {
		wp_redirect($url);
		exit;
	}
}