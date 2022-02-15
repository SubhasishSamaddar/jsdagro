@extends('layouts.pos')
@section('seo-header')
		<title>{{ $pageTitle }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
@endsection
@section('content')

	
	
<form autocomplete="off" id="pos_cart_form" method="post">
        
		<div class="row gutters-10">
                <div class="col-md">
                    <div class="row gutters-5 mb-3">
                        <div class="col-md-12 mb-2 mb-md-0">
                        
                            <div class="form-group mb-0">
                                
                                <input class="form-control form-control-lg" type="text" id="barcode_text" placeholder="Add Products by Barcode" autocomplete="false">
                                
                                <input class="form-control form-control-lg" type="text" id="product_keyword" placeholder="Search by Product Name" onkeyup="filterProducts()">

                                <!--<div id="pagination" style="margin-right:40px;">{!! $pagination_html !!}</div>-->
                            </div>
                            <input type="hidden" id="products_cart_ids" value="0">	
                        </div>
                    </div>
                    
                    <div class="aiz-pos-product-list c-scrollbar-light product-container">
                        <div class="d-flex flex-wrap justify-content-center" id="product-list">
                        <?php
                        if($all_products)
                        {
                            foreach($all_products as $products)
                            {
                                if( $products->discount && $products->discount >0 )
                                {
                                    $pro_price=((100-$products->discount)*$products->price/100); 
                                }
                                else
                                {
                                    $pro_price=$products->price;
                                }

                                $no_dtls = explode('.',$products->weight_per_pkt);
                                $after_decimal_number = $no_dtls[1];
                                if($after_decimal_number=='00')
                                {
                                    $show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
                                }
                                else 
                                {
                                    $show_weight = $products->weight_per_pkt;
                                }

                                ?>
                                <div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray('<?php echo $products->id; ?>')">
                                    <div class="card bg-white c-pointer product-card hov-container">
                                        <div class="position-relative">
                                            <span class="absolute-top-left mt-1 ml-1 mr-0">
                                                <span class="badge badge-inline badge-success fs-13">In stock
                                                : {{$products->available_qty}}</span>
                                            </span>
                                            
                                            <?php
									if (File::exists(public_path('storage/'.$products->product_image))) { ?>
										<img src="{{ asset('/storage/'.$products->product_image)}}" alt="" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
                                     <?php } else { ?>
	                                    <img src="{{ asset('/storage/product.png') }}" alt="" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
                                    <?php  } ?>
                                            
                                            
                                           <!-- <img src="{{asset('/storage/'.$products->product_image)}}" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">-->
                                        </div>
                                        <div class="card-body p-2 p-xl-3">
                                            <div class="text-truncate fs-14 mb-2">{{$products->prod_name}}</div>
                                            <div class="prd_weight">{{$show_weight}} {{ $products->weight_unit }}</div>
                                            <div class="price">
                                                <?php if(number_format($products->max_price,2)!=number_format($pro_price,2)) {?><del class="mr-2 ml-0">₹{{number_format($products->max_price,2)}}</del><?php } ?><span>₹{{number_format($pro_price,2)}}</span>
                                            </div>
                                        </div>
                                        <div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">
                                            <div class="absolute-full bg-dark opacity-50">
                                            </div>
                                            <i class="las la-plus absolute-center la-6x text-white"></i>
                                        </div>
                                    </div>                            
                                </div>
                            <?php
                            }
                        }
                        ?>

                        


                        <span class="scroll-container"></span>
                        <!--<span class="loading-container"><img src="<?php echo url('/') ?>/public/img/loading.gif"></span>-->                       
                        <br clear="all">
                        <div class="load-more-container"></div>
                   
                   
                    </div>
                    
                        <div id="load-more" class="text-center">
                            
                        </div>
                        
                    </div>
                    <div id="pagination" style="margin-right:40px;">{!! $pagination_html !!}</div>

                </div>
                


               




                <div class="col-md-auto w-md-350px w-lg-400px w-xl-500px">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex border-bottom pb-3">
                                
                                <select name="user_id" id="user_id" class="form-control aiz-selectpicker pos-customer" data-live-search="true" onchange="setaddress()" tabindex="0">
                                        <option value="">Walk In Customer</option>
                                        <?php
                                        if(!empty($registered_users))
                                        {
                                            $current_name = '';
                                            foreach($registered_users as $users)
                                            {
                                                if($current_name!=$users->name)
				                                {
                                                    echo '<option value="'.$users->id.'">'.$users->name.'</option>';
                                                }
                                                $current_name = $users->name;		
                                            }	
                                            
                                        }
                                        ?>
                                        </select>
                                

                                <button type="button" class="btn btn-icon btn-soft-dark ml-3 mr-0" data-target="#new-address-modal" data-toggle="modal">
                                    <i class="las la-truck"></i>
                                </button>
                                
                            </div>
                            
                            
                            <div class="" id="cart-details">
                                <div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
                                    <ul class="list-group list-group-flush" id="productcartlist">
                                    </ul>
                                </div>
                                <div id="cart_price_details">
                                
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="pos-footer mar-btm">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div class="d-flex">
                                <!--<div class="dropdown mr-3 ml-0 dropup">
                                    <button class="btn btn-outline-dark btn-styled dropdown-toggle" type="button" data-toggle="dropdown">
                                        Shipping
                                    </button>
                                    <div class="dropdown-menu p-3 dropdown-menu-lg">
                                        <div class="input-group">
                                            <input type="number" min="0" placeholder="Amount" name="shipping" class="form-control" value="0" required="" onchange="setShipping()">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Flat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown dropup">
                                    <button class="btn btn-outline-dark btn-styled dropdown-toggle" type="button" data-toggle="dropdown">
                                        Discount
                                    </button>
                                    <div class="dropdown-menu p-3 dropdown-menu-lg">
                                        <div class="input-group">
                                            <input type="number" min="0" placeholder="Amount" name="discount" class="form-control" value="0" required="" onchange="setDiscount()">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Flat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                                
                            </div>
                            <div class="my-2 my-md-0">
                                <button type="button" class="btn btn-primary btn-block" onclick="orderConfirmation()">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<br>	




<!---Modal -->
<div id="new-address-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6"><strong>Shipping & Payments</strong></h4>
                    
                </div>
                
                    <div class="modal-body">
                        <input type="hidden" name="customer_id" id="set_customer_id" value="">
                        <span id="new_customer_add_span">
                            <input type="hidden" placeholder="Anonymous" id="billing_user_id" name="billing_user_id" value="">
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="city">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Anonymous" id="billing_name" name="billing_name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="city">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="anonymous@gmail.com" id="billing_email" name="billing_email" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="address">Address</label>
                                    <div class="col-sm-12">
                                        <textarea placeholder="Anonymous Address" id="billing_address" name="billing_address" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="email">Country</label>
                                    <div class="col-sm-10">
                                        <select name="billing_country" id="billing_country" class="form-control aiz-selectpicker" required data-placeholder="Select country">
                                            <option value="India">India</option>                                                                                    
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="city">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Kolkata" id="billing_city" name="billing_city" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="postal_code">Postal Code</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" placeholder="712232" id="billing_postal_code" name="billing_postal_code" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class=" row">
                                    <label class="col-sm-2 control-label" for="phone">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" placeholder="Phone" id="billing_phone" name="billing_phone" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </span>

                        <span id="existing_customer_add_span" style="display:none">
                        <div class="form-group">

                            

                            <div id="address_container">
                            <span id="billing_name_html"></span><br clear="all">
                            <span id="billing_email_html"></span><br clear="all">
                            <span id="billing_address_html"></span><br clear="all">
                            <span id="billing_city_html"></span><br clear="all">
                            <span id="billing_postal_code_html"></span><br clear="all">
                            <span id="billing_phone_html"></span><br clear="all">
                            </div>

                                
                        </div>
                        </span>

                        <div class="row">
                            <div style="width:50%;">
                                <h4>Shipping Type</h4>
                                <input type="radio" name="shipping_type" id="pick_up_from_store" value="pick_up_from_store">Pick Up From Store<br>
                                <input type="radio" name="shipping_type" id="home_delivery" value="home_delivery">Home Delivery
                            </div>
                            <div style="width:50%;">
                                <h4>Payment Type</h4>
                                <input type="radio" name="payment_type" id="cash_at_store" value="cash_at_store">Cash At Store<br>
                                <input type="radio" name="payment_type" id="cash_on_delivery" value="cash_on_delivery">Cash On Delivery<br>
                                <input type="radio" name="payment_type" id="mobile_payment" value="mobile_payment">Mobile Payment
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">Close</button>
                    </div>
                
            </div>
        </div>
    </div>
<!--End Modal--->



<!---Modal
<div id="new-shipping-payment-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">Shipping Address</h4>
                    
                </div>
                
                                       
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">Close</button>
                    </div>
                
            </div>
        </div>
    </div>
End Modal--->



<!---
<div id="registered-customer-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">Shipping Address</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                    <div class="modal-body" id="user_modal_body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">Close</button>
                    </div>
                
            </div>
        </div>
    </div>
End Modal--->
<input type="hidden" id="total_cart_product" name="total_cart_product" value="0">

<input type="hidden" id="total_page" value="<?php echo round($total_page); ?>">

</form>
@endsection


@section('css')
<style>
#address_container {
  background-color: lightgrey;
  width: 90%;
  border: 15px solid green;
  padding: 50px;
  margin: 20px;
}

