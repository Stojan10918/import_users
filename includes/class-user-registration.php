<?php

class UserRegistration {
    private $table_name;

    public function __construct() {
        global $wpdb;
        //$this->table_name = $wpdb->prefix . 'pms_member_subscriptions'; 

        add_action('user_register', array( $this, 'add_user_to_table' ), 10, 2 );
    }
    

    public function add_user_to_table($user_id, $userdata) {
        // Check if the user already exists in the custom table
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'pms_member_subscriptions';

        $existing_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE user_id = %d", $user_id));

        //$existing_user = $wpdb->get_var($wpdb->prepare("SELECT * FROM $this->table_name WHERE user_id = %d ", $user_id));

        // If the user doesn't exist in the custom table, insert them with free membership
        if (!$existing_record) {

           
            $current_date = current_time('Y-m-d H:i:s');

            $data = array(
                    'user_id' => $user_id,
                    'start_date'=> $current_date,
                    'subscription_plan_id' => 1956,
                    'status' => 'active',
                    'payment_profile_id' => '',
                    'payment_gateway' => '',
                    'billing_amount' => 0,
                    'billing_duration' => 0,
                    'billing_duration_unit' => '',
                    'billing_cycles' => 0,
                    'user_login' => $user->user_login,
                    'user_email' => $user->user_email,
            );

            $wpdb->insert($this->table_name, $data);
        }
    }
}


?>