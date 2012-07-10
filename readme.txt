=== GIGX Slides Widget ===
Tags: custom, post, gallery, widget
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 0.1.0

A rotating gallery widget using a custom post type for gallery content.

== Description ==

This plugin adds a Featured Slides menu to your WordPress dashboard. To add content to the rotating gallery:

1. Add a new Gallery Post for each image/headline to be shown in the rotating gallery
1. Attach one or more images to the gallery post by uploading the image(s) with the media uploader while editing the post
1. Enter the title & content that you would like to overlay the image
1. Publish the post(s)
1. Add the Rotating Post Gallery widget to widget area on your home page
1. Enter the number of Posts to rotate in the gallery (default = All).
1. Choose the size of image to display in the gallery (based on your Media Settings)

When multiple images are attached to a Gallery Post one of the images is randomly selected to be shown on each page view.

[Plugin Page](http://gigx.co.uk/wordpress/plugins/gigx-slides-widget/)

Credits:
This plugin is based on Post Gallery Widget by Ron Rennick 
and uses jquery.cycle by malsup, jquery.clickable by Sander Aarts and jquery.tipTip by Drew Wilson.
Sorting code is based on a tutorial by Ryan Marganti.
Update code by Janis Elsts.

== Installation ==

1. Upload the entire `gigx-gallery-widget` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.1.0 =
* updated cycle to v2.9999
* pause on hover
* use fitted.js instead of clickable.js
* added target tickbox
*renamed menus to "Slides"
*swapped image and text div in widget markup
= 0.0.5 =
* FEATURE: filter slides by odd/even week
= 0.0.4 =
* CHANGE: now includes days, rather than excludes
* FEATURE: now displays visible days and slide thumbnails in GIGX Slides and Sort screens
= 0.0.3 =
* Fixed Frontend formatting
* Added "exclude Days" functionality
* Uploaded to GitHub
* Changed tooltip to tipTip
= 0.0.2 =
* Added automatic update function.
* Added sorting option.
= 0.0.1 =
* Original version.

