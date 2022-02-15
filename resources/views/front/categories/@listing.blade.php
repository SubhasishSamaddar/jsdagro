@extends('layouts.front')
<?php //echo '<pre>';print_r($categories);?>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="pull-right">
                <div class="btn-group">
                   Product Listing
                </div>
            </div>
        </div>
    </div> 
	
	       <div class="row">
        <div class="container">
          <hr />  
        </div>
      </div>

      

	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-tabs left-tabs sideways-tabs">
				<?php $previousParentId=0;
				$i=1
				?>
				@foreach($categories as $data)
					@if($data->parent_id!=$previousParentId )
						<?php 
						if($i==1):
							$active='active';
						else:
							$active='';
						endif;
						?>
					<li class="nav-item">
						<a class="nav-link {{ $active }}" href="#tab<?php echo $data->parent_id;?>" data-toggle="tab">{{ $data->parent_name }}</a>
					</li>
					<?php $previousParentId=$data->parent_id;?>
					@endif
				<?php $i++;?>
				@endforeach
			</ul>
		</div>

        <div class="col-md-10">
          <div class="container">
            <div class="tab-content">
      
				<?php $previousParentId=0;
				$i=1
				?>
				@foreach($categories as $data)
					@if($data->parent_id!=$previousParentId )
						<?php 
						if($i==1):
							$active='active';
						else:
							$active='';
							echo '</div>
						</article>';
						endif;
						?>
						
						<article class="tab-pane container <?php echo $active;?>" id="tab<?php echo $data->parent_id;?>">
							<h3></h3>
							<div id="products" class="row view-group">
								<div class="item col-xs-12 col-lg-12">
									<div class="thumbnail ">
										<div class="img-event">
											<img class="group list-group-image" src="{{ asset('/storage/'.$data->category_image)}}" alt="" width="100%" height="150px;"/>
										</div>
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<h4 class="group card-title inner list-group-item-heading">{{ $data->parent_name }}</h4>
											</div>
											<!--div class="col-xs-12 col-md-6">
												<a class="btn btn-success" href="#">View Product(s)</a>
											</div-->
										</div>
									</div>
								</div>
								<div class="item col-xs-4 col-lg-4">
									<div class="thumbnail card">
										<div class="img-event">
											<img class="group list-group-image img-fluid" src="{{ asset('/storage/'.$data->category_image)}}" alt="" />
										</div>
										<div class="caption card-body">
											<h4 class="group card-title inner list-group-item-heading">{{ $data->name}}</h4>
											<p class="group inner list-group-item-text" style="max-height: 100px; width:100%; overflow: auto;" >
												{{ $data->description }}
											</p>
											<div class="row">
												<div class="col-xs-12 col-md-12">
													<a class="btn btn-success" href="{{ route('category-wise-product-listing',$data->id) }}">View Product(s)</a>
												</div>
											</div>
										</div>
									</div>
								</div>
			  
					
					<?php $previousParentId=$data->parent_id;?>
					@else
						<div class="item col-xs-4 col-lg-4">
							<div class="thumbnail card" >
								<div class="img-event">
									<img class="group list-group-image img-fluid" src="{{ asset('/storage/'.$data->category_image)}}" alt="" />
								</div>
								<div class="caption card-body">
									<h4 class="group card-title inner list-group-item-heading">{{ $data->name}}</h4>
									<p class="group inner list-group-item-text" style="max-height: 100px; width:100%; overflow: auto;" >
										{{ $data->description }}
									</p>
									<div class="row">
										<div class="col-xs-12 col-md-12">
											<a class="btn btn-success" href="{{ route('category-wise-product-listing',$data->id) }}">View Product(s)</a>
										</div>
									</div>
						
								</div>
							</div>
						</div>
			  
						
					@endif
				<?php $i++;?>
				@endforeach
				
             
              
            </div>
          </div>
        </div>
      </div>

     

    
</div>

@endsection
@section('css')
@endsection
@section('js')
@endsection



