var $j = jQuery.noConflict();
$j(function(){
	//alert('hi');


	$j('#bnrSlid').owlCarousel({
	    loop:false,
	    margin:0,
	    nav:true,
	    dots:false,
	    autoplay: true,
	    autoplayTimeout:3000,
	    items:1
	   // animateOut: 'fadeOut'
	});

	$j('#coursSlide').owlCarousel({
	    loop:true,
	    margin:20,
	    nav:false,
	    dots:true,
	    autoplay: false,
	    autoplayTimeout:3000,
	    items:1,
	  	responsive:{
	        0:{
	            items:1
	        },
	        480:{
	            items:2
	        },
	        1000:{
	            items:3
	        }
    	}
	});

	$j('.tglBtn').click(function(){
		$j(this).toggleClass('active');
		$j('#contactHlp').toggleClass('active');
	});

	$j('#cmtBtn').click(function(){
		$j(this).toggleClass('active');
		$j('.wpcf7-form-control-wrap.message').toggleClass('active');
	});

	$j('.galImg').nivoLightbox();
});


// $j(window).resize(function(){
// 		console.log('height');
// 		console.log(window.innerHeight())
// 	});	