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
            'name' => 'Tab Label',
            'desc' => 'Label for the slide\'s tab',
            'id' => $prefix.'tab',
            'type' => 'text',
            'std' => 'Slide'
      		),
          array(
              'name' => 'Don\'t show Slide on',
              'desc' => 'Don\'t display this slide on the following days:',
              'id' => $prefix.'limit',
              'type' => 'multicheck',
              'options' => array(
                  'mon' => 'Monday',
                  'tue' => 'Tuesday',
                  'wed' => 'Wednesday',
                  'thu' => 'Thursday',
                  'fri' => 'Friday',
                  'sat' => 'Saturday',
                  'sun' => 'Sunday'            
              ),
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
      		return true;
      	}
      }
      #ax metabox
?>
