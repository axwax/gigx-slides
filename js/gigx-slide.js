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
		pauseOnPagerHover: 1 // true to pause when hovering over pager link 
  		 
  	});
	
	//make slides clickable
	$j('div.gigx-slide').fitted();
          
	//tooltips 
        $j(".gigx-slideshow-pagerbutton").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 200});

	//$j('.gigx-slide-text').show("slow");

	$j('.gigx-slide-text').each(function(){  
        //...set the opacity to 0...  
        $j(this).css('opacity', 0);  
        //..set width same as the image...  
        $j(this).css('width', $j(this).siblings('img').width());  
        //...get the parent (the wrapper) and set it's width same as the image width... '  
        $j(this).parent().css('width', $j(this).siblings('img').width());  
        //...set the display to block  
        $j(this).css('display', 'block');
	});  
  
    $j('.gigx-slide').hover(function(){  
        //when mouse hover over the wrapper div  
        //get it's children elements with class description '  
        //and show it using fadeTo  
        $j(this).children('.gigx-slide-text').stop().fadeTo(500, 0.9);  
    },function(){  
        //when mouse out of the wrapper div  
        //use fadeTo to hide the div  
        $j(this).children('.gigx-slide-text').stop().fadeTo(300, 0);  
    });
	
	
});
           