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


    echo "<script>alert('Order Packed Successfully')</script>";

    echo "<script>window.open('index.php?orders','_self')</script>";


}

}

?>
