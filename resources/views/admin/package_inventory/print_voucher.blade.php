<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Jsd Agro | Voucher Details</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .logo { padding: 0px 0px 15px 0px; }
  </style>
</head>

<body>
    <section class="content">
      <div class="container-fluid">  
        <div class="row" style="padding: 30px 0px 0px 0px;">
        <div class="logo"><img src="{{ asset('/img/logo.jpg') }}" alt=""></div>
          <div class="col-12" style="border-top: #cccccc 2px solid;">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col"> 
                    <strong>Store Address</strong>
                    <address style="margin-bottom:0px; line-height: 23px;">
                        Jsd Agro
                    </address> 
                    <strong>GSTIN:</strong> 19AAOFJ4535D1Z8<br/>
                    <strong>Phone:</strong> +91 8617771271<br/>
                    <strong>Email:</strong> info@jsdagro.com
                </div>

                <div class="col-sm-4 invoice-col"> 
                    <strong>Voucher No:</strong> {{ 'JSD-VOU-'.$voucher_no }}<br/>
                    <strong>Date:</strong> {{ date('j F Y',strtotime($voucher_date)) }}<br/>
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <strong>Shipping Address</strong>
                    <address style="margin-bottom:0px;">
                    <?php 
                        if(!empty($swadesh_hut_address))
                        {
                        if($swadesh_hut_address!='')
                        {
                            if( strpos($swadesh_hut_address, ',') !== false ) 
                            {
                                $seller_address_component = explode(',',$swadesh_hut_address);
                                foreach($seller_address_component as $components)
                                {
                                echo $components.'<br/>';
                                }
                            }
                            else 
                            {
                            echo $swadesh_hut_address;
                            }
                        }
                        else 
                        {
                            echo 'N/A';
                        }
                        }
                        else 
                        {
                        echo 'N/A';
                        }
                    ?>
                    </address>
                </div>
            </div>




              <div class="row" style="padding-top: 20px;">
                <div class="col-12 table-responsive">

                {!! $tbl_html !!}
               
                </div>
                <!-- /.col -->
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script>
  $( document ).ready(function() {
    window.print();
  });
</script>
</body>
</html>

