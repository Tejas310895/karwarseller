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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Panel</title>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="styles/bootstrap.min.css" >
    <link rel="stylesheet" href="styles/bootstrap.css" >
    <!-- bootstrap link -->
    <link rel="stylesheet" href="styles/jquery-ui.css">
    <script type="text/javascript" src="js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <!-- google fonts -->
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet'>
    </head>
<body style="margin-top:10%;">
<!-- header -->
    
    <div class="fixed-top bg-white">
        <nav class="navbar navbar-expand-sm navbar-light py-0">
            <a class="navbar-brand" href="#">
                <img src="images/karwarslogo.png" class="mx-2" width="100" height="60" alt="">
            </a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active navbox pt-1 m-1">
                        <a class="nav-link navbutton py-1 px-2" href="#">ORDERS</a>
                    </li>
                    <li class="nav-item active navbox pt-1 m-1">
                        <a class="nav-link navbutton py-1 px-2" href="#">ANALITICS</a>
                    </li>
                </ul>
                <a href="logout.php" class="text-right mx-5 btn btn-danger">Logout</a>
            </div>
        </nav>
        <h1 class="dash-head ml-5 mt-3">ORDERS <small>Dashboard</small></h1>
    </div>
<!-- header -->
<!-- body -->
    <?php 
    
    $get_invoice = "SELECT DISTINCT invoice_no FROM customer_orders WHERE order_status in ('Order Placed','Out for Delivery') ORDER BY order_id DESC";

    $run_invoice = mysqli_query($con,$get_invoice);

    while($row_invoice=mysqli_fetch_array($run_invoice)){

        $invoice_id = $row_invoice['invoice_no'];

        $get_orders = "SELECT * from customer_orders where invoice_no='$invoice_id'";

        $run_orders = mysqli_query($con,$get_orders);

        $order_count = mysqli_num_rows($run_orders);

        $row_orders = mysqli_fetch_array($run_orders);

        $c_id = $row_orders['customer_id'];

        $date = $row_orders['order_date'];

        $add_id = $row_orders['add_id'];

        $order_date = $row_orders['order_date'];

        $order_status = $row_orders['order_status'];

        $get_total = "SELECT sum(due_amount) AS total FROM customer_orders WHERE invoice_no='$invoice_id' and product_status='Deliver'";

        $run_total = mysqli_query($con,$get_total);

        $row_total = mysqli_fetch_array($run_total);

        $total = $row_total['total'];

        $get_customer = "select * from customers where customer_id='$c_id'";

        $run_customer = mysqli_query($con,$get_customer);

        $row_customer = mysqli_fetch_array($run_customer);

        $c_name = $row_customer['customer_name'];

        $c_contact = $row_customer['customer_contact'];

        $get_add = "select * from customer_address where add_id='$add_id'";

        $run_add = mysqli_query($con,$get_add);

        $row_add = mysqli_fetch_array($run_add);

        $customer_address = $row_add['customer_address'];

        $customer_phase = $row_add['customer_phase'];

        $customer_landmark = $row_add['customer_landmark'];

        $customer_city = $row_add['customer_city'];

        $get_discount = "select * from customer_discounts where invoice_no='$invoice_id'";
        $run_discount = mysqli_query($con,$get_discount);
        $row_discount = mysqli_fetch_array($run_discount);

        $discount_type = $row_discount['discount_type'];
        $discount_amount = $row_discount['discount_amount'];
    
    ?>

    <div class="container-fluid px-5 py-3 pt-3">
        <div class="row orders-box p-3">
            <div class="col-4 text-left">
                <h6>ID - <?php echo $invoice_id; ?></h6>
                <h6>Date - <?php echo date('d/M/Y,h:i:s a',strtotime($order_date)); ?></h6>
                <h4>Name - <?php echo $c_name; ?></h4>
            </div>
            <div class="col-4 pt-3">
                <h6>Address - <?php echo $customer_address; ?>, 
                            <?php echo $customer_phase; ?>, 
                            <?php echo $customer_landmark; ?>, 
                            <?php echo $customer_city; ?> . 
                </h6>
                <h4>Contact - <?php echo $c_contact; ?></h4>
            </div>
            <div class="col-4 text-right pt-2 text-white">
            <button id="show_details" class="btn btn-info mx-2 px-3 mb-3" data-toggle="modal" data-target="#cK<?php echo $invoice_id; ?>">View</button>
                <!-- Modal -->
                <div class="modal modal-black fade text-dark" id="cK<?php echo $invoice_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Order Id - <?php echo $invoice_id; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="tim-icons icon-simple-remove"></i>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">IMAGE</th>
                                <th class="text-center">ITEMS</th>
                                <th class="text-center">QTY</th>
                                <th class="text-right">PRICE</th>
                                <!-- <th class="text-right">Status</th> -->
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        
                        $get_pro_id = "select * from customer_orders where invoice_no='$invoice_id' ";

                        $run_pro_id = mysqli_query($con,$get_pro_id);

                        $counter = 0;

                        while($row_pro_id = mysqli_fetch_array($run_pro_id)){

                        $pro_id = $row_pro_id['pro_id'];

                        $qty = $row_pro_id['qty'];

                        $sub_total = $row_pro_id['due_amount'];

                        $client_id = $row_pro_id['client_id'];

                        $pro_price = $sub_total/$qty;                                  

                        $pro_status = $row_pro_id['product_status'];

                        $get_pro = "select * from products where product_id='$pro_id'";

                        $run_pro = mysqli_query($con,$get_pro);

                        $row_pro = mysqli_fetch_array($run_pro);

                        $pro_title = $row_pro['product_title'];

                        $pro_img1 = $row_pro['product_img1'];

                        // $pro_price = $row_pro['product_price'];

                        $pro_desc = $row_pro['product_desc'];
                        
                        // $sub_total = $pro_price * $qty;

                        $get_min = "select * from admins";

                        $run_min = mysqli_query($con,$get_min);

                        $row_min = mysqli_fetch_array($run_min);

                        $min_price = $row_min['min_order'];

                        $del_charges = $row_min['del_charges'];

                        ?>
                            <tr>
                                <td class="text-center">
                                <img src="<?php echo $pro_img1; ?>" alt="" class="img-thumbnail border-0" width="60px">
                                </td>
                                <td class="text-center"><?php echo $pro_title; ?><br><?php echo $pro_desc; ?></td>
                                <td class="text-center"><?php echo $qty; ?> x ₹ <?php echo $pro_price; ?></td>
                                <td class="text-right">₹ <?php echo $sub_total; ?></td>
                                <!-- <td class="text-right"><?php //echo $pro_status; ?></td> -->
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary text-left" data-dismiss="modal">Close</button>
                        <h3 class="card-title">Total - ₹ <?php echo $total+$del_charges; ?>/-</h3>
                    </div>
                    </div>
                </div>
                </div>
                
                <a href="print.php?print=<?php echo $invoice_id; ?>&vendor_id=<?php echo $client_id; ?>" target="_blank" class="btn btn-primary mx-2 px-3 mb-3">Print</a>
                <form action="delivery_notification.php" method="post">
                    <input type="hidden" name="invoice_no" value="<?php echo $invoice_id; ?>">
                    <div class="form-row align-items-right">
                        <div class="col-auto my-1">
                        <select class="custom-select mr-sm-2" name="delivery_partner" id="inlineFormCustomSelect" required>
                            <option disabled selected value>Select Delivery Man</option>
                            <?php 
                            
                            $get_delivery_boy = "select * from delivery_partner";
                            $run_delivery_boy = mysqli_query($con,$get_delivery_boy);
                            while($row_delivery_boy = mysqli_fetch_array($run_delivery_boy)){

                            $delivery_partner_name = $row_delivery_boy['delivery_partner_name'];
                            $delivery_partner_contact = $row_delivery_boy['delivery_partner_contact'];
                            
                            ?>
                            <option value="<?php echo $delivery_partner_contact; ?>"><?php echo $delivery_partner_name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div class="col-auto my-1">
                        <button type="submit" name="submit" class="btn btn-primary">Packed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
<!-- body -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>

<?php 
    }
?>