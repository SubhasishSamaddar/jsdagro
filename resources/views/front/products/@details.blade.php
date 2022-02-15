@extends('layouts.front')

@section('content')
<div class="container">
	<hr>

	
<div class="card">
	<div class="row">
		<aside class="col-sm-5 border-right">
			<article class="gallery-wrap"> 
				<div class="img-big-wrap">
					<div>
						<a href="#"><img src="{{ asset('/storage/'.$details->product_image) }}"></a>
					</div>
				</div>
			</article>
		</aside>
		<aside class="col-sm-7">
			<article class="card-body p-5">
				<h3 class="title mb-3">{{ $details->prod_name }}</h3>

				<p class="price-detail-wrap"> 
					<span class="price h3 text-warning"> 
						<span class="currency">$</span><span class="num">{{ $details->price }}</span>
					</span> 
					<span>/per kg</span> 
				</p>
				<dl class="item-property">
					<dt>Description</dt>
					<dd><p>{{ $details->description }}</p></dd>
				</dl>
				<dl class="item-property">
					<dt>Category</dt>
					<dd><p>{{ $details->category_name }}</p></dd>
				</dl>

<hr>

	<!--a href="#" class="btn btn-lg btn-primary text-uppercase"> Buy now </a-->
	<a onclick="addToCart({{ $details->id }});" class="btn btn-lg btn-outline-primary text-uppercase"> <i class="fas fa-shopping-cart"></i> Add to cart </a>
</article> <!-- card-body.// -->
		</aside> <!-- col.// -->
	</div> <!-- row.// -->
</div> <!-- card.// -->


</div>
@endsection


<script>
function addToCart(ID){
	$.ajax({      
		type: "GET",
		url: "{{ url('product-add-to-cart') }}",   
		data: {product_id : ID},       
		success:   
		function(data){
			var jsonData = $.parseJSON(data);
			alert(jsonData.msg);
			$("#shprocnt").html(jsonData.status+' Item(s)');
			/*
			var jsonData = $.parseJSON(data);
			if(jsonData.count>'0'){
				$("#cart_list").html(jsonData.data);
				$("#product_cart").html(jsonData.count);
			}else{

			}
			*/
		}
	});
}
</script>
@section('css')
@endsection
@section('js')
<script>

  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Active' : 'Inactive';
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
			url: "{{ url('cpanel/products/changestatus') }}",
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection
