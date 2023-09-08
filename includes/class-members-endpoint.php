<?php

class MembersEndpoint {
    public function __construct() {
        add_action('rest_api_init', array($this, 'create_member_endpoint'));
    }

    public function create_member_endpoint() {
        register_rest_route('custom/v2', '/create-member', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_member_data'),
        ));
    }

    
    public function handle_member_data($request) {
       
       
        $params = $request->get_params();

    if (empty($first_name) || empty($last_name) || empty($email)) {
        return new WP_Error(400, "First name, last name, and email are required fields");
    }

    if (!empty($params['first_name'])) {
        $first_name = sanitize_text_field($params['first_name']);
    } else {
        
    }
    if (!empty($params['last_name'])) {
        $last_name = sanitize_text_field($params['last_name']);
    } else {
    
    }
    
    if (!empty($params['email'])) {
        $email = sanitize_email($params['email']);
    } 

    elseif(!is_email($email)) {
        return new WP_Error(400, "Invalid email address");
    }

    
    $user = get_user_by('email', $email);

    if ($user) {
         
         global $wpdb;
         $user_id = $user->ID;
         $subscription_plan_id = 3163;
    
         $existing_subscription = $wpdb->get_row($wpdb->prepare("SELECT * FROM pms_member_subscriptions WHERE user_id = %d AND subscription_plan_id = %d", $user_id, $subscription_plan_id));
 
         if ($existing_subscription) {
             
             $wpdb->update(
                 'pms_member_subscriptions',
                 array('subscription_plan_id' => 3163),
                 array('user_id' => $user_id, 'subscription_plan_id' => $subscription_plan_id)
             );
         }
    } else {
       
       $user_login = $email;
       $user_pass  = wp_generate_password( 12 );
       $user_data = array(
           'user_login' => $user_login,
           'user_email' => $email,
           'first_name' => $first_name,
           'last_name' => $last_name,
           'user_pass' => $user_pass,
           'role' => 'subscriber',
           
       );

      
       $user_id = wp_insert_user($user_data);

       if (is_wp_error($user_id)) {
           return new WP_Error('user_creation_error', 'Error creating user.', array('status' => 400));
       }

  
       
       global $wpdb;
       $fixed_values = array(
           'user_id' => $user_id,
           'subscription_plan_id' => 1956,
           'status' => 'active',
           'payment_profile_id' => '',
           'payment_gateway' => '',
           'billing_amount' => 0,
           'billing_duration' => 0,
           'billing_duration_unit' => '',
           'billing_cycles' => 0,
       );

     
      $wpdb->insert('pms_member_subscriptions', $fixed_values);

    }

    wp_new_user_notification( $user_id, null, 'user' );

 
    return rest_ensure_response(array('message' => 'Member data processed successfully.'));
}
}



?>