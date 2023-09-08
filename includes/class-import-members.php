<?php

class MemberImporter {
    public function __construct() {
      
        add_action('admin_init', array($this, 'import_free_members'));

    }

    public function import_free_members() {
        if (isset($_GET['import_free_members']) && current_user_can('administrator')) {
         

            global $wpdb;

            $subscriber_role = 'subscriber';
            $table_name = $wpdb->prefix . 'pms_member_subscriptions';
            $current_date = date('Y-m-d H:i:s');
            $fixed_values = array(
                'subscription_plan_id' => 1956,
                'status' => 'active',
                'payment_profile_id' => '',
                'payment_gateway' => '',
                'billing_amount' => 0,
                'billing_duration' => 0,
                'billing_duration_unit' => '',
                'billing_cycles' => 0
            );

            $subscribers = get_users(array(
                'role' => $subscriber_role
            ));

            foreach ($subscribers as $subscriber) {
                $user_id = $subscriber->ID;
                
                $existing_entry = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", $user_id)
                );

                if (!$existing_entry) {
                    
                    $data_to_insert = array(
                        'user_id' => $user_id,
                        'start_date' => $current_date
                    );

                    $data_to_insert = array_merge($data_to_insert, $fixed_values);

                    $wpdb->insert($table_name, $data_to_insert);
                }
            

            echo 'Free members imported successfully.';
        }
     } else {
            echo 'Access denied.';
        }
    }

}   



?>
