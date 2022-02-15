<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		@yield('seo-header')
		<!-- Bootstrap styles -->
		<link href="{{ url('/').asset('/front-assets/css/bootstrap.css') }}" rel="stylesheet"/>
		<!-- Slick styles -->
		<link href="{{ url('/').asset('/front-assets/css/slick.css') }}" rel="stylesheet"/>
		<!-- Customize styles -->
		<link href="{{ url('/').asset('/front-assets/css/style.css') }}" rel="stylesheet"/>
      <link href="{{ url('/').asset('/front-assets/css/responsive.css') }}" rel="stylesheet"/>
		<!-- font awesome styles -->
		<link href="{{ url('/').asset('/front-assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
		<!-- Favicons -->
		<link rel="shortcut icon" href="{{ url('/').asset('/front-assets/ico/favicon.ico') }}">

		<style>
			.ui-autocomplete {
				position: absolute; overflow-y: scroll; height: 400px;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 180px;
    padding: 0px;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #EEE;
    /* border-color: rgba(0, 0, 0, 0.2); */
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 0px 0px 4px 4px;
    /* -webkit-box-shadow: 0 5px 10px rgb(0 0 0 / 20%); */
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    /* box-shadow: 0 5px 10px rgb(0 0 0 / 20%); */
    /* -webkit-background-clip: padding-box; */
    -moz-background-clip: padding;
    /* background-clip: padding-box;*/
				*border-right-width: 2px;
				*border-bottom-width: 2px;
			}
          
          .ui-autocomplete > li > div { font-family: 'Roboto', sans-serif;
    font-size: 13px;
    color: #7c7c7c;
    font-weight: 400; padding: 7px 10px; border-bottom: #eeeeee 1px solid; cursor: pointer; }

			.ui-menu-item > a.ui-corner-all {
				display: block;
				padding: 10px 15px;
				clear: both;
				font-weight: normal;
				line-height: 18px;
				color: #555555;
				white-space: nowrap;
				text-decoration: none;
			}

			.ui-state-hover, .ui-state-active {
				color: #000;
				text-decoration: none;
				background-color: #eee;
				
			}

			.ui-menu-item:hover{
				cursor: pointer;
				background: #eee;
			}



		</style>



	</head>
	<body>  
	@include('includes.front-header')

@yield('content')

@include('includes.front-footer')
		<a href="#" class="gotop"><i class="icon-double-angle-up"></i></a>
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="{{ url('/').asset('/front-assets/js/jquery.js') }}"></script>
		<script src="{{ url('/').asset('/front-assets/js/bootstrap.min.js') }}"></script>
		<script src="{{ url('/').asset('/front-assets/js/jquery.easing-1.3.min.js') }}"></script>
		<script src="{{ url('/').asset('/front-assets/js/jquery.scrollTo-1.4.3.1-min.js') }}"></script>
		<script src="{{ url('/').asset('/front-assets/js/shop.js') }}"></script>
		<script src="{{ url('/').asset('/front-assets/js/slick.min.js') }}"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@yield('css')
@yield('js')		





<script>
	 function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
        textbox.addEventListener(event, function() {
        if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
            this.value = "";
        }
        });
    });
    }
	setInputFilter(document.getElementById("swadesh_hut_id"), function(value) {
		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
	function getSwadeshHut(){
		var ID=$("#swadesh_hut_id").val();
		//alert(ID);
		$("#msgstorename").html('');
		$("#storeid").html('');
		$.ajax({      
			type: "GET",
			url: "{{ url('get-swadesh-hut') }}",   
			data: {pincode : ID},       
			success:   
			function(data){  
				var jsonData = $.parseJSON(data);
				if(jsonData.status=='1'){
					/*
					$("#cartstoreid").html(jsonData.msg);
					$("#msgstorename").html(jsonData.msg);
					$("#storeid").html(jsonData.msg);
					$("#processtocheckout").prop('disabled', false);
					*/
					location.reload();
				}else{
					$("#msgstorename").show();
					$("#msgstorename").html(jsonData.msg);
					//alert("dddd");
					//$("#myModal").modal('show');
					//$('#myModal').modal({ backdrop: 'static' }, 'show');
				}
				//location.reload();
			}
		});
		
	}
</script>
<script type="text/javascript">
    $(document).ready(function(){
      	$('.categories-scroll').slick({
      		arrows: true,
        	dots: false,
			infinite: true,
			speed: 300,
			slidesToShow: 8,
			slidesToScroll: 8,
			variableWidth: true,
			responsive: [
		    {
		      breakpoint: 768,
		      settings: {
		        arrows: false,
		        slidesToShow: 3,
		        slidesToScroll: 3
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        arrows: false,
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    }
			]
      	});

      	$( '.menu_toggle' ).on('click', function(){
      		$( this ).toggleClass('opened');
      		$( '.topnav' ).toggleClass('opened');
      	});


    });
</script>

<script>

	$('.searchtext').autocomplete({
      source: "{{url('autocomplete')}}",
      minLength: 1,
      select: function(event, ui) {
			event.preventDefault();
			$("#searchtext").val(ui.item.prod_name);
			/*var elem = $(event.originalEvent.toElement);
			if (elem.hasClass('ac-item-a')) {
				var url = elem.attr('data-url');
				event.preventDefault();
				window.open(url, '_blank ');
			}*/
			window.location = "https://jsdagro.com/products/"+(ui.item.name_url);
		},
		focus: function(event, ui) {
			event.preventDefault();
			$("#searchtext").val(ui.item.prod_name);
		}
    }).data('ui-autocomplete')._renderItem = function(ul, item){
      return $("<li class='ui-autocomplete-row'></li>")
        .data("item.autocomplete", item)
		.append('<a>')
		.append('<span class="ac-item-a" data-url="https://jsdagro.com/products/'+item.id+'">')
		.append('<img src="https://jsdagro.com/public/storage/'+item.product_image+'" style="width:25px;height:25px;">')
        .append(item.prod_name)
		.append('</span>')
		.append('</a>')
        .appendTo(ul);
    };






</script>

	</body>
</html>