<?php

# GIGX_Slides_Widget
# properties:
# $id
# $queued

class GIGX_Slides_Widget extends WP_Widget {
	// Note: these strings match strings in WP exactly. If changed the gettext domain will need to be added
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
    <div class="gigx-slideshow">				
      <div class="gigx-slideshow-wrapper">
<?php		$first = true;
		$num_posts = -1;          
		if( !empty( $gigx_slide_type ) ) {
			$posts = $gigx_slide_type->query_posts( $num_posts );
			$pagermenu='';
			$count=0;
			
			//current week
			if(date("W") % 2 >0)$currentweek='odd';
			else $currentweek='even';
			
			//loop through slides
			foreach( $posts as $p ) {
				// check weekdays	
    			$numdays=count($p->post_limit);
    			$showslide=false;
				if ($numdays>0){
					foreach ($p->post_limit as $d){
						if(date('N')==(int)$d) $showslide=true;
					}
				}
				$showweek=false;
				$whatweeks=count($p->post_week);
				foreach ($p->post_week as $w){
					if($w==$currentweek)$showweek=true;
				}

				//if(date("W") % 2)==
				
          if($showweek && $showslide&&($count<$instance['how_many']||$instance['how_many']<1)){
              $count++;
                  ?>  		
                <div class="gigx-slide<?php echo ' gigx-slide'.$count;  ?>">			                  
                  <?php if (($p->post_url)&&($p->post_url<>"http://")) {?>
                  <a href="<?php echo $p->post_url; ?>" <?php echo $p->post_target; ?>title="<?php echo $p->post_title; ?>">
                    <?php } ?>          
                    <?php echo $p->image; ?>          
                    <?php if (($p->post_url)&&($p->post_url<>"http://")) {?></a>
                  <?php } ?>
                  <div class="gigx-slide-text">
                      <h1><?php echo $p->post_title; ?></h1>            
                      <?php echo wpautop($p->post_excerpt); ?>
                  </div>   		  
                </div>
                <?php
               if (!($p->post_url)||($p->post_url=="http://"))$p->post_url="/"; // just link to page root
              $pagermenu.='<li class="gigx-slideshow-pagerbutton gigx-slideshow-pagerbutton'.$count.'" title="'.$p->post_title.'"><a href="'.$p->post_url.'">'.$p->post_tab.'</a></li>';
              $first = false;
          }   
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
<?php	}
	function wp_head() {
		if( !is_admin() ) {
			$this->queued = true;
			$url = plugin_dir_url( __FILE__ );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'gigx-cycle-js', $url . 'js/jquery.cycle.all.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-fitted-js', $url . 'js/jquery.fitted.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-tooltip-js', $url . 'js/jquery.tipTip.minified.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-cycle-slide-js', $url . 'js/gigx-slide.js', false, false, true );
		}
	}
	function wp_footer() {
		if( $this->queued ) {
			wp_deregister_script( 'gigx-cycle-js' );
			wp_deregister_script( 'gigx-fitted-js' );
			wp_deregister_script( 'gigx-tooltip-js' );
			wp_deregister_script( 'gigx-cycle-slide-js' );
		}
	}
}
?>