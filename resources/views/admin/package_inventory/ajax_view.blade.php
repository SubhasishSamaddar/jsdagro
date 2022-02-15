<input type="text" style="width:220px;float:right;" class="form-control form-control-sm" name="seatch" placeholder="Search..." id="search"><br clear="all">
			<table class="table table-bordered table-sm" id="datatable1">
				<thead>
					<tr class="text-center">
						<th>Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>MRP(INR)</th>
                        <th>Selling Price</th>
                        <th>Image</th>
                        <th>Weight/Pkt</th>
                        <th style="display:none;">Barcode</th>
                        <th>Quantity</th>
                        <th>Inventory Out</th>
						<th>Created At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="data_container">
				@foreach ($records as $key => $inventory)
				<tr>
                    <td>{{ $inventory->prod_name }}</td>
                    <td>
                        @php 
                            $get_category_details = DB::table('categories')->where('id',$inventory->category_id)->first();
                            if(isset($get_category_details->name) && $get_category_details->name!='')
                            {
                                echo $get_category_details->name;
                            }
                            else 
                            {
                                echo 'N/A';
                            }
                        @endphp
                    </td>
                    <td>
                        @php 
                            $get_location_details = DB::table('package_locations')->where('id',$inventory->package_location_id)->first();
                            if(isset($get_location_details->location_name) && $get_location_details->location_name!='')
                            {
                                echo $get_location_details->location_name;
                            }
                            else 
                            {
                                echo 'N/A';
                            }
                        @endphp
                    </td>
                    <td>{{ $inventory->mrp }}</td>
                    <td>{{ $inventory->selling_price }}</td>
                    <td><img src="{{ asset('/storage/'.$inventory->product_image)}}" style="height:60px;" /></td>
                    <td>{{ $inventory->weight_per_packet }} {{ $inventory->weight_unit }}</td>
                    <td style="display:none;">{{ $inventory->barcode }}</td>
                    <td class="text-center">
                        @php
                        $total_out_qty=0;
                        $get_package_inventory_out_details = Helper::get_package_inventory_out_details($inventory->id);
                        if(count($get_package_inventory_out_details)>0)
                        {
                            foreach($get_package_inventory_out_details as $pidetails)
                            {
                                $total_out_qty+=$pidetails->inv_out_qty;
                            }
                        }
                        $available_qty = $inventory->available_qty;
                        @endphp
                        <table>
                            <tr><td><strong>Out Quantity</strong></td><td><strong>Available Quantity</strong></td></tr>
                            <tr><td id="tbl_out_qty{{ $inventory->id }}">{{$total_out_qty}}</td><td id="tbl_avl_qty{{ $inventory->id }}"><input type="hidden" id="pavlqty{{ $inventory->id }}" value="{{$available_qty}}">{{$available_qty}}</td></tr>
                        </table>
                    </td>
                    <td class="text-center"><!--<input data-id="{{$inventory->id}}" class="toggle-class  btn-sm" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="In" data-off="Out" {{ ($inventory->inventory_in_out=="In")? "checked" : "" }} data-size="xs">-->
                    <a href="javascript:void(0)" id="inv_in_out_button" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning" onclick="set_package_inventory('{{ $inventory->id }}','{{ $inventory->package_location_id }}','{{ $inventory->weight_unit }}','{{ $inventory->weight_per_packet }}')">Set Inventory Out</a>

                    <button class="btn btn-xs btn-info"  data-toggle="modal" data-target="#increaseStockModal" onclick="increase_stock('{{ $inventory->id }}','{{ $inventory->package_location_id }}','{{ $inventory->weight_unit }}','{{ $inventory->weight_per_packet }}')">Increase Stock</button>
                    </td>
					<td class="text-center" data-sort="{{ date('d-m-Y',strtotime($inventory->created_at)) }}">{{ date('d-m-Y',strtotime($inventory->created_at)) }}</td>
					<td class="text-center">
                        @can('package_inventory-edit')
                        <a class="btn btn-primary" href="{{ route('package_inventory.edit',$inventory->id) }}"  title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('package_inventory-delete')
						<form method="post" action="{{ route('package_inventory.destroy',$inventory->id) }}" style='display:inline' >
        				@csrf
                  		@method('DELETE')
						<button type="submit"  onclick="return confirm('Are you sure to Delete the Inventory ?');" class="btn btn-danger"  title="Delete" ><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
            <div class="pagination" style="float:right;">{{ $records->links() }}</div>