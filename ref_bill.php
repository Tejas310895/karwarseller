<?php 

include("includes/db.php");

if(isset($_GET['print'])){
  $invoice_no = $_GET['print'];
  date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");

$get_total = "SELECT sum(due_amount) AS total FROM customer_orders where invoice_no='$invoice_no' and product_status='Deliver'";
$run_total = mysqli_query($con,$get_total);
$row_total = mysqli_fetch_array($run_total);

$total = $row_total['total'];

$get_details = "select * from customer_orders where invoice_no='$invoice_no'";
$run_details = mysqli_query($con,$get_details);
$row_details = mysqli_fetch_array($run_details);

$customer_id = $row_details['customer_id'];
$add_id = $row_details['add_id'];
$order_date = $row_details['order_date'];
$client_id = $row_details['client_id'];

$get_customer = "select * from customers where customer_id='$customer_id'";
$run_customer = mysqli_query($con,$get_customer);
$row_customer = mysqli_fetch_array($run_customer);

$customer_name = $row_customer['customer_name'];
$customer_contact = $row_customer['customer_contact'];

$get_add = "select * from customer_address where add_id='$add_id'";
$run_add = mysqli_query($con,$get_add);
$row_add = mysqli_fetch_array($run_add);

$customer_city = $row_add['customer_city'];
$customer_landmark = $row_add['customer_landmark'];
$customer_phase = $row_add['customer_phase'];
$customer_address = $row_add['customer_address'];

$get_min = "select * from admins";
$run_min = mysqli_query($con,$get_min);
$row_min = mysqli_fetch_array($run_min);
$min_price = $row_min['min_order'];
// $del_charges = $row_min['del_charges'];
        
$get_del_charges = "select * from order_charges where invoice_id='$invoice_no'";
$run_del_charges = mysqli_query($con,$get_del_charges);
$row_del_charges = mysqli_fetch_array($run_del_charges);

$del_charges = $row_del_charges['del_charges'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Mono' rel='stylesheet'>
    <style>
            body{
                font-family:Roboto Mono;
            }
            #invoice-POS h1 {
            font-size: 1.5em;
            color: #222;
            }
            #invoice-POS h2 {
            font-size: 0.8rem;
            margin-top: 5px;
            margin-bottom: 5px;
            text-align: left;
            }
            #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
            }
            #invoice-POS p {
            font-size: 0.6em;
            color: #000;
            line-height: 1em;
            }
            #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
            }
            #invoice-POS #top {
            min-height: 100px;
            }
            #invoice-POS #mid {
            min-height: 80px;
            }
            #invoice-POS #bot {
            min-height: 50px;
            }
            /* #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background: url(../admin_area/admin_images/karlogob.png) no-repeat;
            background-size: 60px 60px;
            } */
            #invoice-POS .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(../admin_area/admin_images/karlogob.png) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
            }
            #invoice-POS .info {
            display: block;
            margin-left: 0;
            text-align:center;
            font-weight:bold;
            font-size: 1rem;
            }
            #invoice-POS .title {
            float: right;
            }
            #invoice-POS .title p {
            text-align: right;
            }
            #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
            }
            #invoice-POS .tabletitle {
              border-bottom: 1px solid #000;
            font-size: 1rem;
            /* background: #EEE; */
            }
            #invoice-POS .service {
            border-bottom: 1px solid #000;
            font-size: 1rem;
            color:#000;
            }
            #invoice-POS .item {
            width: 24mm;
            }
            #invoice-POS .itemtext {
            /* font-size: 0.5 rem; */
            margin-top: 7px;
            margin-bottom: 7px;
            }
            #invoice-POS #legalcopy {
            margin-top: 0;
            }
            #legal{
                font-size:1rem !important;
            }

            #item_type{
              text-align:left !important;
            }
    </style>
    <script>
        window.onload = function () {
            window.print();
        }

        window.onafterprint = function(){
            window.close();
        }
    </script>
