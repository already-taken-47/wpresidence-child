<?php

if( !function_exists('wpestate_show_price') ):
    function wpestate_show_price($post_id,$currency,$where_currency,$return=0, $is_hot = false){

        $price_label        = '<span class="price_label">'.esc_html ( get_post_meta($post_id, 'property_label', true) ).'</span>';
        $price_label_before = '<span class="price_label price_label_before">'.esc_html ( get_post_meta($post_id, 'property_label_before', true) ).'</span>';
        $price              = floatval( get_post_meta($post_id, 'property_price', true) );
        $hot_price          = floatval(get_post_meta($post_id, 'prop_hot_price', true));

        $price = ($is_hot && $hot_price) ? $hot_price : $price;

        $th_separator   = stripslashes ( get_option('wp_estate_prices_th_separator','') );
        $custom_fields  = get_option( 'wp_estate_multi_curr', true);
        //print_r($_COOKIE);
        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);
            $custom_fields = get_option( 'wp_estate_multi_curr', true);
            if ($price != 0) {
                $price      = $price * $custom_fields[$i][2];
                $price      = number_format($price,0,'.',$th_separator);

                $currency   = $custom_fields[$i][0];

                if ($custom_fields[$i][3] == 'before') {
                    $price = $currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $currency;
                }

            }else{
                $price='';
            }
        }else{
            if ($price != 0) {
                $price = number_format($price,0,'.',$th_separator);

                if ($where_currency == 'before') {
                    $price = $currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $currency;
                }

            }else{
                $price='';
            }
        }



        if($return==0){
            print $price_label_before.' '.$price.' '.$price_label;
        }else{
            return $price_label_before.' '.$price.' '.$price_label;
        }
    }
endif;