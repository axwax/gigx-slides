<?php
/*
Plugin Name: GIGX Slides
Plugin URI: http://gigx.co.uk/wordpress/plugins/gigx-slides/
Description: A Rotating Gallery Widget using a custom post type to create GIGX Slides.
Author: AxWax
Version: 0.0.3
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
?>