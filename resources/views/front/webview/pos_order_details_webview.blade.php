<style>
    #invoice-POS{
      margin: 0 auto;
      width: 100%;
      background: #FFF;
      font-family: Arial;
      font-size: 16px;
      }
    .info p{
      font-size: 12px;
      color: #000;
      margin:0px;
      padding: 0px;
    }
     
    .logo { padding: 0px 0px 15px 0px; }
    #top, #mid,#bot{ /* Targets all id with 'col-' */
      border-bottom: 1px solid #EEE;
    }
    
    
    #mid{padding: 15px 0px 15px 0px; text-align: center;} 
    
    .info{
      display: block;
      //float:left;
      margin-left: 0;
    }
    .title{
      float: right;
    }
    .title p{text-align: right;} 
    table{
      width: 100%;
      border-collapse: collapse;
    }
    td{
      padding: 5px 0 5px 5px;
      //border: 1px solid #EEE
    }
    .tabletitle{
      //padding: 5px;
      font-size: 12px;
      background: #EEE;
    }
    .service{border-bottom: 1px solid #EEE;}
    .itemtext{font-size: 12px;}
    
    #legalcopy{
      margin-top: 5mm;
      text-align: center;
    }
    </style>
      <div id="invoice-POS">
        
        <center id="top">
          <div class="logo"><img src="{{ asset('/img/logo.jpg') }}" alt="" style="max-width:90%"></div>
          
        </center><!--End InvoiceTop-->
        
        
        <div id="mid">
          <div class="info">
          <?php 
            $address_component = $data[0];
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


            if(!empty($get_swadesh_hut_details))
            {
                if($get_swadesh_hut_details->address!='')
                {
                    if( strpos($get_swadesh_hut_details->address, ',') !== false ) 
                    {
                        $seller_address_component = explode(',',$get_swadesh_hut_details->address);
                        echo '<p>';
                        foreach($seller_address_component as $components)
                        {
                        echo $components.', ';
                        }
                        echo '</p>';
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
            <p>Phone: <?php echo $s_ph_no; ?></p>
            <p>Email: info@jsdagro.com</p>
            <p>GSTIN: 19AAOFJ4535D1Z8</p>
            <p>&nbsp;</p>
            <p><strong>Bill To:</strong></p>
            <p>{{ $address_component->order_number }}</p>
            <p>{{ $address_component->billing_name }}</p>
            <p>{{ $address_component->billing_phone }}</p>
            <p>{{ date('F j, Y',strtotime($address_component->order_date)) }}</p>
            
          </div>
        </div><!--End Invoice Mid-->
        
        <div id="bot">
    
                        <div id="table">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tr class="tabletitle">
                                    <td class="item"><strong>Item</strong></td>
                                    <td class="Hours"><strong>Qty</strong></td>
                                    <td class="Rate"><strong>Sub Total</strong></td>
                                </tr>
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

                                $pos_order_discount = $details->pos_order_discount;

                                @endphp
                                <tr class="service">
                                    <td class="tableitem"><p class="itemtext">{{ $details->title }} (<?php echo substr($get_product_details->weight_per_pkt, 0, strpos($get_product_details->weight_per_pkt, ".")).' '.$get_product_details->weight_unit; ?>)</p></td>
                                    <td class="tableitem"><p class="itemtext">{{ $details->quantity }}</p></td>
                                    <td class="tableitem"><p class="itemtext" style="word-wrap: none;">र{{ number_format(($details->item_total), 2) }}</p></td>
                                </tr>
                                @endforeach
                                @endif
                                
                                @php 
                                if($pos_order_discount>0)
                                {
                                @endphp
                                <tr class="tabletitle">
                                    <td></td>
                                    <td class="Rate">&nbsp;&nbsp;&nbsp;</td>
                                    <td class="payment"><strong>र{{ number_format($subtotal,2) }}</strong></td>
                                </tr>
                                <tr class="tabletitle">
                                    <td></td>
                                    <td class="Rate"><strong>Discount</strong></td>
                                    <td class="payment"><strong>र &nbsp;{{ number_format($pos_order_discount,2) }}</strong></td>
                                </tr>
                                <tr class="tabletitle">
                                    <td></td>
                                    <td class="Rate"><strong>Total</strong></td>
                                    <td class="payment"><strong>र{{ number_format(($subtotal-$pos_order_discount),2) }}</strong></td>
                                </tr>
                                @php
                                }
                                else 
                                {
                                @endphp
                                <tr class="tabletitle">
                                    <td></td>
                                    <td class="Rate"><strong>Total</strong></td>
                                    <td class="payment"><strong>र{{ number_format($subtotal,2) }}</strong></td>
                                </tr>
                                @php 
                                }
                                @endphp
    
                            </table>
                        </div><!--End Table-->
    
                        <div id="legalcopy">
                            <p><strong>Thank you for your business!</strong></p>
                        </div>
    
                    </div><!--End InvoiceBot-->
      </div><!--End Invoice-->
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