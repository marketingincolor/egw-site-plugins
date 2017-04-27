<?php

add_action( 'widgets_init', 'egw_fp_init' );

function egw_fp_init() {
	register_widget( 'egw_fp_init' );
}

class egw_fp_init extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'egw_fp_init',
            'description' => 'Displays featured personalities.'
        );

        parent::__construct( 'egw_fp_init', 'Featured Personalities', $widget_details );

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

								<!-- FEATURED CONTAINER -->
								<div class="mkd-pt-seven-item-inner clearfix">

		                            <!-- IMAGE HOLDER -->
		                            <div class="mkd-pt-seven-image-holder" style="width: 40%;">
		                                <a target="_self" href="<?php echo $instance['img_url']; ?>" title="#" class="mkd-pt-seven-link mkd-image-link" itemprop="url">
		                                <img src="<?php echo $img; ?>"/></a>
		                            </div>
		                            <!-- /IMAGE HOLDER -->

		                            <!-- TEXT CONTAINER -->
		                            <div class="mkd-pt-seven-content-holder">
		                                <div class="mkd-pt-seven-title-holder">
		                                    <h6 class="mkd-pt-seven-title">
		                                        <a href="<?php echo apply_filters('widget_title', $instance['description_url']); ?>"><?php echo apply_filters( 'widget_title', $instance['description'] ); ?></a>
		                                    </h6>
		                                </div>
		                            </div>
		                            <!-- /TEXT CONTAINER -->
		                        </div>
		                        <!-- /#1 FEATURED CONTAINER -->
		                        
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

		$title = '';
	    if( !empty( $instance['title'] ) ) {
	        $title = $instance['title'];
	    }

	    $description = '';
	    if( !empty( $instance['description'] ) ) {
	        $description = $instance['description'];
	    }

	    $description_url = '';
	    if( !empty( $instance['description_url'] ) ) {
	        $description_url = $instance['description_url'];
	    }

	    $img_url = '';
	    if( !empty( $instance['img_url'] ) ) {
	        $img_url = $instance['img_url'];
	    }

		$image = '';
		if(isset($instance['image']))
		{
		    $image = $instance['image'];
		}
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'description' ); ?>"><?php _e( 'Description:' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" ><?php echo esc_attr( $description ); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'description_url' ); ?>"><?php _e( 'Description URL:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'description_url' ); ?>" name="<?php echo $this->get_field_name( 'description_url' ); ?>" type="text" value="<?php echo esc_attr( $description_url ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'img_url' ); ?>"><?php _e( 'Image Link:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'img_url' ); ?>" name="<?php echo $this->get_field_name( 'img_url' ); ?>" type="text" value="<?php echo esc_attr( $img_url ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'image' ); ?>"><?php _e( 'Image Path:' ); ?></label>
            <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $image ); ?>" />
        </p>
        <?php
    }
}