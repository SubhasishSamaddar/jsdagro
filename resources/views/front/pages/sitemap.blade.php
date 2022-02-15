@extends('layouts.front')
@section('seo-header')
		<title>{{ $pageTitle }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
@section('content')
<div class="container">
	<div class="product-listing-outer">
		<div class="product-listing-head">
			<div class="product-cat-title">{{ $pageTitle }}</div>
		</div>
		<div class="product-list-view">
			<div class="product-boxes"> 
				<div class="cmspagedescription">
					<p style="margin: 20px 20px 20px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
					<p style="margin: 20px 20px 20px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				</div>						
			</div>
		</div>
	</div>
</div>	
<br>	
<br>	
@endsection
@section('css')

@endsection
@section('js')

@endsection



