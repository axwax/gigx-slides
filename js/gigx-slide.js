var $j = jQuery.noConflict();

$j(document).ready(function() {
	// slideshow
  	$j('.gigx-slideshow-wrapper').cycle({
  		fx: 'fade',
  		timeout: 7000,
  		speed: 400,
		containerResize: 1,
		fit:           1,     // force slides to fit container 
		height:        '225px',// container height (if the 'fit' option is true, the slides will be set to this height as well) 
		pause:         1,     // true to enable "pause on hover" 
		pauseOnPagerHover: 1, // true to pause when hovering over pager link 
  		pager: $j('.gigx-slideshow-pager'),
  		pagerAnchorBuilder: function(idx, slide) { 
			// return selector string for existing anchor 
			return '.gigx-slideshow-pager li:eq(' + idx + ') a'; 
		} 
  	});
	
	//make slides clickable
	$j('div.gigx-slide').fitted();
          
	//tooltips 
        $j(".gigx-slideshow-pagerbutton").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 200});          
});
           