<?php

class UserRegistration {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'pms_member_subscriptions'; 

        add_action('user_register', array( $this, 'add_user_to_table' ), 10, 2 );
    }
    

    public function add_user_to_table($user_id) {
        
        global $wpdb;
        $existing_user = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $this->table_name WHERE user_id = %d", $user_id));

        
        if (!$existing_user) {
            $wpdb->insert(
                $this->table_name,
                array(
                    'user_id' => $user_id,
                    'start_date'=> date('Y-m-d H:i:s'),
                    'subscription_plan_id' => 1956,
                    'status' => 'active',
                    'payment_profile_id' => '',
                    'payment_gateway' => '',
                    'billing_amount' => 0,
                    'billing_duration' => 0,
                    'billing_duration_unit' => '',
                    'billing_cycles' => 0
                );
               
            );
        }
    }
}


?>