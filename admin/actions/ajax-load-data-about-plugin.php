<?php
//show data about plugins and his updates
add_action('wp_ajax_show_data_about_plugins', 'show_data_about_plugins');
function show_data_about_plugins() {
    $data_about_all_plugins = [];

	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	if (!function_exists('get_plugins')) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugins = get_plugins();
	$plugins_keys = array_keys($plugins);

	$j = 0;
	foreach ($plugins as $plugin) {
		$plugin_root_file = $plugins_keys[$j];
		$plugin_name      = $plugin['Name'];
		$plugin_author    = $plugin['Author'];
		$plugin_version   = $plugin['Version'];
		$plugin_status    = is_plugin_active( $plugin_root_file ) ? 'active' : 'inactive';
		if($_POST['name'] == $plugin_name && ($plugin_author == 'Vicetemple' || $plugin_author == 'Citadel Solutions B.V.')) $status = $plugin_status;
		$j++;
	}
    foreach ($plugins as $key => $val) {
        if($key == $_POST['archive'] . '/'. $_POST['archive'] .'.php') {

            $version_active_plugin = $val['Version'];
            $old_version = $val['Version'];
            if($_POST['version'] > $version_active_plugin) $new_version = $_POST['version'];
        }
    }
    $data = [
        'status' => $status,
        'new_version' => $new_version,
        'name' => $_POST['name'],
        'description' => $_POST['desc'],
        'author' => $_POST['author'],
        'version' => $old_version,
        'archive_name' => $_POST['archive'],
        'additional_version' => $_POST['version']
    ];

    $data_about_all_plugins[$_POST['name']] = [
            'new_version' => $new_version,
            'version' => $old_version,
            'additional_version' => $_POST['version'],
            'archive_name' => $_POST['archive'],
    ];

    if(get_option('vicetemple_update_plugin') === false) {
        update_option('vicetemple_update_plugin', $data_about_all_plugins);
    } else {
        $old_data_about_all_plugins = get_option('vicetemple_update_plugin');
        $result = array_merge($old_data_about_all_plugins, $data_about_all_plugins);
        update_option('vicetemple_update_plugin', $result);
    }
	wp_send_json($data);
	wp_die();
}