.pagination li {
    display: inline;
    width:40px;
    padding: 5px 5px 5px 5px;
    text-align:center;
    float:left;
    background: #e62e04;
    /* Old browsers */
    background: linear-gradient(to left, red 50%, blue 50%);
    background-size: 200% 100%;
    background-position:left bottom;
    margin-left:10px;
    margin-top: 5px;
    transition:all 0.5s ease;
}

.pagination li page-item .disabled{
  display: none;
}

.pagination li a {
    color:white;
}

</style>
@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://jsdagro.com/public/front-assets/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script>
$(document).ready(function(){
    $("#users").select2();
   
    
    $('#pagination a').on('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var urlsplit = url.split("=");
        infinteLoadMore2(urlsplit[1]);
    });
})

var page = 1;
var total_page = $("#total_page").val();
var count = 0;
jQuery(function($) {
    $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            infinteLoadMore(page);
        }
        /*else 
        {
            if(count<total_page){
                infinteLoadMore2(page);
            }
        }*/
    })
});

/*var page = 1;
$(".product-container").scroll(function() {
   $(".loading-container").show();
   var hT = $('#load-more').offset().top,
       hH = $('#load-more').outerHeight(),
       wH = $(window).height(),
       wS = $(this).scrollTop();
    console.log((hT-wH) , wS);
    if(wS > (hT+hH-wH)){
        page++;
        setTimeout(function(){infinteLoadMore(page)}, 5000);
   }
});*/

