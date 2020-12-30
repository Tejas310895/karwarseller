<?php 

include("includes/db.php");

if(isset($_POST['submit'])){
$invoice_no = $_POST['invoice_no'];
$delivery_partner = $_POST['delivery_partner'];

date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");

$get_total = "SELECT sum(due_amount) AS total FROM customer_orders where invoice_no='$invoice_no'";
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

$chatApiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2MTE5MzgyODIsInVzZXIiOiI5MTg2MTgyNTk2NjEifQ.K_bsE48VS705q-9ResDr1keJotUm1BMOm2dzOfyGC8E"; // Get it from https://www.phphive.info/255/get-whatsapp-password/
        
    $number =   $delivery_partner; // Number
    $message = "Order Details \n";
    $message .= "Order-Id-".$invoice_no."\n"; // Message
    $message .= "Name-".$customer_name."\n"; // Message
    $message .= "Order Date-".date('d/M/Y,h:i a',strtotime($order_date))."\n"; // Message
    $message .= "Pack Date-".date('d/M/Y,h:i a',strtotime($today))."\n"; // Message
    $message .= $customer_address.",".$customer_phase.",".$customer_landmark.",".$customer_city."\n \n"; // Message

    $get_waclient = "SELECT DISTINCT(client_id) FROM customer_orders WHERE invoice_no='$invoice_no'";
        $run_waclient = mysqli_query($con,$get_waclient);
        while($row_waclient=mysqli_fetch_array($run_waclient)){
        $waclient_id = $row_waclient['client_id'];

        $get_client = "select * from clients where client_id='$waclient_id'";
        $run_client = mysqli_query($con,$get_client);
        $row_client = mysqli_fetch_array($run_client);
        
        $client_shop = $row_client['client_shop'];

        $get_client_total = "SELECT sum(due_amount) AS client_total from customer_orders where invoice_no='$invoice_no' and client_id='$waclient_id'";
        $run_client_total = mysqli_query($con,$get_client_total);
        $row_client_total = mysqli_fetch_array($run_client_total);

        $client_total = $row_client_total['client_total'];

        $message .= $client_shop."\n"."Total :-Rs".$client_total."/- \n \n";

        }
    $message .= "Grand Total:-".$total;

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$chatApiToken,
        'Content-Type: application/json'
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    //echo $response;

    $update_status_pac = "UPDATE customer_orders SET order_status='Packed' WHERE invoice_no='$invoice_no'";

    $run_status_pac = mysqli_query($con,$update_status_pac);

        echo "<script>alert('Status Updated')</script>";
        echo "<script>window.open('index.php','_self')</script>";
}else{
    echo "<script>alert('Error Try Again')</script>";
    echo "<script>window.open('index.php','_self')</script>";
}
?>