<?php 
session_start();
include("includes/db.php");

if(!isset($_SESSION['client_email'])){

  echo "<script>window.open('login.php','_self')</script>";

}else{

  $seller_email = $_SESSION['client_email'];
  $get_client_id = "select * from clients where client_email='$seller_email'";
  $run_client_id = mysqli_query($con,$get_client_id);
  $row_client_id = mysqli_fetch_array($run_client_id);
  $client_id = $row_client_id['client_id'];

if(isset($_GET['update_order'])){

  date_default_timezone_set('Asia/Kolkata');
  $today = date("Y-m-d H:i:s");

  $update_order = $_GET['update_order'];

  $status = $_GET['status'];

  $update_status_del = "UPDATE customer_orders SET order_status='$status',del_date='$today' WHERE invoice_no='$update_order' and client_id='$client_id'";

  $run_status_del = mysqli_query($con,$update_status_del);

  if($client_id==6){

  $order_id = $update_order;

  date_default_timezone_set('Asia/Kolkata');
  $today = date("Y-m-d H:i:s");

  $get_order_details = "select * from customer_orders where invoice_no='$order_id'";
  $run_order_details = mysqli_query($con,$get_order_details);
  $row_order_details = mysqli_fetch_array($run_order_details);

  $customer_id = $row_order_details['customer_id'];
  
  $get_customer = "select * from customers where customer_id='$customer_id'";

  $run_customer = mysqli_query($con,$get_customer);

  $row_customer = mysqli_fetch_array($run_customer);

  $c_name = $row_customer['customer_name'];

  $c_email = $row_customer['customer_email'];

  $c_contact = $row_customer['customer_contact'];

  $get_total = "SELECT sum(due_amount) AS total FROM customer_orders WHERE invoice_no='$order_id' and product_status='Deliver'";

  $run_total = mysqli_query($con,$get_total);

  $row_total = mysqli_fetch_array($run_total);

  $total = $row_total['total'];

  $get_discount = "select * from customer_discounts where invoice_no='$order_id'";
  $run_discount = mysqli_query($con,$get_discount);
  $row_discount = mysqli_fetch_array($run_discount);

  $coupon_code = $row_discount['coupon_code'];
  $discount_type = $row_discount['discount_type'];
  $discount_amount = $row_discount['discount_amount'];

  $get_del_charges = "select * from order_charges where invoice_id='$order_id'";
  $run_del_charges = mysqli_query($con,$get_del_charges);
  $row_del_charges = mysqli_fetch_array($run_del_charges);

  $del_charges = $row_del_charges['del_charges'];

  if($discount_type==='amount'){

      $grand_total = ($total+$del_charges)-$discount_amount;

  }elseif ($discount_type==='product') {

      $get_off_pro = "select * from products where product_id='$discount_amount'";
      $run_off_pro = mysqli_query($con,$get_off_pro);
      $row_off_pro = mysqli_fetch_array($run_off_pro);

      $off_product_price = $row_off_pro['product_price'];

      $grand_total = ($total+$del_charges)+$off_product_price;
      
  }elseif (empty($discount_type)) {

      $grand_total = $total+$del_charges;
      
  }

  $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.cashfree.com/api/v1/order/create',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('appId' => '4650665d5636e6e9464179340564',
                              'secretKey' => '209d3f7ab9e04a78cfdfa2ad2e97dd14541e18d3',
                              'orderId' => $order_id,
                              'orderAmount' => $grand_total,
                              'orderCurrency' => 'INR',
                              'orderNote' => 'Order Payment',
                              'customerEmail' => $c_email,
                              'customerName' => $c_name,
                              'customerPhone' => $c_contact,
                              'returnUrl' => 'https://karwars.in/admin_area/handleResponse.php',
                              'notifyUrl' => 'https://karwars.in/admin_area/handleResponse.php'),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  $result = json_decode($response, true);
  $pay_link = $result['paymentLink'];
  $status = $result['status'];
  // $reason = $result['reason'];

  if($status==='OK'){

      $long_url = $pay_link;
      $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
      $genericAccessToken = '54be5c94eb234cb15f17c7358d9437d57cc06dc9';

      $data = array(
          'long_url' => $long_url
      );
      $payload = json_encode($data);

      $header = array(
          'Authorization: Bearer ' . $genericAccessToken,
          'Content-Type: application/json',
          'Content-Length: ' . strlen($payload)
      );

      $ch = curl_init($apiv4);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $resultl = curl_exec($ch);

      $resulten = json_decode($resultl, true);

      $link = $resulten['link'];

  $insert_payment = "insert into payment_links (invoice_id,
                                                payment_link,
                                                created_at) 
                                                values 
                                                ('$order_id',
                                                 '$link',
                                                 '$today')";
  $run_insert_payment = mysqli_query($con,$insert_payment);

  if($run_insert_payment){

    //   $textp = "Below%20is%20the%20KARWARS%20GROCERY%20pay%20on%20delivery%20link%20for%20contactless%20delivery%20".$link;

    //   //echo $url = "https://smsapi.engineeringtgr.com/send/?Mobile=9636286923&Password=DEZIRE&Message=".$m."&To=".$tel."&Key=parasnovxRI8SYDOwf5lbzkZc6LC0h"; 
    // //  $url = "http://api.bulksmsplans.com/api/SendSMS?api_id=API31873059460&api_password=W3cy615F&sms_type=T&encoding=T&sender_id=VRNEAR&phonenumber=91$c_contact&textmessage=$text";
    // $url = "http://www.bulksmsplans.com/api/send_sms_multi?api_id=APIMerR2yHK34854&api_password=wernear_11&sms_type=Transactional&sms_encoding=text&sender=VRNEAR&message=$textp&number=+91$c_contact";
    //  // Initialize a CURL session. 
    //  $ch = curl_init();  
     
    //  // Return Page contents. 
    //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
     
    //  //grab URL and pass it to the variable. 
    //  curl_setopt($ch, CURLOPT_URL, $url); 
     
    //  $result = curl_exec($ch);  

      echo "<script>alert('Link Generated')</script>";
      echo "<script>window.open('index.php?view_orders','_self')</script>";    

  }
  }
}


    echo "<script>alert('Order Packed Successfully')</script>";

    echo "<script>window.open('index.php?orders','_self')</script>";


}

}

?>