add_action( 'admin_notices', 'vicetemple_admin_notice_updates_plugin');
function vicetemple_admin_notice_updates_plugin() {
    $plugin_data = get_option('vicetemple_update_plugin');
    $is_core_page      = 'toplevel_page_arc-dashboard' === get_current_screen()->base ? true : false;
    $updates_count = 0;

    if( !function_exists('get_plugins') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    foreach($plugin_data as $plugin_dt) {
        if(is_plugin_active($plugin_dt['archive_name'] . '/'. $plugin_dt['archive_name'] .'.php')) {
            if ($plugin_dt['additional_version'] > $plugin_dt['version']) {
                $updates_count++;
            }
        }
    }
    if($updates_count > 0) {
        echo '<div class="notice notice-success is-dismissible">';
        if (!$is_core_page) {
            echo '<p>New version is available:<br>';
            foreach ($plugin_data as $key => $plugin_dt) {
                if(is_plugin_active($plugin_dt['archive_name'] . '/'. $plugin_dt['archive_name'] .'.php')) {
                    if ($plugin_dt['additional_version'] > $plugin_dt['version']) {
                        if ($key == 'Dev Core Plugin') $key = 'PornX Core';
                        echo '&#10149; ' . esc_html($key) . ' <strong>v' . esc_html($plugin_dt['additional_version']) . '</strong> &nbsp;&bull;&nbsp; <a href="admin.php?page=arc-dashboard">Upgrade</a></p>';
                    }
                }
            }
        } else {
            echo '<p>New versions are available for some of your Vicetemple products.</p>';
            echo '<p><i class="fa fa-arrow-down" aria-hidden="true"></i>Scroll down the page and click on Update to update your products to the most recent version.<i class="fa fa-arrow-down" aria-hidden="true"></i></p>';
        }
        echo '</div>';
    }

}

//show data about theme and her update
add_action('wp_ajax_show_data_about_current_theme', 'show_data_about_current_theme');
function show_data_about_current_theme() {
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	$currentTheme = wp_get_theme();
	$curThemeName = $currentTheme->get('Name');
	for($i = 0; $i < @count($_POST['themes']); $i++) {
		if($curThemeName == $_POST['themes'][$i]['name']) {
			//theme = wp_get_theme($_POST['themes'][$i]['archive_name']);
			if($currentTheme->get('Status') == 'publish') {
				if($_POST['themes'][$i]['version'] > $currentTheme->get('Version')) {
					$new_version = $_POST['themes'][$i]['version'];
				}
				$data[] = [
						'name' => $currentTheme->get('Name'),
						'description' => $currentTheme->get('Description'),
						'version' => $currentTheme->get('Version'),
						'status' => $currentTheme->get('Status'),
						'archive_name' => $_POST['themes'][$i]['archive_name'],
						'new_version' => $new_version,
						'flag' => 'yes'
					];
					update_option('vicetemple_update_theme', json_encode($data));
			} 
		} else {
			$data[] = [
				'name' => $_POST['themes'][$i]['name'],
				'description' => $_POST['themes'][$i]['description'],
				'version' => $_POST['themes'][$i]['version'],
				'status' => 'draft',
				'archive_name' => $_POST['themes'][$i]['archive_name'],
				'new_version' => null
			];
		}
	}
	wp_send_json($data);
	wp_die();
}

add_action( 'admin_notices', 'vicetemple_admin_notice_updates_theme');
function vicetemple_admin_notice_updates_theme() {
	$theme_data = json_decode(get_option('vicetemple_update_theme', true));
	$is_core_page      = 'toplevel_page_arc-dashboard' === get_current_screen()->base ? true : false;
	for($i = 0; $i < @count($theme_data); $i++) {	
		if(($theme_data[$i]->flag !== 'indefined' || $theme_data[$i]->flag !== null) && $theme_data[$i]->new_version !== null) {
			echo '<div class="notice notice-success is-dismissible">';
			if($theme_data[$i]->flag == 'yes') {				
				if ($is_core_page) {
					echo '<p>New version of PornX theme is available.</p>';
					echo '<p><i class="fa fa-arrow-down" aria-hidden="true"></i> Scroll down the page and click on Update to update your products to the most recent version. <i class="fa fa-arrow-down" aria-hidden="true"></i></p>';
				} else {
					echo '<p>New version of PornX theme is available: &#10149; ' . esc_html( $theme_data[$i]->name ) . ' <strong>v' . esc_html( $theme_data[$i]->new_version ) . '</strong> &nbsp;&bull;&nbsp; <a href="admin.php?page=arc-dashboard">Upgrade</a></p>';
				}
			}
			echo '</div>';
		}		
		
	}
}

{
	/***** DASHBOARD - LIKES|DISLIKES TAB****/
//filter likes
	add_action( 'wp_ajax_filter_likes', 'filter_likes' );
	function filter_likes() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			wp_die( 'Busted!' );
		}

		$sort = $_POST['sort'];
		$name = $_POST['name'];

		$data = get_posts( [
			'numberposts'      => - 1,
			'meta_key'         => $name,
			'orderby'          => 'meta_value_num',
			'order'            => $sort,
			'post_type'        => 'post',
			'suppress_filters' => true,
		] );
		foreach ( $data as $d ) {
			$thumb   = get_post_meta( $d->ID, 'thumb', true );
			$like    = get_post_meta( $d->ID, 'likes_count', true );
			$dislike = get_post_meta( $d->ID, 'dislikes_count', true );
			$out[]   = [
				'id'         => $d->ID,
				'post_title' => $d->post_title,
				'guid'       => $d->guid,
				'thumb'      => $thumb,
				'likes'      => $like,
				'dislikes'   => $dislike
			];
		}
		wp_send_json( $out );
		wp_die();
	}

//draft
	add_action( 'wp_ajax_draft_dislikePost', 'draft_dislikePost' );
	function draft_dislikePost() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Busted!' );
		}

		$postId = $_POST['postID'];
		wp_update_post( [
			'ID'          => $postId,
			'post_status' => 'draft'
		] );
		wp_send_json( $postId );
		wp_die();
	}

