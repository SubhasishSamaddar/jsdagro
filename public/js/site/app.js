var $j = jQuery.noConflict();
$j(function(){
	//alert('hi');


	$j('#bnrSlid').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:false,
	    dots:true,
	    autoplay: true,
	    autoplayTimeout:3000,
	    items:1,
	    animateOut: 'fadeOut'
	});


	
	$j('#ourPertnerSlid').owlCarousel({
	    loop:true,
	    margin:20,
	    nav:false,
	    autoplay: false,
	    dots:true,
	    responsive:{
	        0:{
	            items:1
	        },
	        480:{
	            items:2
	        },
	        768:{
	            items:3
	        },
	        992:{
	            items:4
	        },
	    }
	});

});

