<?php
/****hidden xbox options menu****/
add_action('admin_menu', 'remove_xbox_options_menu', 999);
function remove_xbox_options_menu() {
	remove_menu_page( 'amg-options'); //hidden mass grabber menu
	remove_menu_page( 'vicetemple-single-options'); //hidden single embedder menu
	remove_menu_page( 'my-theme-options'); //hidden theme option
	remove_menu_page( 'vicetemplepl-options'); //hidden player menu
	remove_menu_page( 'amve-options-page'); //hidden mass embedder menu
	remove_menu_page( 'popup-options'); //hidden popup option menu
}/****end hidden xbox options menu****/
