@extends('layouts.admin')
@section('content_header')
<div class="row mb-2">
	<div class="col-sm-6">
	<h1>Banner Management</h1>
	</div>
	<div class="col-sm-6">
	<ol class="breadcrumb float-sm-right">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active">Banners</li>
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
	<div class="card">
		<div class="card-header">
			<div class="pull-left">

	        </div>
	        <div class="pull-right">

			</div>
        </div>
        <form method="post" action="{{ route('banner.store') }}" id="banner-form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <table class="table table-bordered table-sm" id="datatable">
                    <thead>
                        <tr class="text-center">
                            <th>Banners</th>
                            <th>Small Banners</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width:50%; background-color: #A9A9A9;">
                            <button type="button" class="btn btn-sm btn-info" onclick="add_banner_image()"><i class="fa fa-plus"></i>&nbsp;Banners</button>
                            @if(count($banners)>0)
                                @foreach($banners as $details)
                                <div class="row"><div class="col-lg-3"><label for="usr">Image:</label><br clear="all"><img src="{{ asset('/storage/banner/'.$details->banner_image)}}" style="height:60px; width:100px;" /></div><div class="col-lg-4"><label for="pwd">Image Text:</label><input type="text" class="form-control" id="image_text_<?php echo $details->id; ?>" value="{{ $details->banner_image_text }}" onkeyup="update_text('<?php echo $details->id; ?>')"><span style="color: green;">* Type to update text</span></div><div class="col-lg-4"><label for="pwd">Image Url:</label><input type="text" class="form-control" value="{{ $details->banner_url }}" id="image_url_<?php echo $details->id; ?>" onkeyup="update_url('<?php echo $details->id; ?>')"><span style="color: green;">* Type to update url</span></div><div class="col-lg-1"><label for="pwd">&nbsp;&nbsp;</label><br clear="all"><button type="button" class="btn btn-danger btn-sm" onclick="delete_banner_image('normal','<?php echo $details->id; ?>')"><i class="fa fa-trash"></i></button></div></div>
                                @endforeach
                                </div>
                                <br clear="all">
                            @endif
                            <div id="ImageBoxGroup"></div>
                            <br clear="all">
                            <div class="row"><div class="col-lg-5"></div><div class="col-lg-4"></div><div class="col-lg-3"><button type="button" class="btn btn-success btn-sm upload-small-banner">Upload Banner</div></div>
                        </td>
                        <td style="width:50%; background-color: #C0C0C0 ;">
                            <button type="button" class="btn btn-sm btn-info" onclick="add_small_banner_image()"><i class="fa fa-plus"></i>&nbsp;Small Banners</button>
                            @if(count($small_banners)>0)
                                @foreach($small_banners as $small_details)
                                <div class="row"><div class="col-lg-3"><label for="usr">Image:</label><br clear="all"><img src="{{ asset('/storage/banner/small/'.$small_details->banner_image)}}" style="height:60px; width:100px;" /></div><div class="col-lg-4"><label for="pwd">Image Text:</label><input type="text" class="form-control" id="image_text_<?php echo $small_details->id; ?>" value="{{ $small_details->banner_image_text }}" onkeyup="update_text('<?php echo $small_details->id; ?>')"><span style="color: green;">* Type to update text</span></div><div class="col-lg-4"><label for="pwd">Image Url:</label><input type="text" class="form-control" value="{{ $small_details->banner_url }}" id="image_url_<?php echo $small_details->id; ?>" onkeyup="update_url('<?php echo $small_details->id; ?>')"><span style="color: green;">* Type to update url</span></div><div class="col-lg-1"><label for="pwd">&nbsp;&nbsp;</label><br clear="all"><button type="button" class="btn btn-danger btn-sm" onclick="delete_banner_image('small','<?php echo $small_details->id; ?>')"><i class="fa fa-trash"></i></button></div></div>
                                @endforeach
                                </div>
                                <br clear="all">
                            @endif
                            <div id="SmallImageBoxGroup"></div>
                            <br clear="all">
                            <div class="row"><div class="col-lg-5"></div><div class="col-lg-3"></div><div class="col-lg-4"><button type="button" class="btn btn-success btn-sm upload-small-banner">Upload Small Banner</div></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>
	</div>
@endsection
@section('css')
<link href="{{ asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>
<script>

  $(function() {
    $('.upload-small-banner').click(function() {
        $("#banner-form").submit();
    })
  })


function delete_banner_image(image_type,id)
{
    if(confirm('Are you sure you want to delete the image?'))
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "{{ url('cpanel/banner/deleteimage') }}",
        data: {'image_type': image_type, 'id': id},
        success: function(data){
            alert(data.success);
            location.reload();
        }
    });
}


function update_text(id)
{
    var text = $("#image_text_"+id).val();
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "{{ url('cpanel/banner/updatetext') }}",
        data: {'text': text, 'id': id},
        success: function(data){
            $("#image_text_"+id).html(text);
        }
    });
}

function update_url(id)
{
    var url = $("#image_url_"+id).val();
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "{{ url('cpanel/banner/updateurl') }}",
        data: {'url': url, 'id': id},
        success: function(data){
            $("#image_url_"+id).html(text);
        }
    });
}



var counter = 1;
function add_banner_image()
{
    var newTextBoxDiv = $(document.createElement('span')).attr("id", 'ImageBoxDiv' + counter);
    newTextBoxDiv.append('<div class="row"><div class="col-lg-3"><label for="usr">Image:</label><input type="file" name="banner_image[]"></div><div class="col-lg-4"><label for="pwd">Image Text:</label><input class="form-control" type="text" id="banner_image_text" name="banner_image_text[]"></div><div class="col-lg-4"><label for="pwd">Image Url:</label><input class="form-control" type="text" id="banner_image_url" name="banner_image_url[]"></div><div class="col-lg-1"><label for="pwd">&nbsp;&nbsp;</label><br clear="all"><a href="javascript:void(0)"  onclick="remove_image_box()" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i class="fa fa-trash"></i></a></div></div>');
    newTextBoxDiv.appendTo("#ImageBoxGroup");
    counter++;
}
function remove_image_box()
{
    counter--;
    $("#ImageBoxDiv" + counter).remove();
    return false;
}


var counter2 = 1;
function add_small_banner_image()
{
    var newTextBoxDiv2 = $(document.createElement('span')).attr("id", 'SmallImageBoxDiv' + counter2);
    newTextBoxDiv2.append('<div class="row"><div class="col-lg-3"><label for="usr">Image:</label><input type="file" name="small_banner_image[]"></div><div class="col-lg-4"><label for="pwd">Image Text:</label><input class="form-control" type="text" id="banner_image_text" name="small_banner_image_text[]"></div><div class="col-lg-4"><label for="pwd">Image Url:</label><input class="form-control" type="text" id="banner_image_url" name="small_banner_image_url[]"></div><div class="col-lg-1"><label for="pwd">&nbsp;&nbsp;</label><br clear="all"><a href="javascript:void(0)"  onclick="remove_small_image_box()" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i class="fa fa-trash"></i></a></div></div>');
    newTextBoxDiv2.appendTo("#SmallImageBoxGroup");
    counter2++;
}
function remove_small_image_box()
{
    counter2--;
    $("#SmallImageBoxDiv" + counter2).remove();
    return false;
}


</script>
@endsection
