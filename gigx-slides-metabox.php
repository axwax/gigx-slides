<?php
   # gigx slide meta box
   
      # meta boxes class
      if (!class_exists('RW_Meta_Box')) require 'meta-box.php';
      # meta boxes class
   
      # ax metabox
      // Register meta boxes
      
      $prefix = 'gigx_slide_';
      
      $meta_boxes = array();
      
      // first meta box
      $meta_boxes[] = array(
      	'id' => 'gigx-slides-meta-box',
      	'title' => 'GIGX Slide Fields',
      	'pages' => array('gigx_slide'), // multiple post types, accept custom post types
      	'context' => 'normal', // normal, advanced, side (optional)
      	'priority' => 'high', // high, low (optional)
      	'fields' => array(
      		array(
            'name' => 'URL',
            'desc' => 'URL of the page the slide links to',
            'id' => $prefix .'url',
            'type' => 'text',
            'std' => 'http://',		
            'validate_func' => 'check_text' // validate function, created below, inside RW_Meta_Box_Validate class
      		),
      		array(
            'name' => 'Target',
            'desc' => 'tick to open link in new tab/window.',
            'id' => $prefix .'target',
            'type' => 'checkbox',
            'std' => 0,		
      		),
            array(
            'name' => 'Tab Label',
            'desc' => 'Label for the slide\'s tab',
            'id' => $prefix.'tab',
            'type' => 'text',
            'std' => 'Slide'
      		),
			array(
			  'name' => 'Show Slide on',
			  'desc' => 'Only Display this slide on the preceding days.',
			  'id' => $prefix.'limit',
			  'type' => 'checkbox_list',
			  'options' => array(
				  '1' => 'Monday',
				  '2' => 'Tuesday',
				  '3' => 'Wednesday',
				  '4' => 'Thursday',
				  '5' => 'Friday',
				  '6' => 'Saturday',
				  '7' => 'Sunday'            
				)
			),
			array(
			  'name' => 'Odd or Even Week',
			  'desc' => 'Display this slide on odd, even or both weeks?',
			  'id' => $prefix.'week',
			  'type' => 'checkbox_list',
			  'options' => array(
				  'odd' => 'Odd',
				  'even' => 'Even'            
				)
			)
		)
  
      );
      foreach ($meta_boxes as $meta_box) {
      	$my_box = new RW_Meta_Box($meta_box);
      }
      
      // Validate value of meta fields
      
      // Define ALL validation methods inside this class
      // and use the names of these methods in the definition of meta boxes (key 'validate_func' of each field)
      
      class RW_Meta_Box_Validate {
      	function check_text($text) {
      		#if ($text != 'hello') {
      		#	return false;
      		#}
      		return $text;
      	}
      }
      #ax metabox
?>
