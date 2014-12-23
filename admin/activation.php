<?php

// Activation function
function lolint_activation(){
	global $wpdb;
	
	// Check for the api-key option to determine if settings need to be added
	$current_settings = get_option( 'lolint_api_key', false );

	 	if ( ! $current_settings ) {
			update_option( 'lolint_api_key', '' );
			update_option( 'lolint_championicon_width', '50' );
			update_option( 'lolint_spellicon_width', '50' );
			update_option( 'lolint_itemicon_width', '50' );
			update_option( 'lolint_masteryicon_width', '50' );
			update_option( 'lolint_region', 'na' );
			update_option( 'lolint_runeicon_width', '50' );
	 	}
	
	// Needed for dbdelta
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// Create the static information tables
	$sql="CREATE TABLE IF NOT EXISTS `".LOLINT_CHAMPION."` (
		`autoinc` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(11) NOT NULL,
		`lolkey` varchar(50) NOT NULL,
		`name` varchar(50) NOT NULL,
		`title` text NOT NULL,
		`simplified_name` varchar(50) NOT NULL,
		PRIMARY KEY (`autoinc`),
		UNIQUE KEY `id` (`id`)
		) DEFAULT CHARSET=utf8;";
	dbdelta($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".LOLINT_RUNE."` (
		`autoinc` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(11) NOT NULL,
		`description` text NOT NULL,
		`name` varchar(100) NOT NULL,
		`image` varchar(20) NOT NULL,
		`tier` tinyint(1) NOT NULL,
		`type` varchar(10) NOT NULL,
		`stats` text CHARACTER SET utf8 NOT NULL,
		`simplified_name` varchar(100) NOT NULL,
		PRIMARY KEY (`autoinc`)
		) DEFAULT CHARSET=utf8;";
	dbdelta($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".LOLINT_MASTERY."` (
		`autoinc` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(11) NOT NULL,
		`description` text NOT NULL,
		`name` varchar(50) NOT NULL,
		`simplified_name` varchar(50) NOT NULL,
		PRIMARY KEY (`autoinc`)
		) DEFAULT CHARSET=utf8;";
	dbdelta($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".LOLINT_ITEM."` (
		`autoinc` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(11) NOT NULL,
		`plaintext` text,
		`description` text,
		`name` varchar(50) NOT NULL,
		`lolgroup` varchar(50) DEFAULT NULL,
		`simplified_name` varchar(50) NOT NULL,
		PRIMARY KEY (`autoinc`)
		) DEFAULT CHARSET=utf8;";
	dbdelta($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".LOLINT_SPELL."` (
		`autoinc` int(11) NOT NULL AUTO_INCREMENT,
		`id` int(11) NOT NULL,
		`description` text NOT NULL,
		`name` varchar(50) NOT NULL,
		`lolkey` varchar(50) NOT NULL,
		`summonerlevel` int(11) NOT NULL,
		`simplified_name` varchar(50) NOT NULL,
		PRIMARY KEY (`autoinc`)
		) DEFAULT CHARSET=utf8;";
	dbdelta($sql);
	
	// No API key so function doesn't work - lolint_get_static();
	
	// Triggers a redirect on activation
	add_option('lolint_do_activation_redirect', true);
}

// Redirect function
function lolint_redirect(){
	// On activation, redirect user to settings page with a message for the next steps
	if (get_option('lolint_do_activation_redirect', false)) {
        delete_option('lolint_do_activation_redirect');
        wp_redirect(admin_url( 'admin.php?page=lolint&lolintmsg=RW50ZXIgQVBJIGtleSBhbmQgY2xpY2sgU2F2ZSBTZXR0aW5ncy4gVGhlbiBjbGljayBVcGRhdGUgTG9MIFN0YXRpYyBEYXRhIGFib3ZlLiBUaGVuIGVhY2ggb2YgdGhlIHVwZGF0ZSBpY29uIGJ1dHRvbnMgYmVsb3cgdG8gcmV0cmlldmUgaWNvbnMu' ));
    }
}