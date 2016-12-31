<?php

class Hot_Widget extends WP_Widget {
    private $propertiesOrder = null;

    function __construct(){
        $this->propertiesOrder = array(
            'post_date' => __('Published Date','wpestate'),
            'property_price' => __('Property Price','wpestate')
        );

        //function Hot_Widget(){
        $widget_ops = array('classname' => 'hot_sidebar', 'description' => 'Put a Hot listing on sidebar.');
        $control_ops = array('id_base' => 'hot_widget');
        parent::__construct('hot_widget', 'Wp Estate: Hot Listing', $widget_ops, $control_ops);
    }

    function form($instance){
        $defaults = array(
            'title' => 'Hot Listing',
            'order_by'=>'post_date',
            'second_line'=>''
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        $display='<p>
			<label for="'.$this->get_field_id('order_by').'">Order By</label>
		</p><p> 
		    <select id="'.$this->get_field_id('order_by').'" name="'.$this->get_field_name('order_by').'">';
        foreach($this->propertiesOrder as $optionValue => $optionLabel) {
            $display .= '<option ';
            if ($optionValue == $instance['order_by'])  {
                $display .= 'selected';
            }
            $display .= ' value="'.$optionValue.'">'.$optionLabel.'</option>';
        }
		$display .= '</select>
		</p><p>
			<label for="'.$this->get_field_id('second_line').'">Second Line:</label>
		</p><p>
			<input id="'.$this->get_field_id('second_line').'" name="'.$this->get_field_name('second_line').'" value="'.$instance['second_line'].'" />
		</p>';
        print $display;
    }


    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['order_by'] = $new_instance['order_by'];
        $instance['second_line'] = $new_instance['second_line'];

        return $instance;
    }



    function widget($args, $instance){
        extract($args);
        $display='';
        print $before_widget;
        $display.='<div class="featured_sidebar_intern hot">';

        $args=array(
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
            'meta_query' => array(
                array(
                    'key'     => 'prop_hot',
                    'value'   => 1,
                    'compare' => '=',
                ),
            )
        );
        $the_query = new WP_Query( $args );

        // The Loop
        while ( $the_query->have_posts() ) :
            $the_query->the_post();
            $link        =  get_permalink();
            $thumb_id    =  get_post_thumbnail_id($instance['prop_id']);
            $preview     =  wp_get_attachment_image_src($thumb_id, 'property_featured_sidebar');
            if($preview[0]==''){
                $preview[0]= get_template_directory_uri().'/img/defaults/default_property_featured_sidebar.jpg';
            }
            $display    .=  '<div class="featured_widget_image" data-link="'.get_permalink().'">
                                          
                                                <div class="prop_new_details_back"></div>
                                                <a href="'.get_permalink().'"><img  src="'.$preview[0].'" class="img-responsive" alt="slider-thumb" /></a>
                                                                                    
                                        </div>';
            $display    .=  '<div class="featured_title"><a href="'.$link.'" class="featured_title_link">'.get_the_title().'</a></div>';
        endwhile;

        wp_reset_query();

        if($instance['second_line']){
            $display    .=  '<div class="featured_second_line">'.$instance['second_line'].'</div>';
        }

        $display.='</div>';
        print $display;
        print $after_widget;
    }




}