</head>
<body>
<div class="pagebreak mt-1 ml-1">
  <div id="invoice-POS">
  <!-- <center>
  <img src="images/karwarslogo.png" alt="" width="80px" style="margin-top:10px;">
  </center> -->
    <!-- <center id="top">
      <div class="logo">
      </div>
    </center> -->
    <div id="mid">
      <div class="info">
        <h2 style="text-align:center;">Order KOT</h2>
        <h2>Order id : <?php echo $invoice_no; ?> </br>
            Date : <?php echo date('d/M/Y h:i a',strtotime($order_date)); ?> </br>
            Name : <?php echo $customer_name; ?></br>
            Phone   : <?php echo $customer_contact; ?></br>
            <!-- Address : <?php //echo $customer_address.",".$customer_phase.",".$customer_landmark.",".$customer_city; ?></br> -->
        </h2>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

					<div id="table">
						<table>
							<tr class="tabletitle">
                <th class="item"><h2 class="my-0">Rack</h2></th>
                <th class="item"><h2 class="my-0">Shelf</h2></th>
								<th class="item"><h2 class="my-0">Item</h2></th>
								<th class="Hours" style="width:5%;"><h2 class="my-0" style="text-align: left;">Qty</h2></th>
								<th class="Rate"><h2 class="my-0" style="text-align: right;">Total</h2></th>
							</tr>
      <?php 

        $get_client_id = "SELECT distinct(client_id) from customer_orders where invoice_no='$invoice_no'";
        $run_client_id = mysqli_query($con,$get_client_id);
        $taxable_value = array();
        $total_tax = array();
        $total_qty = array();
        $you_saved = 0;
        while($row_client_id=mysqli_fetch_array($run_client_id)){

            $client_id = $row_client_id['client_id'];

            $get_product_type = "select * from clients where client_id='$client_id'";
            $run_product_type = mysqli_query($con,$get_product_type);
            $row_product_type = mysqli_fetch_array($run_product_type);

            $product_type = $row_product_type['client_pro_type'];

            $get_client_sum = "SELECT sum(due_amount) as client_total from customer_orders where invoice_no='$invoice_no' and product_status='Deliver' and client_id='$client_id'";
            $run_client_sum = mysqli_query($con,$get_client_sum);
            $row_client_sum = mysqli_fetch_array($run_client_sum);

            $client_total = $row_client_sum['client_total'];
            
            echo"
            <tr>
            <th colspan='4' class='item_type' style='font-size:0.6rem;text-align:left;padding:10px 10px 0px 10px;text-transform: uppercase;background-color:#F0F0F0;'>$product_type</th>
            <th class='item_type' style='font-size:0.6rem;text-align:right;padding:10px 10px 0px 10px;text-transform: uppercase;background-color:#F0F0F0;'>$client_total</th>
            </tr>
            ";
       ?>
        <tbody class="text-center" style="font-weight:bold;">
      <?php
				$get_pro_id = "select * from customer_orders where invoice_no='$invoice_no' and client_id='$client_id'";

                $run_pro_id = mysqli_query($con,$get_pro_id);
                

				$counter = 0;

				while($row_pro_id = mysqli_fetch_array($run_pro_id)){
					
				$pro_id = $row_pro_id['pro_id'];

				$qty = $row_pro_id['qty'];

        array_push($total_qty,$qty);

				$product_status = $row_pro_id['product_status'];

				$sub_total = $row_pro_id['due_amount'];

				$pro_price = $sub_total/$qty;

				$get_pro = "select * from products where product_id='$pro_id'";

				$run_pro = mysqli_query($con,$get_pro);

				while($row_pro = mysqli_fetch_array($run_pro)){

					// $total =0;

					$pro_title = $row_pro['product_title'];

					$pro_desc = $row_pro['product_desc'];

          $rack_no = $row_pro['rack_no'];

          $shelf_no = $row_pro['shelf_no'];

					// $pro_price = $row_pro['product_price'];

					$mrp = $row_pro['price_display'];

          $product_gst_rate = $row_pro['product_gst_rate'];

          $tax = round($sub_total*($product_gst_rate/100),2);

          $taxable = round($sub_total-($sub_total*($product_gst_rate/100)),2);

          array_push($taxable_value,$taxable);
          array_push($total_tax,$tax);
					if($mrp<$pro_price){

						$discount=0;

					}else{

						$discount=($mrp-$pro_price)*$qty;
					} 

					//$sub_total = $row_pro['product_price']*$qty;
					
					//$total += $sub_total;

                    $counter = ++$counter;
                    $you_saved += $discount;

					if($product_status==='Deliver'){

            echo "            
              <tr class='service'>
                <td class='tableitem'><p class='itemtext'>$rack_no</p></td>
                <td class='tableitem'><p class='itemtext'>$shelf_no</p></td>
								<td class='tableitem'><p class='itemtext'>$pro_title $pro_desc</p></td>
								<td class='tableitem' style='padding-left: 5px;'><p class='itemtext' style='text-align: left;'>$qty</p></td>
                <td class='tableitem'><p class='itemtext' style='text-align: right;'>$sub_total.00</p></td>
							</tr>
            
            ";

					}else {

						echo "
              <tr class='service'>
                <td class='tableitem'><p class='itemtext'>$rack_no</p></td>
                <td class='tableitem'><p class='itemtext'>$shelf_no</p></td>
								<td class='tableitem'><p class='itemtext'>$pro_title $pro_desc</p></td>
								<td class='tableitem'><p class='itemtext' style='text-align: center;'>$qty</p></td>
								<td class='tableitem'><p class='itemtext'>Cancelled</p></td>
							</tr>
						";	

					}

					}

                }
			?>
      <?php } ?>
            <?php 
                
                $get_discount = "select * from customer_discounts where invoice_no='$invoice_no'";
                $run_discount = mysqli_query($con,$get_discount);
                $row_discount = mysqli_fetch_array($run_discount);

                $coupon_code = $row_discount['coupon_code'];
                $discount_type = $row_discount['discount_type'];
                $discount_amount = $row_discount['discount_amount'];

                if($discount_type==='amount'){
                    $grand_total = ($total+$del_charges)-$discount_amount;
                }elseif (empty($discount_type)) {
                    $grand_total = $total+$del_charges;
                }elseif ($discount_type==='product') {
                    $get_promo_pro = "select * from products where product_id='$discount_amount'";
                    $run_promo_pro = mysqli_query($con,$get_promo_pro);
                    $row_promo_pro = mysqli_fetch_array($run_promo_pro);
    
                    $promo_pro_title = $row_promo_pro['product_title'];
                    $promo_pro_desc = $row_promo_pro['product_desc'];
                    $promo_pro_price = $row_promo_pro['product_price']; 
                    $grand_total = ($total+$del_charges)+$promo_pro_price;
            ?>

            <tr>
                <th colspan='4' class='item_type' style='font-size:0.6rem;text-align:left;padding:10px 10px 0px 10px;text-transform: uppercase;background-color:#F0F0F0;'>Promo Products</th>
            </tr>
            <tr>
                <td class='tableitem' colspan="4"><p class='itemtext'><?php echo $promo_pro_title." ".$promo_pro_desc;?></p></td>
								<td class='tableitem'><p class='itemtext'><?php echo $promo_pro_price;?></p></td>
            </tr>
            <?php } ?>
              <!-- <tr class="tabletitle">
								<td class="Rate mb-0" colspan="4"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;">Item Count :</h2></td>
								<td class="payment"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;"><?php echo array_sum($total_qty); ?></h2></td>
							</tr>
							<tr class="tabletitle">
								<td class="Rate" colspan="4"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;">Item Taxable Value : ₹</h2></td>
								<td class="payment"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;"><?php echo number_format(round(array_sum($taxable_value),2),2); ?></h2></td>
							</tr>
              <tr class="tabletitle">
								<td class="Rate" colspan="4"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;">Total Tax : ₹</h2></td>
								<td class="payment"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;"><?php echo number_format(round(array_sum($total_tax),2),2); ?></h2></td>
							</tr>
              <?php //if($del_charges>0){?>
                <tr class="tabletitle">
                  <td class="Rate" colspan="4"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;">Delivery Charges : ₹</h2></td>
                  <td class="payment"><h2 style="text-align:right;margin-bottom:0;margin-top:0;font-size:0.7rem;"><?php echo number_format($del_charges,2); ?></h2></td>
                </tr> -->
              <?php //} ?>
              <!-- <?php //if(!empty($coupon_code)){?>
              <tr class="tabletitle">
              <?php //if($discount_type==='amount'){ ?>
              <td class="text-right" colspan="5" style="text-align:center;"><h2 style="text-align:center;font-size:0.7rem;">Promo Applied (<?php echo strtoupper($coupon_code); ?>)<h2></td>
              </tr>
              <tr class="tabletitle">
              <td colspan="4" style="text-align:right;font-size:0.7rem;">Discount (<?php echo strtoupper($coupon_code); ?>):₹ </td>
              <td><h2 style="text-align:right;font-size:0.7rem;">-<?php echo $discount_amount; ?></h2></td>
              <?php //}else{ ?>
              <td class="text-left" colspan="5" style="text-align:left;">
                <h2 style="text-align:right;font-size:0.7rem;">
                    Promo Applied (<?php echo strtoupper($coupon_code); ?>).
                <h2>
              </td>
              </tr>
              <?php //} ?>
              <?php //} ?> -->
              <tr class="tabletitle">
                <td class="Rate" colspan="2"><h2 style="text-align:left;margin-bottom:0;">Total :</h2></td>
                <td class="payment"><h2 style="text-align:right;margin-bottom:0;"><?php echo number_format($grand_total,2); ?></h2></td>
              </tr>

						</table>
					</div><!--End Table-->
                <!-- <h3 style="margin-top: 0;margin-bottom:0;text-align:right;font-weight:bold;font-size:0.7rem;">You Save :₹ <?php //echo $you_saved; ?></h3> -->
					<!-- <div id="legalcopy">
						<p class="legal" style="text-align: center;font-weight:bold;font-size:1rem;margin-top:0;margin-bottom:0;"><strong>Thank You!</strong> <br> Order Again www.karwars.in.
						</p>
            <center>
            <img src="images/gplay.png" alt="" width="100px">
            </center>
					</div> -->

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
  </div>
</body>
</html>
<?php } ?>