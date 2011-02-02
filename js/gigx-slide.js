var $j = jQuery.noConflict();

$j(document).ready(function() {
  // slideshow
  	$j('.gigx-slideshow-wrapper').cycle({
  		fx: 'fade',
  		timeout: 7000,
  		speed: 400,
  		pager: $j('.gigx-slideshow-pager'),
  		pagerAnchorBuilder: function(idx, slide) { 
          // return selector string for existing anchor 
          return '.gigx-slideshow-pager li:eq(' + idx + ') a'; 
      } 
  	});
	
	//make slides clickable
    $j('div.gigx-slide').clickable();
          
	//tooltips 

        $j(".gigx-slideshow-pagerbutton").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 100});

          
});
           