$(function() {
    $('.product-container').jscroll({
        autoTrigger: true,
        padding: 0,
        
    });
});


var ENDPOINT = "{{ url('/') }}";
function infinteLoadMore(page) {
    var product_keyword = $("#product_keyword").val();
    //$(".loading-container").show();
    $.ajax({      
		type: "POST",
		url: "{{ url('lazy-load') }}",   
		data: {'product_keyword':product_keyword,'page': page,'_token': '{{ csrf_token() }}'},       
		success:     
		function(data){
			$(".loading-container").hide();
			$("#product-list").append(data.product_html);
            $("#load-more").html(data.end_html)
		}
	});
}

function infinteLoadMore2(page) {
    var product_keyword = $("#product_keyword").val();
    $(".loading-container").show();
    $.ajax({      
		type: "POST",
		url: "{{ url('lazy-load2') }}",   
		data: {'product_keyword':product_keyword,'page': page,'_token': '{{ csrf_token() }}'},       
		success:     
		function(data){
            $(".loading-container").show();
            $("#product-list").html(data.product_html);
            $(".loading-container").hide();
		}
	});
}


</script>

<script>
function filterProducts(){
	var product_keyword = $("#product_keyword").val();
    if(product_keyword!=''){
        $.ajax({      
            type: "POST",
            url: "{{ url('filter-products-by-keyword') }}",   
            data: {'product_keyword': product_keyword,'_token': '{{ csrf_token() }}'},       
            success:     
            function(data){
                $("#pagination").show();
                $("#load_img").hide();
                $("#product-list").html(data.product_html);
                $("#pagination").html(data.pagination_html);
            }
        });
    }else{
        //$("#product_keyword").val('');
        //filterProducts();
        show_all_product();
    }
}


