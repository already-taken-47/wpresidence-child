<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Property custom fields
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_box') ):
    function estate_box($post) {
        global $post;
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
        $mypost = $post->ID;

        print' 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
        <td width="33%" align="left" valign="top">
            <p class="meta-options">
            <label for="property_address">'.__('Address(*only street name and building no): ','wpestate').'</label><br />
            <textarea type="text" id="property_address"  size="40" name="property_address" rows="3" cols="42">' . esc_html(get_post_meta($mypost, 'property_address', true)) . '</textarea>
            </p>
        </td>
      
   
        <td align="left" valign="top">   
            <p class="meta-options">
            <label for="property_zip">'.__('Zip: ','wpestate').'</label><br />
            <input type="text" id="property_zip" size="40" name="property_zip" value="' . esc_html(get_post_meta($mypost, 'property_zip', true)) . '">
            </p>
        </td>
        <!--
        <td align="left" valign="top">   
            <p class="meta-options">
            <label for="property_state">'.__('State:: ','wpestate').'</label><br />
            <input type="text" id="property_state" size="40" name="property_state" value="' . esc_html(get_post_meta($mypost, 'property_state', true)) . '">
            </p>
        </td>
    -->

    
    </tr>

    <tr>';
        $status_values          =   esc_html( get_option('wp_estate_status_list') );
        $status_values_array    =   explode(",",$status_values);
        $prop_stat              =   get_post_meta($mypost, 'property_status', true);
        $property_status        =   '';


        foreach ($status_values_array as $key=>$value) {
            if(trim($value)!= ''){
                if (function_exists('icl_translate') ){
                    $value     =   icl_translate('wpestate','wp_estate_property_status_'.$value, $value ) ;
                }

                $value = trim($value);
                $property_status.='<option value="' . $value . '"';
                if ($value == $prop_stat) {
                    $property_status.='selected="selected"';
                }
                $property_status.='>' . $value . '</option>';
            }
        }




        $normal_selected='';
        if ( trim($status_values)==''){
            print   $normal_selected= ' selected ' ;
        }

        print'
    <td align="left" valign="top">
        <p class="meta-options">
        <label for="property_country">'.__('Country: ','wpestate').'</label><br />

        ';
        print wpestate_country_list(esc_html(get_post_meta($mypost, 'property_country', true)));
        print '     
        </p>
    </td>
        
    <td align="left" valign="top">
         <p class="meta-options">
            <label for="property_status">'.__('Property Status:','wpestate').'</label><br />
            <select id="property_status" style="width: 237px;" name="property_status">
            <option value="normal" '.$normal_selected.'>normal</option>
            ' . $property_status . '
            </select>
        </p>
    </td>

    <td align="left" valign="top">  
         <p class="meta-options"> 
            <input type="hidden" name="prop_featured" value="0">
            <input type="checkbox"  id="prop_featured" name="prop_featured" value="1" ';
        if (intval(get_post_meta($mypost, 'prop_featured', true)) == 1) {
            print'checked="checked"';
        }
        print' />
            <label for="prop_featured">'.__('Make it Featured Property','wpestate').'</label>
        </p>
        
        <p class="meta-options"> 
            <input type="hidden" name="prop_hot" value="0">
            <input type="checkbox"  id="prop_hot" name="prop_hot" value="1" ';
        if (intval(get_post_meta($mypost, 'prop_hot', true)) == 1) {
            print 'checked="checked"';
        }
        print ' />
            <label for="prop_hot">'.__('Make it Hot Property','wpestate').'</label>
        </p>
    </td>

      <td align="left" valign="top">          
      </td>
    </tr>
    </table>
    ';
    }
endif; // end   estate_box
