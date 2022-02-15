<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Jsd Agro | Invoice</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="{{ asset("/plugins/fontawesome-free/css/all.min.css") }}">

  <!-- Ionicons -->

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="{{ asset("/css/adminlte.min.css") }}">

  <!-- Google Font: Source Sans Pro -->

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>

<body>





    <section class="content">

      <div class="container-fluid">

          @php $address_component = $data[0]; @endphp

            <?php 

            $get_swadesh_hut_details = Helper::get_swadesh_hut_details($address_component->swadesh_hut_id);

            if($address_component->swadesh_hut_id==3)
            {
              $s_ph_no = '8100010453';
            }
            else if($address_component->swadesh_hut_id==4)
            {
              $s_ph_no = '8617771271';
            }
            else 
            {
              $s_ph_no = '8100010453';
            }

            ?>

        <div class="row" style="padding: 30px 0px 0px 0px;">

          <div class="col-6">

            <img src="https://jsdagro.com/public/front-assets/images/print-logo.png" style="margin-left: 15px;">

          </div> 

          <div class="col-6" style="text-align: right">

              <p><strong>Invoice: {{ $address_component->order_number }}</strong><br/><strong>Date:</strong> {{ date('F j, Y',strtotime($address_component->order_date)) }}</p>

          </div>

          <div class="col-12" style="border-top: #cccccc 2px solid;">



            

            

            <!-- Main content -->

            <div class="invoice p-3 mb-3">

             

              <!-- info row -->





              <div class="row">

                <div class="col-12">

                  

                </div>

                

              </div>



              



              <div class="row invoice-info">

                  

                  

                 <div class="col-sm-4 invoice-col"> 

                 <strong>Store Address</strong>

                  <address style="margin-bottom:0px; line-height: 23px;">

                    <?php 

                      if(!empty($get_swadesh_hut_details))

                      {

                        if($get_swadesh_hut_details->address!='')

                        {

                          if( strpos($get_swadesh_hut_details->address, ',') !== false ) 

                          {

                              $seller_address_component = explode(',',$get_swadesh_hut_details->address);

                              foreach($seller_address_component as $components)

                              {

                                echo $components.'<br/>';

                              }

                          }

                          else 

                          {

                            echo $get_swadesh_hut_details->address;

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

                  <strong>GSTIN:</strong> 19AAOFJ4535D1Z8<br/>

                  <strong>Phone:</strong> +91 <?php echo $s_ph_no; ?><br/>

                  <strong>Email:</strong> info@jsdagro.com

                 </div>

                <!-- /.col -->

                <div class="col-sm-4 invoice-col">

                    <strong>Billing Address</strong>

                  <address style="margin-bottom:0px;">

                    {{ $address_component->billing_name }}<br>

                    {{ $address_component->billing_street }}<br>

                    {{ $address_component->billing_city }}, {{ $address_component->billing_state }} {{ $address_component->billing_pincode }}<br>

                    <strong>Phone:</strong> {{ $address_component->billing_phone }}

                  </address>

                  <strong>Country of Supply:</strong> India<br/>

                  <strong>Payment Mode:</strong> <!-- Cash on Delivery --> {{ $address_component->payment_mode }}

                </div>

                <!-- /.col -->
                

                <div class="col-sm-4 invoice-col">

                  <strong>Shipping Address</strong>

                  <address style="margin-bottom:0px;">

                    {{ $address_component->billing_name }}<br>

                    {{ $address_component->shipping_address }}<br>

                    {{ $address_component->billing_city }}, {{ $address_component->state }} {{ $address_component->pin_code }}<br>

                    <strong>Phone:</strong> {{ $address_component->shipping_phone }}

                  </address>

                </div>

                <!--<div class="col-sm-4 invoice-col">

                  <b>Invoice : #{{ $address_component->order_number }}</b><br>

                  <b>Payment :</b> COD<br>

                </div>-->

                <!-- /.col -->

              </div>

              <!-- /.row -->

              

              <!-- Table row -->

              <div class="row" style="padding-top: 20px;">

                <div class="col-12 table-responsive">

                  <table class="table table-striped">

                    <thead>

                    <tr>

                      <th>Image</th>

                      <th>Product</th>

                      <th>HSN #</th>

                      <th>Qty</th>

                      <th>Weight</th>

                      <th>Subtotal</th>

                      <th>Tax Type</th>

                      <!--<th>Tax Rate</th>-->

                      <th>Tax Amount</th>

                      <th>Total Amount</th>

                    </tr>

                    </thead>

                    <tbody>

                    
                    @php

                    $subtotal = 0;

                    @endphp

                    @if(isset($data) && count($data)>0)

                    @foreach($data as $details)

                    @php

                    $get_product_details = DB::table('products')->where('id',$details->product_id)->first();

                    //print_r($get_product_details);exit;

                    $subtotal+=$details->item_total;





                    $unitp = (100*$details->item_total)/(100+$get_product_details->cgst+$get_product_details->sgst);

                    $unitp = number_format($unitp,2);



                    
                    







                    @endphp
                    
                    <tr>

                      <td><img src="<?php echo url('/') ?>/public/storage/<?php echo $details->product_image; ?>" height="50px" width="50px"></td>

                      <td>{{ $details->title }}</td>

                      <td>{{ $get_product_details->hsn }}</td>

                      <td><?php echo '<strong>'.$details->quantity; ?></td>

                      <td><?php echo '<strong>'.substr($get_product_details->weight_per_pkt, 0, strpos($get_product_details->weight_per_pkt, ".")).' '.$get_product_details->weight_unit; ?></td>

                      <td>र{{ $unitp }}</td>

                      <td>GST</td>

                      <td>र{{ (float)$details->item_total  -  (float)(str_replace(',','', $unitp)) }}</td> 

                      <td>र {{ number_format(($details->item_total), 2) }}</td>

                    </tr>
                    
                    @endforeach

                    @endif

                    

                    <tr>

                        <td colspan="8"><strong>Total:</strong> <?php

                          $amount_after_decimal = round($subtotal - ($num = floor($subtotal)), 2) * 100;

                          // Check if there is any number after decimal

                          $amt_hundred = null;

                          $count_length = strlen($num);

                          $x = 0;

                          $string = array();

                          $change_words = array(0 => '', 1 => 'One', 2 => 'Two',

                            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',

                            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',

                            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',

                            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',

                            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',

                            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',

                            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',

                            70 => 'Seventy',  75 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');

                            $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');

                            while( $x < $count_length ) {

                              $get_divider = ($x == 2) ? 10 : 100;

                              $amount = floor($num % $get_divider);

                              $num = floor($num / $get_divider);

                              $x += $get_divider == 10 ? 1 : 2;

                              if ($amount) {

                              $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;

                              $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;

                              $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 

                              '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 

                              '.$here_digits[$counter].$add_plural.' '.$amt_hundred;

                                }

                          else $string[] = null;

                          }

                          $implode_to_Rupees = implode('', array_reverse($string));

                          // $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 

                          // " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';

                          $get_paise = ($amount_after_decimal > 0) ? "" . ($change_words[$amount_after_decimal] . " 

                          " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';

                          echo '<strong>'.($implode_to_Rupees ? $implode_to_Rupees . 'Rupees And ' : '') . $get_paise.'</strong>'; 

                          ?> <strong>only</strong></td>

                          <td>र <strong>{{ number_format($subtotal,2) }}</strong></td>

                        

                    </tr>

                    </tbody>

                  </table>

                </div>

                <!-- /.col -->

              </div>

              <!-- /.row -->



              

              <!-- /.row -->



              <!-- this row will not appear when printing -->

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

    /*var pdf = new jsPDF('p', 'pt', 'letter');

    pdf.canvas.height = 72 * 11;

    pdf.canvas.width = 72 * 8.5;

    pdf.fromHTML(document.body);

    pdf.save('jsdinvoice.pdf');*/

  });

</script>

</body>

</html>

