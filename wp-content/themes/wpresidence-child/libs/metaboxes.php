<?php

////////////////////////////////////////////////////////////////////////////////////////////////
// Saving of custom data
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_save_postdata') ):
    function estate_save_postdata($post_id) {
        global $post;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }




        ///////////////////////////////////// Check permissions
        if(isset($_POST['post_type'])){
            if ('page' == $_POST['post_type'] or 'post' == $_POST['post_type'] or 'estate_property' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id))
                    return;
            }
            else {
                if (!current_user_can('edit_post', $post_id))
                    return;
            }
        }



        $allowed_keys=array(
            'sidebar_option',
            'sidebar_select',
            'post_show_title',
            'group_pictures',
            'embed_video_id',
            'embed_video_type',
            'page_show_title',
            'adv_filter_search_action',
            'adv_filter_search_category',
            'current_adv_filter_city',
            'current_adv_filter_area',
            'listing_filter',
            'show_featured_only',
            'show_filter_area',
            'header_type',
            'header_transparent',
            'page_custom_lat',
            'page_custom_long',
            'page_custom_zoom',
            'min_height',
            'max_height',
            'keep_min',
            'page_custom_image',
            'rev_slider',
            'sidebar_agent_option',
            'local_pgpr_slider_type',
            'local_pgpr_content_type',
            'agent_position',
            'agent_email',
            'agent_phone',
            'agent_mobile',
            'agent_skype',
            'agent_facebook',
            'agent_twitter',
            'agent_linkedin',
            'agent_pinterest',
            'agent_instagram',
            'agent_website',
            'item_id',
            'item_price',
            'purchase_date',
            'buyer_id',
            'biling_period',
            'billing_freq',
            'pack_listings',
            'mem_list_unl',
            'pack_featured_listings',
            'pack_price',
            'pack_visible',
            'pack_stripe_id',
            'property_address',
            'property_zip',
            'property_state',
            'property_country',
            'property_status',
            'prop_featured',
            'property_price',
            'property_label',
            'property_label_before',
            'property_size',
            'property_lot_size',
            'property_rooms',
            'property_bedrooms',
            'property_bathrooms',
            'embed_video_type',
            'embed_video_id',
            'owner_notes',
            'property_latitude',
            'property_longitude',
            'property_google_view',
            'google_camera_angle',
            'page_custom_zoom',
            'property_agent',
            'property_user',
            'use_floor_plans',
            'property_page_desing_local',
            'property_list_second_content',
            /**
             * Start rewrite
             */
            'prop_hot'
            /**
             * End rewrite
             */
        );

        $custom_fields = get_option( 'wp_estate_custom_fields', true);
        if( !empty($custom_fields)){
            $i=0;
            while($i< count($custom_fields) ){
                $name =   $custom_fields[$i][0];
                $slug         =     wpestate_limit45(sanitize_title( $name ));
                $slug         =     sanitize_key($slug);
                $allowed_keys[]=     $slug;
                $i++;
            }
        }

        $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array =   explode( ',',$feature_list);



        foreach($feature_list_array as $key => $value){
            $post_var_name=  str_replace(' ','_', trim($value) );
            $input_name =   wpestate_limit45(sanitize_title( $post_var_name ));
            $input_name =   sanitize_key($input_name);
            $allowed_keys[]=     $input_name;
        }



        foreach ($_POST as $key => $value) {
            if( !is_array ($value) ){

                if (in_array ($key, $allowed_keys)) {
                    $postmeta = wp_filter_kses( $value );
                    update_post_meta($post_id, sanitize_key($key), $postmeta );
                }



            }
        }

        //////////////////////////////////////////////////////////////////
        //// change listing author id
        //////////////////////////////////////////////////////////////////
        if ( isset($_POST['property_user'])){
            $current_id = wpsestate_get_author($post_id);
            $new_user=intval($_POST['property_user']);

            if($current_id != $new_user && $new_user!=0 ){
                // change author
                $post = array(
                    'ID'            => $post_id,
                    'post_author'   => $new_user
                );

                wp_update_post($post );
            }

        }
        ///////////////////////////// end change author id

        //////////////////////////////////////////////////////////////////
        /// save floor plan
        //////////////////////////////////////////////////////////////////

        if(isset($_POST['plan_title'])){
            update_post_meta($post->ID, 'plan_title',wpestate_sanitize_array ( $_POST['plan_title'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_title','' );
            }
        }

        if(isset($_POST['plan_description'])){
            update_post_meta($post->ID, 'plan_description',wpestate_sanitize_array ( $_POST['plan_description'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_description','' );
            }
        }

        if(isset($_POST['plan_image_attach'])){
            update_post_meta($post->ID, 'plan_image_attach',wpestate_sanitize_array ( $_POST['plan_image_attach'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_image_attach','' );
            }
        }

        if(isset($_POST['plan_image'])){
            update_post_meta($post->ID, 'plan_image',wpestate_sanitize_array ( $_POST['plan_image'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_image','' );
            }
        }

        if(isset($_POST['plan_size'])){
            update_post_meta($post->ID, 'plan_size',wpestate_sanitize_array ( $_POST['plan_size'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_size','' );
            }
        }


        if(isset($_POST['plan_rooms'])){
            update_post_meta($post->ID, 'plan_rooms',wpestate_sanitize_array ( $_POST['plan_rooms'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_rooms','' );
            }
        }

        if(isset($_POST['plan_bath'])){
            update_post_meta($post->ID, 'plan_bath',wpestate_sanitize_array ( $_POST['plan_bath'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_bath','' );
            }
        }

        if(isset($_POST['plan_price'])){
            update_post_meta($post->ID, 'plan_price',wpestate_sanitize_array ( $_POST['plan_price'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'plan_price','' );
            }
        }


        //////////////////////////////////////// end save floor plan



        if(isset($_POST['adv_filter_search_action'])){
            update_post_meta($post->ID, 'adv_filter_search_action',wpestate_sanitize_array ( $_POST['adv_filter_search_action'] ) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'adv_filter_search_action','' );
            }
        }

        if(isset($_POST['adv_filter_search_category'])){
            update_post_meta($post->ID, 'adv_filter_search_category', wpestate_sanitize_array ($_POST['adv_filter_search_category']) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'adv_filter_search_category','' );
            }
        }

        if(isset($_POST['current_adv_filter_city'])){
            update_post_meta($post->ID, 'current_adv_filter_city',wpestate_sanitize_array($_POST['current_adv_filter_city']) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'current_adv_filter_city','' );
            }
        }


        if(isset($_POST['current_adv_filter_area'])){
            update_post_meta($post->ID, 'current_adv_filter_area',wpestate_sanitize_array ($_POST['current_adv_filter_area']) );
        }else{
            if(isset($post->ID)){
                update_post_meta($post->ID, 'current_adv_filter_area','' );
            }
        }
    }
endif; // end   estate_save_postdata
