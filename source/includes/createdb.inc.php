<?php

/**
 * Create DB
 *
 * @return void
 */
function sandbox_profile_create_db() {
	global $wpdb;
	$version = get_option( 'sandbox_profile_version', '1.0.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_prefix = $wpdb->prefix;
	$table_parties = $table_prefix . 'sandbox_profile_parties';
	$table_party_members = $table_prefix . 'sandbox_profile_party_members';

	$sql = "CREATE TABLE $table_parties (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		hash char(32) NOT NULL,
		max_invites int NOT NULL DEFAULT 1,
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		updated timestamp DEFAULT CURRENT_TIMESTAMP,
		expires timestamp NULL,
		UNIQUE KEY id (ID)
	) $charset_collate;
	CREATE TABLE $table_party_members (
		ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NULL,
		invitation_id bigint(20) unsigned NULL,
		status enum(invited', 'activated', 'approved') DEFAULT 'invited',
		created timestamp DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY id (ID),
	CONSTRAINT fk_sandbox_profile_invitations_user_id
		FOREIGN KEY (user_id)
		REFERENCES {$wpdb->prefix}users(ID)
		ON DELETE CASCADE,
	CONSTRAINT fk_sandbox_profile_invitations_invitation_id
		FOREIGN KEY (invitation_id)
		REFERENCES {$table_invitations}(ID)
		ON DELETE CASCADE    
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
