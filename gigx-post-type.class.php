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
  		'supports' => array( 'title', 'editor','thumbnail' )
  		);
    		
   # meta boxes
   # ax metabox
   var $prefix = 'gigx_';
  
   var $meta_box = array(
      'id' => 'gigx-slide-meta-box',
      'title' => 'GIGX Slide Fields',
      'page' => 'gigx_slide',
      'context' => 'normal',
      'priority' => 'high',
      'fields' => array(
          array(
              'name' => 'URL',
              'desc' => 'URL of the page the slide links to',
              'id' => 'gigx_slide_url',
              'type' => 'text',
              'std' => 'http://'
          ),
          array(
              'name' => 'Tab Label',
              'desc' => 'Label for the slide\'s tab',
              'id' => 'gigx_slide_tab',
              'type' => 'text',
              'std' => 'Slide'
          ),
          array(
              'name' => 'Limit Display',
              'desc' => 'Only display this slide on the following days:',
              'id' => 'gigx_slide_limit',
              'type' => 'checkbox',
              'std' => ''
          ),
          array(
              'name' => 'Monday',
              'desc' => 'Display this slide on Mondays',
              'id' => 'gigx_slide_mon',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Tuesday',
              'desc' => 'Display this slide on Tuesdays',
              'id' => 'gigx_slide_tue',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Wednesday',
              'desc' => 'Display this slide on Wednesdays',
              'id' => 'gigx_slide_wed',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Thursday',
              'desc' => 'Display this slide on Thursdays',
              'id' => 'gigx_slide_thu',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Friday',
              'desc' => 'Display this slide on Fridays',
              'id' => 'gigx_slide_fri',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Saturday',
              'desc' => 'Display this slide on Saturdays',
              'id' => 'gigx_slide_sat',
              'type' => 'checkbox',
              'std' => ''
          ),          
          array(
              'name' => 'Sunday',
              'desc' => 'Display this slide on Sundays',
              'id' => 'gigx_slide_sun',
              'type' => 'checkbox',
              'std' => ''
          )                    
      )
  );
  		
  
  
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
    		add_action('admin_menu', array( &$this, 'admin_menu' ), 20);
        add_action('save_post', array( &$this,'mytheme_save_data'));
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
  			$p->post_limit= get_post_meta($child['post_parent'], 'gigx_slide_limit', true);
  			$p->post_mon= get_post_meta($child['post_parent'], 'gigx_slide_mon', true);
  			$p->post_tue= get_post_meta($child['post_parent'], 'gigx_slide_tue', true);
        $p->post_wed= get_post_meta($child['post_parent'], 'gigx_slide_wed', true);
        $p->post_thu= get_post_meta($child['post_parent'], 'gigx_slide_thu', true);
        $p->post_fri= get_post_meta($child['post_parent'], 'gigx_slide_fri', true);
        $p->post_sat= get_post_meta($child['post_parent'], 'gigx_slide_sat', true);
        $p->post_sun= get_post_meta($child['post_parent'], 'gigx_slide_sun', true);
        
        
        if( ( $c = count( $attachments ) ) > 1 ) {
  				$x = rand( 1, $c );
  				while( $c > $x++ )
  					next( $attachments );
  			}
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
  	
  	function add_metabox() {
  		global $post;
  		if( empty( $post ) || $this->post_type_name != $post->post_type )
  			return;
  		$child = array( 'post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'none' );

  		  #ax metabox
        $meta_box=$this->meta_box;
            add_meta_box($meta_box['id'], $meta_box['title'], array( &$this,'gigx_slide_meta_box'), $this->post_type_name, $meta_box['context'], $meta_box['priority']);
        #ax metabox
  
    }
  
    # gigx_slide_meta_box
    // Callback function to show fields in meta box
    function gigx_slide_meta_box() {
        global $post;
        $meta_box=$this->meta_box;
        // Use nonce for verification
        echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
        echo '<table class="form-table">';
        foreach ($meta_box['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<tr>',
                    '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                    '<td>';
            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                    break;
                case 'textarea':
                    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                    break;
                case 'select':
                    echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>';
                    break;
                case 'radio':
                    foreach ($field['options'] as $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                    }
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    break;               
            }
            echo     '<td>',
                '</tr>';
        }
        echo '</table>';        
    }
    # /gigx_slide_meta_box
        
    # mytheme_save_data
    // Save data from meta box
    function mytheme_save_data($post_id) {
        $meta_box=$this->meta_box;
        
        // verify nonce
        if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
    
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
    
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        
        foreach ($meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
            
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        } 
    }
    # /mytheme_save_data
}

?>