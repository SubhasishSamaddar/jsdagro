@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Demo</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Demo</li>
	</ol>
	</div>
</div> 
@endsection 
@section('content')
	@if ($message = Session::get('success'))
	<div class="alert alert-success">
		<ul class="margin-bottom-none padding-left-lg">
			<li>{{ $message }}</li>
		</ul>
	</div>
	@endif
	@if ($message = Session::get('error'))
	<div class="alert alert-danger">
		<ul class="margin-bottom-none padding-left-lg">
			<li>{{ $message }} </li>
		</ul>
	</div>
	@endif
    <div class="card-body">
        <div class="controls">
            <button class="js-seralize2">Build from serialize</button>
        </div><br/>
        <textarea id="log2" rows="4" cols="80">[{"col":1,"row":5,"size_x":2,"size_y":2},{"col":3,"row":1,"size_x":1,"size_y":2},{"col":4,"row":1,"size_x":1,"size_y":1},{"col":1,"row":3,"size_x":1,"size_y":1},{"col":2,"row":3,"size_x":3,"size_y":1},{"col":1,"row":4,"size_x":1,"size_y":1},{"col":2,"row":4,"size_x":1,"size_y":1},{"col":1,"row":1,"size_x":1,"size_y":1}]
        </textarea><br/>
        <div class="gridster">
            <ul >
                
            </ul>
        </div>
        <div class="controls">
            <button class="js-seralize">Serialize</button>
        </div>
        <br/>

        <textarea id="log" rows="4" cols="80" ></textarea>
    </div>
	
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/demo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/dist/jquery.gridster.min.css') }}">
@endsection 
@section('js') 
<script src="{{ asset('/dist/jquery.gridster.js') }}" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    var gridster;
    // same object than generated with gridster.serialize() method
    var serialization = [{"col":1,"row":5,"size_x":2,"size_y":2},{"col":3,"row":1,"size_x":1,"size_y":2},{"col":4,"row":1,"size_x":1,"size_y":1},{"col":1,"row":3,"size_x":1,"size_y":1},{"col":2,"row":3,"size_x":3,"size_y":1},{"col":1,"row":4,"size_x":1,"size_y":1},{"col":2,"row":4,"size_x":1,"size_y":1},{"col":1,"row":1,"size_x":1,"size_y":1}];
    // sort serialization
    serialization = Gridster.sort_by_row_and_col_asc(serialization);

    $(function () {

        var log = document.getElementById('log');

        gridster = $(".gridster ul").gridster({
            widget_base_dimensions: [100, 55],
            widget_margins: [5, 5],
            autogrow_cols: true,
            resize: {
                enabled: true
            }
        }).data('gridster');

        $('.js-seralize2').on('click', function () {
            gridster.remove_all_widgets();
            $.each(serialization, function () {
                gridster.add_widget('<li />', this.size_x, this.size_y, this.col, this.row);
            });
        });


        $('.js-seralize').on('click', function () {
            var s = gridster.serialize();

            $('#log').val(JSON.stringify(s));
        })
    });
</script>
@endsection