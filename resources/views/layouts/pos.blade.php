<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		@yield('seo-header')
		<!-- Bootstrap styles -->
		<link href="{{ asset('/front-assets/css/bootstrap.css') }}" rel="stylesheet"/>
		<!-- Slick styles -->
		<!-- Customize styles -->
		<link href="{{ asset('/front-assets/css/style.css') }}" rel="stylesheet"/>
      <link href="{{ asset('/front-assets/css/responsive.css') }}" rel="stylesheet"/>
		<!-- font awesome styles -->
		<link href="{{ asset('/front-assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
		<!-- Favicons -->
		<link rel="shortcut icon" href="{{ asset('/front-assets/ico/favicon.ico') }}">

		<style>
			.ui-autocomplete {
				position: absolute;
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
	@include('includes.pos-header')
	<div class="container-pos">
@yield('content')
</div>
@include('includes.front-footer')
		<a href="#" class="gotop"><i class="icon-double-angle-up"></i></a>
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="{{ asset('/front-assets/js/jquery.js') }}"></script>
		<script src="{{ asset('/front-assets/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('/front-assets/js/jquery.easing-1.3.min.js') }}"></script>
		<script src="{{ asset('/front-assets/js/jquery.scrollTo-1.4.3.1-min.js') }}"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@yield('css')
@yield('js')		


	</body>
</html>