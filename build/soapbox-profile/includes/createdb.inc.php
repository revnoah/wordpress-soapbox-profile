<?php

/**
 * Create DB
 *
 * @return void
 */
function soapbox_profile_create_db() {
	global $wpdb;
	$version = get_option( 'soapbox_profile_version', '1.0.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_parties = $wpdb->prefix . 'soapbox_profile_parties';
	$table_party_members = $wpdb->prefix . 'soapbox_profile_party_members';
	$table_posts = $wpdb->prefix . 'soapbox_profile_posts';

	$sql = "CREATE TABLE $table_parties (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`desc` text NULL,
		slug varchar(80) NOT NULL,
		logo varchar(120) NULL,
		banner varchar(120) NULL,
		website varchar(120) NULL,
		twitter varchar(80) NULL,
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID)
	) $charset_collate;
	CREATE TABLE $table_party_members (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		party_id bigint(20) unsigned NOT NULL,
		`name` varchar(255) NOT NULL,
		title enum('member', 'leader') DEFAULT 'member',
		website varchar(120) NULL,
		twitter varchar(80) NULL,
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
	CONSTRAINT fk_soapbox_profile_party_id
		FOREIGN KEY (party_id)
		REFERENCES {$table_parties}(ID)
		ON DELETE CASCADE
	) $charset_collate;
	CREATE TABLE $table_posts (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		party_member_id bigint(20) unsigned NOT NULL,
		title varchar(255) NOT NULL,
		`desc` text NULL,
		`url` varchar(255) NOT NULL,
		thumbnail varchar(255) NULL,
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
	CONSTRAINT fk_soapbox_profile_party_member_id
		FOREIGN KEY (party_member_id)
		REFERENCES {$table_party_members}(ID)
		ON DELETE CASCADE
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
