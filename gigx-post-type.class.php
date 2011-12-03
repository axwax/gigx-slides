<?php
/*
 * Set values for post type
 */
class GIGX_Post_Type {
  	var $post_type_name = 'gigx_slide';
  	var $handle = 'gigx-meta-box';
  	var $attachments = null;
  
  	var $post_type = array(
  		'label' => 'GIGX Slides',
  		'singular_label' => 'GIGX Slide',
  		'menu_position' => '1',
  		'taxonomies' => array(),
  		'public' => true,
  		'show_ui' => true,
  		'rewrite' => false,
  		'query_var' => false,
  		'supports' => array( 'title', 'editor','thumbnail','revisions' )
  		); // 'custom-fields'
    		  
  	function GIGX_Post_Type() {
  		return $this->__construct();
  	}
  
  	function  __construct() {
  		add_action( 'init', array( &$this, 'init' ) );
  
  		$this->post_type['description'] = $this->post_type['singular_label'];
  		$this->post_type['labels'] = array(
  			'name' => $this->post_type['label'],
  			'singular_name' => $this->post_type["singular_label"],
  			'add_new' => 'Add ' . $this->post_type["singular_label"],
  			'add_new_item' => 'Add New ' . $this->post_type["singular_label"],
  			'edit' => 'Edit',
  			'edit_item' => 'Edit ' . $this->post_type["singular_label"],
  			'new_item' => 'New ' . $this->post_type["singular_label"],
  			'view' => 'View ' . $this->post_type["singular_label"],
  			'view_item' => 'View ' . $this->post_type["singular_label"],
  			'search_items' => 'Search ' . $this->post_type["label"],
  			'not_found' => 'No ' . $this->post_type["singular_label"] . ' Found',
  			'not_found_in_trash' => 'No ' . $this->post_type["singular_label"] . ' Found in Trash'
  			);
  	}
  
  	function init() {
    		register_post_type( $this->post_type_name, $this->post_type );
    		# custom icon
        add_action('admin_head', array( &$this,'gigx_slide_icon'));
        # custom thumbnail size
        add_image_size( 'gigx-slide', 300, 225 );
        
        # change title text (only works for wp >=3.1)
        add_filter( 'enter_title_here', array( &$this, 'gigx_change_default_title') );            
  	}
  	# change title text        
    function gigx_change_default_title( $title ){
      $screen = get_current_screen();
      if  ( 'gigx_slide' == $screen->post_type ) {
        $title = 'Enter Slide Title';
      }
      return $title;
    }  	
  	function gigx_slide_icon() {
      	global $post_type;
      	$url = plugin_dir_url( __FILE__ );
      	?>
      	<style>
      	<?php if (($_GET['post_type'] == 'gigx_slide') || ($post_type == 'gigx_slide')) : ?>
      	#icon-edit { background:transparent url('<?php echo $url .'images/icon32x32.png';?>') no-repeat; }		
      	<?php endif; ?>
      	#adminmenu #menu-posts-gigxslide div.wp-menu-image{background: url("<?php echo $url .'images/icon.png';?>") no-repeat 6px -17px !important;}
      	#adminmenu #menu-posts-gigxslide:hover div.wp-menu-image,#adminmenu #menu-posts-gallery.wp-has-current-submenu div.wp-menu-image{background-position:6px 7px!important;}	    	
      	
        </style>
        <?php
    }
  

  	  function query_posts( $num_posts = -1, $orderby = 'menu_order' ) {
  		$query = sprintf( 'showposts=%d&post_type=%s&orderby=%s&order=ASC', $num_posts, $this->post_type_name,$orderby );
  		$posts = new WP_Query( $query );  
  		$gallery = array();
  		$child = array( 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'none' );
  		while( $posts->have_posts() ) {
  			$posts->the_post();
  			$child['post_parent'] = get_the_ID(); 
  			
  
  			$p = new stdClass();
  			$p->post_title = get_the_title();
  			$p->post_excerpt = get_the_content();
        $p->post_url= get_post_meta($child['post_parent'], 'gigx_slide_url', true);
  			$p->post_tab= get_post_meta($child['post_parent'], 'gigx_slide_tab', true);
        $p->post_limit= get_post_meta($child['post_parent'], 'gigx_slide_limit', false);      
        
        #if( ( $c = count( $attachments ) ) > 1 ) {
  			#	$x = rand( 1, $c );
  			#	while( $c > $x++ )
  			#		next( $attachments );
  			#}
  			$img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'gigx-slide',false);
  			$p->image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/>';
  			$gallery[] = $p;
  		}
  		wp_reset_query();
  		return $gallery;
  	}
  	function admin_menu() {
  		add_action( 'do_meta_boxes', array( &$this, 'add_metabox' ), 9 );
  	}
  	
  
        
}

?>