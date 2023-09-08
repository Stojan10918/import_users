<?php

/*
Plugin Name:	Import and Endpooint for Users
Description:	Using this plugin you will be able to iport users
Version:		1.0.0
Author:			Someone
*/


if ( ! defined( 'ABSPATH' ) ) 
exit;

require_once( __DIR__ . '/includes/class-import-members.php' );
require_once( __DIR__ . '/includes/class-members-endpoint.php');
require_once( __DIR__ . '/includes/class-user-registration.php');


add_action('plugins_loaded', 'plugin_init');

function plugin_init() {  
    $member_importer = new MemberImporter();
    $members_endpoint = new MembersEndpoint();
    $user_registration = new UserRegistration();
}

?>