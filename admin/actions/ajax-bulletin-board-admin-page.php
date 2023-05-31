<?php
/** Delete ad in db [start]*/
add_action('wp_ajax_delete_ad_in_db', 'delete_ad_in_db');
function delete_ad_in_db(){
    if(empty($_POST['nonce'])){
        wp_die( 0 );
    }

        global $wpdb;
        $table_bulletin_board = $wpdb->prefix . 'bulletin_board';
        $data_id_for_delete = $_POST['data_id_for_delete'];
        $wpdb->delete(
            $table_bulletin_board,
            array(
                'id' => $data_id_for_delete,
            ),
            array(
                '%d' ,
            )
        );
    wp_send_json($data_id_for_delete);
}
/** Delete ad in db [end]*/

/** Publish ad [start]*/
add_action('wp_ajax_publish_ad', 'publish_ad');
function publish_ad(){
    if(empty($_POST['nonce'])){
        wp_die( 0 );
    }
        global $wpdb;
        $table_bulletin_board = $wpdb->prefix . 'bulletin_board';
        $data_id_for_publish = $_POST['data_id_for_publish'];
        $wpdb->update( $table_bulletin_board,
            array( 'status' => 1 ),
            array( 'id' => $data_id_for_publish )
        );
    wp_send_json($data_id_for_publish);
}
/** Publish ad [end]*/

/** Edit ad [start]*/
add_action('wp_ajax_edit_ad', 'edit_ad');
function edit_ad(){
    if(empty($_POST['nonce'])){
        wp_die( 0 );
    }

        global $wpdb;
        $table_bulletin_board = $wpdb->prefix . 'bulletin_board';
	    $data_id_for_save = $_POST['data_id_for_save'];
        $text_message = $_POST['text_message'];
        $wpdb->update( $table_bulletin_board,
            array( 'text_message' => nl2br($text_message)),
            array( 'id' => $data_id_for_save )
        );
    wp_send_json($text_message);
}
/** Edit ad [end] */


/****display_ads_publication_date***/
add_action('wp_ajax_display_ads_publication_date', 'display_ads_publication_date');
function display_ads_publication_date() {
	if(empty($_POST['nonce'])){
		wp_die( 0 );
	}
		global $wpdb;
		$table_bulletin_board = $wpdb->prefix . 'bulletin_board';
		$ids = $_POST['data_id'];
		foreach ($ids as $id) {
			$sql = "SELECT `publication_date` FROM $table_bulletin_board WHERE `id` = " . $id;
			$res[$id] = date('F j, Y H:i', ($wpdb->get_row($sql)->publication_date - $_POST['offset']*60));
		}

	wp_send_json($res);
}