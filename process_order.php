<?php 

include("includes/db.php");

if(isset($_GET['update_order'])){

  date_default_timezone_set('Asia/Kolkata');
  $today = date("Y-m-d H:i:s");

  $update_order = $_GET['update_order'];

  $status = $_GET['status'];

  $update_status_del = "UPDATE customer_orders SET order_status='$status',del_date='$today' WHERE invoice_no='$update_order'";

  $run_status_del = mysqli_query($con,$update_status_del);


    echo "<script>alert('Order Packed Successfully')</script>";

    echo "<script>window.open('index.php?orders','_self')</script>";


}

?>