//delete
	add_action( 'wp_ajax_delete_dislikePost', 'delete_dislikePost' );
	function delete_dislikePost() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Busted!' );
		}

		global $wpdb;
		$table = $wpdb->prefix . 'reportMsg';
		if ( isset( $_POST['report'] ) && ! empty( $_POST['report'] ) ) {
			$query = "DELETE FROM {$table} WHERE `postId`=" . $_POST['postID'];
			$wpdb->query( $query );
		}
		$postId = $_POST['postID'];
		wp_delete_post( $postId );
		wp_send_json( $postId );
		wp_die();
	}
}
{
	/***** DASHBOARD - SUPPORT TAB****/
//filter data from database logs
	add_action( 'wp_ajax_filter_supportMsg', 'filter_supportMsg' );
	function filter_supportMsg() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			wp_die( 'Busted!' );
		}

		global $wpdb;
		$table = $wpdb->prefix . 'supportMsg';
		$type  = $_POST['type'];
		$query = "SELECT * FROM $table WHERE `type` = '" . $type . "'";
		$msg   = $wpdb->get_results( $query );
		$data  = [
			'msg' => $msg
		];
		wp_send_json( $data );
		wp_die();
	}

//delete data from database logs
	add_action( 'wp_ajax_delete_data_from_support_table', 'delete_data_from_support_table' );
	function delete_data_from_support_table() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			wp_die( 'Busted!' );
		}
		global $wpdb;
		$table = $wpdb->prefix . 'supportMsg';
		$query = "DELETE FROM $table";
		$wpdb->query( $query );
		wp_die();
	}
}

