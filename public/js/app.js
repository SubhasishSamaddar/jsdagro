var $j = jQuery.noConflict();

$j(function(){
	//alert('hi');

	$j('.re_repFrm').hide();
	$j('.leavCmnt').on('click', function(event){
		$j(this).siblings('.re_repFrm').toggle('slow');
	});

    $j('.galImg').nivoLightbox();
});