function show_all_product()
{
    $.ajax({      
        type: "POST",
        url: "{{ url('show-all-product') }}",   
        data: {'_token': '{{ csrf_token() }}'},       
        success:     
        function(data){
            $("#pagination").show();
            $("#product-list").html(data.product_html);
            $("#pagination").html(data.pagination_html);
        }
    });
}


function showpossearchdata(page){
    var product_keyword = $("#product_keyword").val();
    $.ajax({      
		type: "POST",
		url: "{{ url('filter-products-by-pageno') }}",   
		data: {'page': page,'product_keyword': product_keyword,'_token': '{{ csrf_token() }}'},       
		success:     
		function(data){
			$("#product-list").html(data.product_html);
		}
	});
}


$("#barcode_text").keypress(function(event) {
    if (event.which == 13) {
        var barcode_text = $("#barcode_text").val();
        $.ajax({      
            type: "POST",
            url: "{{ url('get-exact-product-by-barcode') }}",   
            data: {'barcode_text': barcode_text,'_token': '{{ csrf_token() }}'},       
            success:     
            function(data){
                if(data.product_count==0)
                {
                    alert("No Product With This Barcode Exists!!");
                    event.preventDefault();
                    return false;
                }
                else 
                {
                    $("#product-list").html(data.product_html);
                    setSessionIdArray(data.product_id);
                    $("#barcode_text").focus();
                    $("#barcode_text").val('');
                    $("#pagination").hide();
                    event.preventDefault();
                }
            }
        });
        
        event.preventDefault();
     }
});

function setSessionIdArray(product_id)
{
    var products_cart_ids = $("#products_cart_ids").val();
    $.ajax({      
		type: "POST",
		url: "{{ url('set-session-id-array') }}",   
		data: {'product_id': product_id,'_token': '{{ csrf_token() }}','products_cart_ids': products_cart_ids},       
		success:     
		function(data){
			$("#products_cart_ids").val(data.products_cart_ids);
            generateCartData(product_id,data.products_cart_ids);
		}
	});
}