{
	/***** DASHBOARD - BAN TAB****/
	//add to ban [start]
	add_action('wp_ajax_add_to_ban', 'add_to_ban');
	function add_to_ban(){
		if(empty($_POST['nonce'])){
			wp_die( 0 );
		}
		$check_ajax_referer = check_ajax_referer( 'ajax-nonce', 'nonce', false );
		$current_user_can   = current_user_can( 'edit_others_pages' );

		if ( $check_ajax_referer && $current_user_can ) {
			$list_ip_input = explode( PHP_EOL, $_POST['list_ip']);
			foreach($list_ip_input as $k=>$v){
				if($v == false) unset($list_ip_input[$k]);
			}
			$create_option = add_option( 'ban_ip', $list_ip_input );
			if($create_option == false){
				$old_list_ip = get_option( 'ban_ip');
				$new_list_ip = array_merge($old_list_ip, $list_ip_input);
				update_option( 'ban_ip', $new_list_ip );
			}
		} else {
			wp_die( 'Forbidden', '', 403 );
		}
		wp_send_json($list_ip_input);
		wp_die();
	}/** add to ban [end] */

	//delete from ban [start]
	add_action('wp_ajax_delete_from_ban', 'delete_from_ban');
	function delete_from_ban(){
		if(empty($_POST['nonce'])){
			wp_die( 0 );
		}

		$check_ajax_referer = check_ajax_referer( 'ajax-nonce', 'nonce', false );
		$current_user_can   = current_user_can( 'edit_others_pages' );

		if ( $check_ajax_referer && $current_user_can ) {
			$all_ban_ip = get_option('ban_ip');
			for($i=0; $i >= 0; $i++){
				if(($key = array_search($_POST['ip_for_delete'],$all_ban_ip)) !== FALSE){
					unset($all_ban_ip[$key]);
				}
				if($key === false) break;
			}

			$new_value = $all_ban_ip;
			update_option( 'ban_ip', $new_value );

		} else {
			wp_die( 'Forbidden', '', 403 );
		}
		wp_send_json(str_replace(['.', ':', '/'], '', $_POST['ip_for_delete']));
		wp_die();
	}
	//delete from ban [end]

	//delete all IP from bab [start]
	add_action('wp_ajax_delete_all_ip_from_ban', 'delete_all_ip_from_ban');
	function delete_all_ip_from_ban(){
		if(empty($_POST['nonce'])){
			wp_die( 0 );
		}
		$check_ajax_referer = check_ajax_referer( 'ajax-nonce', 'nonce', false );
		$current_user_can   = current_user_can( 'edit_others_pages' );

		if ( $check_ajax_referer && $current_user_can ) {
			$res = delete_option( 'ban_ip' );

		} else {
			wp_die( 'Forbidden', '', 403 );
		}

		if($res){
			wp_send_json('true');
			wp_die();
		}
	}
	//delete all IP from bab [end]
}
{
	/***** DASHBOARD - REPORTS TAB****/
	//send reports
	add_action('wp_ajax_ARC_send_report', 'ARC_send_report');
	add_action('wp_ajax_nopriv_ARC_send_report', 'ARC_send_report');
	function ARC_send_report() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');

		global $wpdb;
		$table = $wpdb->prefix. 'reportMsg';
		$msg_data = [
			'date' => date("Y-m-d H:i:s"),
			'msg' => strip_tags($_POST['msg']),
			'type' => $_POST['type'],
			'postId' => $_POST['post_id'],
		];
		$types = ['%s', '%s', '%s', '%s'];
		$wpdb->insert($table, $msg_data, $types);

		/****letter for admin****/
		send_letter_report_video($_POST['type']);
		/****end letter for admin****/

		wp_send_json('success');
		wp_die();
	}

	//sort reports
	add_action('wp_ajax_filter_reportMsg', 'filter_reportMsg');
	function filter_reportMsg() {
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			wp_die( 'Busted!' );
		}

		global $wpdb;
		$table = $wpdb->prefix . 'reportMsg';
		$type = $_POST['type'];
		$query = "SELECT * FROM $table WHERE `type` = '" . $type . "' ORDER BY date DESC";
		$msg = $wpdb->get_results($query);
		foreach ($msg as $value){
			if(get_post_status($value->postId) == 'draft') continue;
			else {
				if(stripos($value->postId, '&user') !== false) {
					$videoTitle = get_userdata((int)str_replace('&user', '', $value->postId))->display_name;
				} else {
					$videoTitle = get_the_title($value->postId);

				}
				if ( $value->type == 'notWork' ) {
					$badge = 'danger';
					$text  = __( 'Video does not play', 'arc' );
				}
				if ( $value->type == 'violent' ) {
					$badge = 'danger';
					$text  = __( 'Violent or Harmful Acts', 'arc' );
				}
				if($value->type == 'violentUser') {
					$badge = 'danger';
					$text = 'This user\'s content violates '.get_bloginfo('name'). '\'s Terms';
				}
				if ( $value->type == 'underage' ) {
					$badge = 'warning';
					$text  = __( 'Underage', 'arc' );
				}
				if ( $value->type == 'underagePhoto' || $value->type == 'underagePost') {
					$badge = 'warning';
					$text  = __( 'Potentially features a Minor', 'arc' );
				}
				if($value->type == 'underageUser') {
					$badge = 'warning';
					$text = __('This user appears to be underage','arc');
				}
				if ( $value->type == 'other' || $value->type == 'otherUser') {
					$badge = 'secondary';
					$text  = __( 'Other', 'arc' );
				}
				if ( $value->type == 'otherPhoto' || $value->type == 'otherPost' ) {
					$badge = 'secondary';
					$text  = __( 'Otherwise Inappropriate or Objectionable', 'arc' );
				}
				if ( $value->type == 'wrong' ) {
					$badge = 'info';
					$text  = __( 'Inappropriate Content', 'arc' );
				}
				if($value->type == 'wrongUser') {
					$badge = 'info';
					$text = __('This user is impersonating someone else','arc');
				}
				if ( $value->type == 'spam' ) {
					$badge = 'primary';
					$text  = __( 'Spam or misleading', 'arc' );
				}
				if($value->type == 'spamUser') {
					$badge = 'primary';
					$text = __('This user is spamming','arc');
				}
				$data[] = [
					'title' => $videoTitle,
					'badge' => $badge,
					'textBadge' => $text,
					'date' => $value->date,
					'id' => $value->id,
					'msg' => $value->msg,
					'postId' => $value->postId
				];
			}
		}
		wp_send_json($data);
		wp_die();
	}

	//delete all reports
	add_action('wp_ajax_delete_all_reports', 'delete_all_reports');
	function delete_all_reports() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');
		global $wpdb;
		$table = $wpdb->prefix . 'reportMsg';
		$query = "DELETE FROM $table";
		$wpdb->query($query);
		wp_die();
	}

	//delete one reports
	add_action('wp_ajax_delete_one_report', 'delete_one_report');
	function delete_one_report() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');
		global $wpdb;
		$table = $wpdb->prefix . 'reportMsg';
		$query = "DELETE FROM {$table} WHERE `id` = " . $_POST['reportID'];
		$wpdb->query($query);
		wp_send_json($_POST['reportID']);
		wp_die();
	}

}
{
	/***** DASHBOARD - LOGS TAB****/
    // get data about active plugins for select in logs tab
    add_action('wp_ajax_load_data_about_plugin', 'load_data_about_plugin');
    function load_data_about_plugin() {
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'ajax-nonce'))
            wp_die( 'Busted!');

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugins = get_plugins();
        $plugins_keys = array_keys($plugins);

        $i = 0;
        foreach ($plugins as $plugin) {
            $plugin_root_file   = $plugins_keys[$i];
            $plugin_title       = $plugin['Name'];
            $plugin_author       = $plugin['Author'];
            $plugin_status      = is_plugin_active($plugin_root_file) ? 'active' : 'inactive';
            if ($plugin_status == 'active' && ($plugin_author == 'Vicetemple' || $plugin_author == 'Citadel Solutions B.V.')) {
                $plug_name[] = $plugin_title;
            }
            $i++;
        }

        $data = [
            'plugins' => $plug_name
        ];

        wp_send_json($data);
        wp_die();
    }


	//get all data from database logs for copy to clipboard
	add_action('wp_ajax_get_data_from_log_table', 'get_data_from_log_table');
	function get_data_from_log_table() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');

		global $wpdb;
		$table = $wpdb->prefix . 'vicetempleCoreLogs';

		$query = "SELECT * FROM $table ORDER BY date DESC";
		$logs = $wpdb->get_results($query);

		$data = [
			'logs' => $logs
		];

		wp_send_json($data);
		wp_die();
	}

	//delete data from database logs
	add_action('wp_ajax_delete_data_from_log_table', 'delete_data_from_log_table');
	function delete_data_from_log_table() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');

		global $wpdb;
		$table = $wpdb->prefix . 'vicetempleCoreLogs';

		$query = "DELETE FROM $table";
		$wpdb->query($query);
		wp_die();
	}

	//filter data from database logs
	add_action('wp_ajax_select_filter_data', 'select_filter_data');
	function select_filter_data() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');

		global $wpdb;
		$table = $wpdb->prefix . 'vicetempleCoreLogs';

		$type = $_POST['type'];
		$product = $_POST['product'];

		if($type == 'all') {
			$query = "SELECT * FROM $table WHERE `product` = '" . $product . "'";
		}
		if($product == 'all') {
			$query = "SELECT * FROM $table WHERE `type` = '" . $type . "'";
		}
		if($type !== 'all' && $product !== 'all') {
			$query = "SELECT * FROM $table WHERE `type` = '" . $type . "' AND `product` = '" . $product . "'";
		}

		$logs = $wpdb->get_results($query);

		$data = [
			'logs' => $logs
		];

		wp_send_json($data);
		wp_die();
	}

	add_action('wp_ajax_message_filter_data', 'message_filter_data');
	function message_filter_data() {
		$nonce = $_POST['nonce'];
		if (!wp_verify_nonce($nonce, 'ajax-nonce'))
			wp_die( 'Busted!');

		global $wpdb;
		$table = $wpdb->prefix . 'vicetempleCoreLogs';

		$id = $_POST['id'];
		$text = $_POST['text'];

		if($id == 'message') {
			$query = "SELECT * FROM $table WHERE `message` LIKE '%" . $text . "%'";
		}
		if($id == 'location') {
			$query = "SELECT * FROM $table WHERE `location` LIKE '%" . $text . "%'";
		}

		$logs = $wpdb->get_results($query);

		$data = [
			'logs' => $logs
		];

		wp_send_json($data);
		wp_die();
	}
}


