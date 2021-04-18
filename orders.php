<div class="row">
<?php 

    if($client_id==1){
        $get_invoice = "SELECT DISTINCT invoice_no FROM customer_orders WHERE order_status in ('Order Placed') ORDER BY order_id DESC";
    }else{
        $get_invoice = "SELECT DISTINCT invoice_no FROM customer_orders WHERE order_status in ('Order Placed') and client_id='$client_id' ORDER BY order_id DESC";
    }
    
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

        $get_total = "SELECT sum(due_amount) AS total FROM customer_orders WHERE invoice_no='$invoice_id' and client_id='$client_id' and product_status='Deliver'";

        $run_total = mysqli_query($con,$get_total);

        $row_total = mysqli_fetch_array($run_total);

        $total = $row_total['total'];

        $get_vendor_total = "SELECT sum(vendor_due_amount) AS vendor_total FROM customer_orders WHERE invoice_no='$invoice_id' and client_id='$client_id' and product_status='Deliver'";

        $run_vendor_total = mysqli_query($con,$get_vendor_total);

        $row_vendor_total = mysqli_fetch_array($run_vendor_total);

        $vendor_total = $row_vendor_total['vendor_total'];

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
          <div class="col-lg-12 col-md-6">
            <div class="card card-chart" style="border-radius:1.8rem;">
                <div class="card-header">
                    <h5 class="card-category">Order Id - <?php echo $invoice_id; ?></h5>
                    <h5 class="card-category">Date - <?php echo date('d/M/Y @ h:i:s a',strtotime($order_date)); ?></h5>
                    <h4 class="card-title">Name - <?php echo $c_name; ?></h4>
                    <h6 class="card-title">Mobile - +91 <?php echo $c_contact; ?></h6>
                    <h6 class="card-title">Address - 
                    <?php echo $customer_address; ?>, 
                    <?php echo $customer_phase; ?>, 
                    <?php echo $customer_landmark; ?>, 
                    <?php echo $customer_city; ?> . 
                    </h6>
                </div>
                <div class="card-body">
                    <a href="process_order.php?update_order=<?php echo $invoice_id;?>&status=Packed" class="btn btn-success pull-right" onclick="return confirm('Are you sure?')" style="padding: 11px 21px;">
                    <i class="now-ui-icons shopping_basket"></i>
                    Packed
                    </a>
                    <a href="<?php if($client_id==1){ echo "main_print.php";}else{echo "vendor_print.php";}?>?print=<?php echo $invoice_id; ?>&vendor_id=<?php echo $client_id; ?>" target="_blank" class="btn btn-info pull-right" style="padding: 11px 21px;">
                    <i class="now-ui-icons files_paper"></i>
                    Print
                    </a>
                    <button id="show_details" class="btn btn-danger pull-right" data-toggle="modal" data-target="#cK<?php echo $invoice_id; ?>" style="padding: 11px 21px;">
                    <i class="now-ui-icons travel_info"></i>
                    View
                    </button>
                </div>
            </div>
          </div>
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
                            <th class="text-right">MRP</th>
                            <th class="text-right">SELL PRICE</th>
                            <!-- <th class="text-right">Status</th> -->
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    
                    $get_pro_id = "select * from customer_orders where invoice_no='$invoice_id' and client_id='$client_id' and product_status='Deliver'";

                    $run_pro_id = mysqli_query($con,$get_pro_id);

                    $counter = 0;

                    while($row_pro_id = mysqli_fetch_array($run_pro_id)){

                    $pro_id = $row_pro_id['pro_id'];

                    $qty = $row_pro_id['qty'];

                    $sub_total = $row_pro_id['due_amount'];

                    $vendor_sub_total = $row_pro_id['vendor_due_amount'];

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
                            <td class="text-center"><?php echo $qty; ?></td>
                            <td class="text-right">₹ <?php echo $sub_total; ?></td>
                            <td class="text-right">₹ <?php echo $vendor_sub_total; ?></td>
                            <!-- <td class="text-right"><?php //echo $pro_status; ?></td> -->
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-primary text-left" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">MRP - ₹ <?php echo $total; ?>/-</h5>
                            <h5 class="card-title">SELL PRICE - ₹ <?php echo round($vendor_total, 2); ?>/-</h5>
                        </div>
                    </div>     
                </div>
                </div>
            </div>
            </div>
<?php } ?>
</div>