<?php

add_action( 'widgets_init', 'egw_aw_init' );

function egw_aw_init() {
	register_widget( 'egw_aw_init' );
}

class egw_aw_init extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'egw_aw_init',
            'description' => 'Displays advertisers in sidebar.'
        );

        parent::__construct( 'egw_aw_init', 'Advertiser Landing Page Widget', $widget_details );

    }

    public function widget( $args, $instance )
    {
    	if ( isset( $instance['image'])) {
    		$img = $instance['image'];
    	}
    	else {
    		$img = get_stylesheet_directory_uri() . "/assets/img/aavathar.jpg";
    	}

		?>
		<div class="mkd-rpc-holder">
		<?php if ( !empty($instance['title']) ) : ?>
		<div class="mkd-section-title-holder clearfix"><span class="mkd-st-title"><?php echo apply_filters( 'widget_title', $instance['title'] ); ?></span></div>
		<?php endif; ?>
		<div id="mkd-widget-tab-4" class="mkd-ptw-holder mkd-tabs" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="false">
		    <div class="mkd-plw-tabs-content">
		        <div data-max_pages="4" data-paged="1" data-display_excerpt="no" data-display_date="yes" data-title_length="30" data-title_tag="h6" data-display_image="yes" data-custom_thumb_image_height="84" data-custom_thumb_image_width="117" data-category_id="4" data-number_of_posts="5" data-base="mkd_post_layout_seven">
		            <div class="mkd-bnl-outer">
		                <div class="mkd-bnl-inner">
		                    <div class="mkd-pt-seven-item mkd-post-item mkd-active-post-page">

								<!-- Advertiser Container -->
								<div class="mkd-pt-seven-item-inner clearfix"  style="padding:1rem;">

		                            <!-- Name -->
		                            <div class="vc_row">
		                            	<div class="vc_col-sm-12">
		                            		<?php if( $instance['name'] != null || $instance['name'] != '' ) : ?>
		                            			<i class="fa fa-building" aria-hidden="true"></i> 
		                                		<?php echo $instance['name']; ?>
		                                	<?php endif; ?>
		                            	</div>
		                            </div>
		                            <!-- /Name -->

		                            <!-- Address -->
		                            <div class="vc_row">
		                                <div class="vc_col-sm-12">
		                                	<?php if( ( ( $instance['street'] || $instance['street2'] )!= null ) || ( ( $instance['street'] || $instance['street2'] ) != '' ) ): ?>
		                                		<i class="fa fa-map-marker" aria-hidden="true"></i>
		                                		<a href="http://maps.google.com/?q=<?php echo $instance['street'] . $instance['street2']; ?>" style="" target="_blank">
		                                			<?php echo $instance['street']; ?><br />
		                                			<span style="text-indent: -15px; margin-left: 15px;">
		                                				<?php echo $instance['street2']; ?>
	                                				</span>
		                                		</a>
		                                	<?php endif; ?>
		                                </div>
		                            </div>
		                            <!-- /Address -->

		                          	<!-- Phone -->
		                            <div class="vc_row">
		                                <div class="vc_col-sm-12">
		                                	<?php if( $instance['phone'] != null || $instance['phone'] != '' ) : ?>
		                                		<i class="fa fa-phone" aria-hidden="true"></i>
		                                		<a href="tel:<?php echo $instance['phone']; ?>"><?php echo $instance['phone']; ?></a>
		                                	<?php endif; ?>
		                                </div>
		                            </div>
		                            <!-- /Phone -->

		                           	<!-- Email -->
		                            <div class="vc_row">
		                                <div class="vc_col-sm-12">
		                                	<?php if( $instance['email'] != null || $instance['email'] != '' ) : ?>
		                                		<i class="fa fa-envelope-o" aria-hidden="true"></i>
		                                		<a href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a>
		                                	<?php endif; ?>
		                                </div>
		                            </div>
		                            <!-- /Email -->


		                        </div>
		                        <!-- /Advertiser Container-->
		                        
		                    </div>
		                </div><br>
		            </div>
		        </div>
		    </div>
		</div>

		<?php

		echo $args['after_widget'];
    }

	public function update( $new_instance, $old_instance ) {  
	    return $new_instance;
	}

    public function form( $instance )
    {
    	$street = '';
   		if( !empty( $instance['street'] ) ) {
	        $street = $instance['street'];
	    }

    	$street2 = '';
   		if( !empty( $instance['street2'] ) ) {
	        $street2 = $instance['street2'];
	    }

		$title = '';
	    if( !empty( $instance['title'] ) ) {
	        $title = $instance['title'];
	    }

	    $name = '';
	    if( !empty( $instance['name'] ) ) {
	        $name = $instance['name'];
	    }

	    // $address = '';
	    // if( !empty( $instance['address'] ) ) {
	    //     $address = $instance['address'];
	    // }

	    $phone = '';
	    if( !empty( $instance['phone'] ) ) {
	        $phone = $instance['phone'];
	    }

		$email = '';
		if(isset($instance['email']))
		{
		    $email = $instance['email'];
		}
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'name' ); ?>"><?php _e( 'Name:' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" ><?php echo esc_attr( $name ); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'street' ); ?>"><?php _e( 'Street Line 1:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'street' ); ?>" name="<?php echo $this->get_field_name( 'street' ); ?>" type="text" value="<?php echo esc_attr( $street ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'street2' ); ?>"><?php _e( 'Street Line 2:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'steet2' ); ?>" name="<?php echo $this->get_field_name( 'street2' ); ?>" type="text" value="<?php echo esc_attr( $street2 ); ?>" />
        </p>
<!--         <p>
            <label for="<?php echo $this->get_field_name( 'street' ); ?>"><?php _e( 'Street:' ); ?></label>
            <input class="widefat" id="<?php #echo $this->get_field_id( 'address' ); ?>" name="<?php #echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php #echo esc_attr( $address ); ?>" />
        </p> -->

        <p>
            <label for="<?php echo $this->get_field_name( 'phone' ); ?>"><?php _e( 'Phone:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'email' ); ?>"><?php _e( 'Email:' ); ?></label>
            <input name="<?php echo $this->get_field_name( 'email' ); ?>" id="<?php echo $this->get_field_id( 'email' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $email ); ?>" />
        </p>
        <?php
    }
}