/*****change_demo_scheme_option****/
add_action('wp_ajax_change_demo_scheme_option', 'change_demo_scheme_option');
function change_demo_scheme_option() {
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce'))
		wp_die( 'Busted!');

	set_theme_mod('enable_demos_color_scheme', 'demos');
	die();
}


/*** show changelog information****/
add_action('wp_ajax_show_changelog_info', 'show_changelog_info');
function show_changelog_info() {
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'ajax-nonce'))
        wp_die( 'Busted!');

    $return = '';
    $file_for = (string)$_POST['file_for'] ;
    $ch = curl_init(VICETEMPLECORE_LIC_URL . VICETEMPLECORE_LOGS);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/81.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'product='. $file_for);

    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch,CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    if(curl_exec($ch) === false) {
        curl_close($ch);
        wp_send_json(false);
    } else {
       $CHANGES_LOG = json_decode(curl_exec($ch), ARRAY_A);
       curl_close($ch);
		krsort($CHANGES_LOG);
        foreach($CHANGES_LOG as $k => $v){
            $return .= '<strong>' . $k . '</strong>';
            $return .= '<ul style=" list-style-type: square;
                                margin-left: 20px;
                                margin-right:20px;">';
            foreach($v as $item) {
                $return .= '<li>'. stripslashes($item) . '</li>';
            }
            $return .= '</ul>';
        }
        wp_send_json($return);
    }
}