function generateCartData(product_id,products_cart_ids)
{
    var total_cart_product = $("#total_cart_product").val();
    $.ajax({      
		type: "POST",
		url: "{{ url('generate_cart_data') }}",   
		data: {'_token': '{{ csrf_token() }}','product_id': product_id,'products_cart_ids': products_cart_ids},       
		success:     
		function(data){
            
            if(total_cart_product==0)
            {
                $("#cart_price_details").html(data.price_html);
                $("#total_cart_product").val(5);
                $("#productcartlist").append(data.list_html);
            }
            else 
            {
                var totalPrice = 0;
                $('input[name="form_product_price[]"]').each( function() {
                    totalPrice += parseFloat(this.value);      
                });

                var product_count = 0;
                $('input[name="form_product_id[]"]').each( function() {
                    if(product_id==this.value){
                        product_count++;
                    }
                });

                if(product_count==0){
                    $("#productcartlist").append(data.list_html);
                    $("#cart_price_details").html('<div class="d-flex justify-content-between fw-600 fs-18 border-top pt-2"><span>TOTAL</span><input type="text" name="form_discount_amount" id="form_discount_amount" onkeyup="set_discounted_price()" placeholder="Enter Discount Amount" style="width:200px;margin-bottom:10px;margin-left:10px;"><span>₹<span id="total_cart_amount">'+(totalPrice.toFixed(2))+'</span></span></div><input type="hidden" id="form_total_cart_amount" name="form_total_cart_amount" value="'+(totalPrice.toFixed(2))+'">');
                    var curr_price = 0;
                    $('input[name="form_product_price[]"]').each( function() {
                        curr_price += parseFloat(this.value);      
                    });
                    $("#total_cart_amount").html(curr_price.toFixed(2));
                    $("#form_total_cart_amount").val(curr_price.toFixed(2));
                }
                else{
                    $("#cart_price_details").html('<div class="d-flex justify-content-between fw-600 fs-18 border-top pt-2"><span>TOTAL</span><input type="text" name="form_discount_amount" id="form_discount_amount" onkeyup="set_discounted_price()" placeholder="Enter Discount Amount" style="width:200px;margin-bottom:10px;margin-left:10px;"><span>₹<span id="total_cart_amount">'+(totalPrice.toFixed(2))+'</span></span></div><input type="hidden" id="form_total_cart_amount" name="form_total_cart_amount" value="'+(totalPrice.toFixed(2))+'">');
                    updateQuantity(product_id,data.single_price,0);
                }
                
            }
            
		}
	});
}


function updateQuantity(id,price,tax)
{
 var qty = $("#qty-"+id).val();
 var cur_qty = Number(qty)+1;

 var sprice = parseFloat(price).toFixed(2);
 var stprice = parseFloat(cur_qty*(parseFloat(sprice).toFixed(2))).toFixed(2);
 var stpricehtml = parseFloat(stprice).toFixed(2);

$.ajax({      
    type: "POST",
    url: "{{ url('check-availability') }}",   
    data: {'cur_qty': cur_qty,'product_id': id,'_token': '{{ csrf_token() }}'},          
    success:
    function(data){
        if(data.availableStock=='no'){
            alert("Sorry More Product Not Available!!");
            return false;
        }
        else 
        {
            $("#qty-"+id).val(cur_qty);
            $("#current_single_qty"+id).html(cur_qty);
            $("#current_single_total"+id).html(stpricehtml);
            $("#form_product_qty"+id).val(cur_qty);
            $("#form_product_price"+id).val(Number(cur_qty)*Number(price).toFixed(2));
            $("#form_product_tax"+id).val(Number(cur_qty)*Number(tax));
            getPriceDetails();
        }
    }
});
 
 
 
 
}


function decreaseQuantity(id,price,tax)
{
 var qty = $("#qty-"+id).val();
 if(qty > 1) {
 var cur_qty = Number(qty)-1;
 var sprice = parseFloat(price).toFixed(2);
 var stprice = parseFloat(cur_qty*(parseFloat(sprice).toFixed(2))).toFixed(2);
 var stpricehtml = parseFloat(stprice).toFixed(2);
 $("#qty-"+id).val(cur_qty);
 $("#current_single_qty"+id).html(cur_qty);
 $("#current_single_total"+id).html(stpricehtml);
 $("#form_product_qty"+id).val(cur_qty);
 $("#form_product_price"+id).val(Number(cur_qty)*Number(price).toFixed(2));
 $("#form_product_tax"+id).val(Number(cur_qty)*Number(tax));
 getPriceDetails();
 }
 
}


