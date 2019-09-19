<?php

//TODO: create page for soapbox routes

/**
 * action admin_menu
 */
add_action('admin_menu', 'soapbox_profile_create_profile_menu');

/**
 * Create profile menu
 *
 * @return void
 */
function soapbox_profile_create_profile_menu() {
    add_menu_page(
        __('Soapbox'),
        __('Soapbox Profile'),
        'manage_options',
        'soapbox-profile',
        'soapbox_profile_profiles'
    );

    add_submenu_page(
        'soapbox-profile',
        __('Parties'),
        __('Parties'),
        'manage_options',
        'soapbox-profile-parties',
        'soapbox_profile_parties'
    );

    add_submenu_page(
        'soapbox-profile',
        __('Party Profiles'),
        __('Party Profiles'),
        'manage_options',
        'soapbox-profile-party-profiles',
        'soapbox_profile_party_profiles'
    );
}

function soapbox_profile_profiles() {
    echo 'testing';
}

function soapbox_profile_parties() {
    echo 'party on, wayne';
}

function soapbox_profile_party_profiles() {
    echo 'party profiles';
}
