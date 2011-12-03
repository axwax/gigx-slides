<?php
/*
Plugin Name: GIGX Slides
Plugin URI: http://gigx.co.uk/wordpress/plugins/gigx-slides/
Description: A Rotating Gallery Widget using a custom post type to create GIGX Slides.
Author: AxWax
Version: 0.0.5
Author URI: http://axwax.de/
Credits:
This plugin is based on Post Gallery Widget by Ron Rennick 
and uses jquery.cycle by malsup, jquery.clickable by Sander Aarts and jquery.tipTip by Drew Wilson ( http://code.drewwilson.com/entry/tiptip-jquery-plugin ).
Sorting code is based on a tutorial by Ryan Marganti ( http://soulsizzle.com/jquery/create-an-ajax-sorter-for-wordpress-custom-post-types ).
Update code by Janis Elsts ( http://w-shadow.com/blog/2010/09/02/automatic-updates-for-any-plugin ).
*/

# check for updates
require 'plugin-update-checker.php';
$checkForUpdate = new PluginUpdateChecker('http://gigx.co.uk/wordpress/update/gigx-slides.json', __FILE__, 'gigx-slides', 1);
$checkForUpdate->checkForUpdates();
# /check for updates

# meta box
require 'gigx-slides-metabox.php';
# /meta box

# gigx_slide custom post type
require 'gigx-slides-post-type.class.php';
$gigx_slide_type = new GIGX_Slides_Post_Type();
# /gigx_slide custom post type

# gigx slides widget
require 'gigx-slides-widget.class.php';
function register_gigx_slides_widget() {
	register_widget( 'GIGX_Slides_Widget' );
}
add_action( 'widgets_init', 'register_gigx_slides_widget' );
# /gigx slides widget

# gigx_slide sorting functions
require 'gigx-slides-sorting.php';
# /gigx_slide sorting functions

# gigx_slide custom columns
require 'gigx-slides-custom-columns.php';
# /gigx_slide custom columns

# gigx_slide custom template
add_action("template_redirect", 'gigx_slides_template_redirect');

// Template selection
function gigx_slides_template_redirect()
{
	global $wp;
	global $wp_query;
	if (is_array("wp->query_vars") && $wp->query_vars["post_type"] == "gigx_slide")
	{
		// Let's look for the property.php template file in the current theme
		if (have_posts())
		{
			if ( file_exists( STYLESHEETPATH . '/single-gigx_slide.php' ) )include ( STYLESHEETPATH . '/single-gigx_slide.php' );
			else if ( file_exists( TEMPLATEPATH . '/single-gigx_slide.php' ) )include ( TEMPLATEPATH . '/single-gigx_slide.php' );
			else include ( 'single-gigx_slide.php' ); 
      //include(TEMPLATEPATH . '/property.php');
			die();
		}
		else
		{
			$wp_query->is_404 = true;
		}
	}
}
# gigx_slide custom template

# shortcode
function widget($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','widget'); 
?>