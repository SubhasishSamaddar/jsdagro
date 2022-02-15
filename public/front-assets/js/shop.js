$(document).ready(function() {
    
    $(".myaccn").hover(function() {
        $(".submyaccn").show();
    });
    $(".submyaccn").hover(function() {
        $(".submyaccn").show();
    });
    $(".myaccn").mouseleave(function() {
        $(".submyaccn").hide();
    });
    $(".submyaccn").mouseleave(function() {
        $(".submyaccn").hide();
    });
    
    
    $("body").fadeIn(400);

    $('#myCarousel').carousel()
    $('#newProductCar').carousel()

    /* Home page item price animation */
    $('.thumbnail').mouseenter(function() {
     $(this).children('.zoomTool').fadeIn();
    });

    $('.thumbnail').mouseleave(function() {
    	$(this).children('.zoomTool').fadeOut();
    });

    // Show/Hide Sticky "Go to top" button
	$(window).scroll(function(){
		if($(this).scrollTop()>200){
			$(".gotop").fadeIn(200);
		}
		else{
			$(".gotop").fadeOut(200);
		}
	});
// Scroll Page to Top when clicked on "go to top" button
	$(".gotop").click(function(event){
		event.preventDefault();

		$.scrollTo('#gototop', 1500, {
        	easing: 'easeOutCubic'
        });
	});
	
	$(".storeLocation").click(function(event){
	    
	     $(".storezipchanger").toggle();
	     $(".storezipoverlay").toggle();
	});
	
	$(".shopbycategories").click(function(event){
	    
	     $(".menus").show();
	});
	
	$(".menus").mouseleave(function(event){
	    
	     $(".menus").hide();
	});
	
	


});


window.onscroll = function() {myFunction()};

var header = document.getElementById("head");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}