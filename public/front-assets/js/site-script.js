$(document).ready(function(){
    $('.categories-scroll').slick({
    	arrows: true,
       	dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 8,
		slidesToScroll: 8,
		responsive: [
			{
				breakpoint: 1300,
				settings: {
					slidesToShow: 6,
					slidesToScroll: 6,
					infinite: true
      			}
    		},
		]
    });
});


