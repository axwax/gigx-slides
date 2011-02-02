<?php

# GIGX_Slides_Widget
# properties:
# $sizes
# $id
# $queued

class GIGX_Slides_Widget extends WP_Widget {
	// Note: these strings match strings in WP exactly. If changed the gettext domain will need to be added
	var $sizes = array( 'full' => 'Full Size', 'medium' => 'Medium', 'large' => 'Large' );
	var $id = 'gigx_slide_widget';
	var $queued = false;
	function GIGX_Slides_Widget() {
		$widget_ops = array( 'description' => __( 'GIGX Slides Widget' ) );
		$this->WP_Widget( $this->id, __('GIGX Slides Widget'), $widget_ops );
		add_action( 'wp_head', array( &$this, 'wp_head' ), 1 );
		add_action( 'wp_footer', array( &$this, 'wp_footer' ), 2 );
	}
	function widget( $args, $instance ) {
		global $gigx_slide_type;
		extract( $args );
		echo $before_widget; ?>			
    <div class="gigx-slideshow" id="gigx-slides<?php echo ( $instance['size'] ? '-' . $instance['size'] : '' ); ?>">				
      <div class="gigx-slideshow-wrapper">
<?php		$first = true;
		$num_posts = -1;
		if( $instance['how_many'] > 0 )
			$num_posts = $instance['how_many'];
			
		if( !empty( $gigx_slide_type ) ) {
			$posts = $gigx_slide_type->query_posts( $num_posts, $instance['size'] );
			$pagermenu='';
			$count=0;
			foreach( $posts as $p ) { 
      $count++;
          ?>  		
        <div class="gigx-slide<?php echo ' gigx-slide'.$count;  ?>">			    
          <div class="gigx-slide-text">            <h1>
              <?php echo $p->post_title; ?></h1>            
            <p>
              <?php echo $p->post_excerpt; ?><br />
            </p>  			  
          </div>                 
          <?php if (($p->post_url)&&($p->post_url<>"http://")) {?>
          <a href="<?php echo $p->post_url; ?>" title="
            <?php echo $p->post_title; ?>">
            <?php } ?>          
            <?php echo $p->image; ?>          
            <?php if (($p->post_url)&&($p->post_url<>"http://")) {?></a>
          <?php } ?>		
        </div>
<?php				
      $pagermenu.='<li class="gigx-slideshow-pagerbutton gigx-slideshow-pagerbutton'.$count.'" title="'.$p->post_title.'"><a href="'.$p->post_url.'">'.$p->post_tab.'</a></li>';
      $first = false;
			}
		}
    ?>				
      </div>        
      <ul class="gigx-slideshow-pager">
        <?php echo $pagermenu; ?>
      </ul>				
      <div style="clear:both;">
      </div>			
    </div>
<?php 		echo $after_widget;
		if( $this->queued )
			$this->queued = false;
	}
 	function update( $new_instance, $old_instance ) {
		$new_instance['how_many'] = intval( $new_instance['how_many'] );
		if( !in_array( $new_instance['size'], array_keys( $this->sizes ) ) )
			$new_instance['size'] = 'full';
		return $new_instance;
	}
	function form( $instance ) { ?>		
<p>
  <label for="<?php echo $this->get_field_id('how_many'); ?>">
    <?php _e('How many gallery posts:') ?>
  </label>		
  <input type="text" id="<?php echo $this->get_field_id('how_many'); ?>" name="
  <?php echo $this->get_field_name('how_many'); ?>" value="
  <?php echo ( $instance['how_many'] > 0 ? esc_attr( $instance['how_many'] ) : '' ); ?>" />
</p>		
<p>			
  <label for="<?php echo $this->get_field_id('size'); ?>">
    <?php _e( 'Image Size:' ); ?>
  </label>			
  <select name="<?php echo $this->get_field_name('size'); ?>" id="
    <?php echo $this->get_field_id('size'); ?>" class="widefat"> 
    <?php		foreach( $this->sizes as $k => $v ) { ?> 				
    <option value="<?php echo $k; ?>"<?php selected( $instance['size'], $k ); ?>>
    <?php _e( $v ); ?>
    </option>
    <?php		} ?>			
  </select>		
</p>
<?php	}
	function wp_head() {
		if( !is_admin() ) {
			$this->queued = true;
			$url = plugin_dir_url( __FILE__ );
			wp_enqueue_style( 'gigx-cycle', $url . 'css/style.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'gigx-cycle-js', $url . 'js/jquery.cycle.lite.min.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-clickable-js', $url . 'js/jquery.clickable-0.1.9.js', array( 'jquery' ), '1.4', true );
      wp_enqueue_script( 'gigx-tooltip-js', $url . 'js/jquery.tipTip.minified.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-cycle-slide-js', $url . 'js/gigx-slide.js', false, false, true );
		}
	}
	function wp_footer() {
		if( $this->queued ) {
			wp_deregister_script( 'gigx-cycle-js' );
			wp_deregister_script( 'gigx-cycle-slide-js' );
		}
	}
}
?>