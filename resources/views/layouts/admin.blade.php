<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="icon" href="{{ asset('/images/fab.png') }}" sizes="32x32" />
	<link rel="icon" href="{{ asset('/images/fab.png') }}" sizes="192x192" />
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <!-- Theme style -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ url('/').asset('/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('/').asset('/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ url('/').asset('/plugins/datatable/dataTables.bootstrap4.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
  <!-- Plugin CSS -->
  <link type="text/css" href="{{ url('/').asset('/css/OverlayScrollbars.min.css') }}" rel="stylesheet"/>


  @yield('css')

</head>
<body class="sidebar-mini layout-fixed  text-sm">
<div class="wrapper">
  <!-- Header -->

  <!-- Navbar -->
  @include('includes.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('includes.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('content_header')
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          @yield('content')
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  @include('includes.right_sidebar')
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('includes.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<!-- Plugin JS -->
<script type="text/javascript" src="{{ asset('/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('/').asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/js/adminlte.min.js') }}"></script>

<script type="text/javascript" src="{{ url('/').asset('/plugins/datatable/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ url('/').asset('/plugins/datatable/dataTables.bootstrap4.js') }}"></script>
@yield('js')

<script>

$(document).ready(function() {
  $('#datatable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });

/*
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
*/
    // setInputFilter(document.getElementById("ordered_qty"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("available_qty"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("cgst"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("sgst"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("igst"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("weight_per_pkt"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });
    // setInputFilter(document.getElementById("price"), function(value) {
    // return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    // });


} );

</script>

</body>
</html>