function getPriceDetails()
{
    var totalPrice = 0;
    $('input[name="form_product_price[]"]').each( function() {
        totalPrice += parseFloat(this.value);      
    });

    /*var totalTax = 0;
    $('input[name="form_product_tax[]"]').each( function() {
        totalTax += parseInt(this.value);      
    });

    var grand_price = Number(totalPrice)+Number(totalTax);*/

    //$("#sub_total_cart_amount").html(totalPrice);
    $("#total_cart_amount").html(totalPrice.toFixed(2));
    //$("#total_tax_amount").html(totalTax);

    //$("#form_sub_total_cart_amount").val(totalPrice);
    $("#form_total_cart_amount").val(totalPrice.toFixed(2));
    //$("#form_total_tax_amount").val(totalTax);

}


function removeFromCart(product_id)
{
    $("#pro_li"+product_id).remove();
    getPriceDetails();
}


function orderConfirmation()
{
    var payment_type = $('input[name="payment_type"]:checked').val();
    if(typeof payment_type==='undefined')
    {
        alert('Please Select Payment Type');
        return false;
    }
    var myform = document.getElementById("pos_cart_form");
    var fd = new FormData(myform );
    fd.append('_token', '{{ csrf_token() }}');
    $.ajax({      
		type: "POST",
		url: "{{ url('pos-place-order') }}",   
		data: fd,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',    
		success:     
		function(data){
            $("#productcartlist").html('<strong style="color:green;">'+data.msg+'</strong>');
            $("#cart_price_details").html('');
            var order_id = data.order_id;
            window.location.href = "<?php echo url('/') ?>/pos-order-details/"+order_id;
		}
	});
}


function show_registered_customer()
{
    $.ajax({      
		type: "POST",
		url: "{{ url('show_registerd_user') }}",   
		data: {'_token': '{{ csrf_token() }}'},          
		success:
        function(data){
            $("#user_modal_body").html(data.registered_users_html)
		}
	});
}


function setaddress()
{
    var user_id = $("#user_id").val();
    if(user_id=='')
    {
        $("#new_customer_add_span").show();
        $("#existing_customer_add_span").hide();
        $("#billing_user_id").val('');
        $("#billing_name").val('');
        $("#billing_email").val('');
        $("#billing_address").val('');
        $("#billing_city").val('');
        $("#billing_postal_code").val('');
        $("#billing_phone").val('');
        $("#new-address-modal").modal('show');
    }
    if(user_id!='')
    {
        $.ajax({      
		type: "POST",
		url: "{{ url('set_user_address') }}",   
		data: {'user_id': user_id,'_token': '{{ csrf_token() }}'},          
		success:
        function(data){
            $("#new_customer_add_span").hide();
            $("#existing_customer_add_span").show();

            $("#billing_user_id").val(user_id);
            $("#billing_name").val(data.name);
            $("#billing_email").val(data.email);
            $("#billing_address").val(data.address1);
            $("#billing_city").val(data.city);
            $("#billing_postal_code").val(data.pincode);
            $("#billing_phone").val(data.phone);

            $("#billing_name_html").html(data.name);
            $("#billing_email_html").html(data.email);
            $("#billing_address_html").html(data.address1);
            $("#billing_city_html").html(data.city);
            $("#billing_postal_code_html").html(data.pincode);
            $("#billing_phone_html").html(data.phone);

            

            $("#set_address_html").html(data.msg);
            $("#new-address-modal").modal('show');
            
		}
	});
    }
    
}


function set_discounted_price()
{
    var form_discount_amount = $("#form_discount_amount").val();
    var totalPrice = 0;
    $('input[name="form_product_price[]"]').each( function() {
        totalPrice += parseFloat(this.value);      
    });
    $("#form_total_cart_amount").val((Number(totalPrice)-Number(form_discount_amount)).toFixed(2));
    $("#total_cart_amount").html((Number(totalPrice)-Number(form_discount_amount)).toFixed(2));
}
</script>
@endsection



