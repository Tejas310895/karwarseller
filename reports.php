  <?php 
  
    $get_reports = "SELECT * FROM customer_orders where client_id='$client_id' GROUP BY CAST(del_date as DATE) order by del_date desc";
    $run_reports = mysqli_query($con,$get_reports);
    $counter = 0;
    while($row_reports = mysqli_fetch_array($run_reports)){
    $del_date = $row_reports['del_date'];
    $delivery_date = date('Y-m-d',strtotime($del_date));
    $display_delivery_date = date('d-M-Y',strtotime($del_date));

    $counter = ++$counter;
    
    $get_total_purchase = "select sum(due_amount) as total_purchase from customer_orders where CAST(del_date as DATE)='$delivery_date' and client_id='$client_id' and order_status='Delivered' and product_status='Deliver'";
    $run_total_purchase = mysqli_query($con,$get_total_purchase);
    $row_total_purchase = mysqli_fetch_array($run_total_purchase);

    $total_purchase = $row_total_purchase['total_purchase'];


  ?>
  <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0 text-center">
        <button class="btn btn-link btn-block" data-toggle="collapse" data-target="#collapse<?php echo $counter; ?>" aria-expanded="true" aria-controls="collapseOne">
          <h6 class="mb-0">Order Report <br><?php echo $display_delivery_date; ?></h6>
          <h6 class="mb-0"><small>( Total Amount ₹<?php if($total_purchase>0){echo $total_purchase;}else{ echo 0;} ?>/- )</small></h6>
          <i class="now-ui-icons arrows-1_minimal-down"></i>
        </button>
      </h5>
    </div>
    <div id="collapse<?php echo $counter; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="card-body">
      <div class="table-responsive">
          <table class="table text-center">
            <thead class=" text-primary">
              <th>
                Status
              </th>
              <th>
                Order Id
              </th>
              <th>
                Name
              </th>
              <th>
                Amount
              </th>
              <th>
                Action
              </th>
            </thead>
            <tbody class="text-center">
              <?php 
              
              $get_orders = "select * from customer_orders where CAST(del_date as DATE)='$delivery_date' and client_id='$client_id' group by invoice_no";
              $run_orders = mysqli_query($con,$get_orders);
              while($row_orders=mysqli_fetch_array($run_orders)){
                  $invoice_no = $row_orders['invoice_no'];
                  $order_status = $row_orders['order_status'];
                  $customer_id = $row_orders['customer_id'];

                  $get_customer = "select * from customers where customer_id='$customer_id'";
                  $run_customer = mysqli_query($con,$get_customer);
                  $row_customer = mysqli_fetch_array($run_customer);
                  $customer_name = $row_customer['customer_name'];

                  $get_order_total = "select sum(due_amount) as order_total from customer_orders where invoice_no='$invoice_no' and client_id='$client_id' and product_status='Deliver'";
                  $run_order_total = mysqli_query($con,$get_order_total);
                  $row_order_total = mysqli_fetch_array($run_order_total);

                  $order_total = $row_order_total['order_total'];

              ?>
              <tr>
                <td>
                  <?php echo $order_status; ?>
                </td>
                <td>
                  <?php echo $invoice_no; ?>
                </td>
                <td>
                  <?php echo $customer_name; ?>
                </td>
                <td>
                  <?php if($order_total>0){echo $order_total;}else{ echo 0;} ?>
                </td>
                <td>
                    <button id="show_details" class="btn btn-danger pull-right mx-1" data-toggle="modal" data-target="#cK<?php echo $invoice_no; ?>">
                    <i class="now-ui-icons travel_info"></i>
                    View
                    </button>
                    <!-- Modal -->
                    <div class="modal modal-black fade text-dark" id="cK<?php echo $invoice_no; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Order Id - <?php echo $invoice_no; ?></h5>
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
                                
                                $get_pro_id = "select * from customer_orders where invoice_no='$invoice_no' and client_id='$client_id'";

                                $run_pro_id = mysqli_query($con,$get_pro_id);
                     
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
                                        <td class="text-right"><?php if($pro_status==="Deliver"){echo "₹".$sub_total;}else{echo "Cancelled";} ?></td>
                                        <!-- <td class="text-right"><?php //echo $pro_status; ?></td> -->
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary text-left" data-dismiss="modal">Close</button>
                                <?php 
                                
                                $get_total = "select sum(due_amount) as total from customer_orders where invoice_no='$invoice_no' and client_id='$client_id' and product_status='Deliver'";
                                $run_total = mysqli_query($con,$get_total);
                                $row_total = mysqli_fetch_array($run_total);

                                $total = $row_total['total'];
                                
                                ?>
                                <h3 class="card-title">Total - ₹ <?php echo $total; ?>/-</h3>
                            </div>
                            </div>
                        </div>
                        </div>
                        <a href="<?php if($client_id==1){ echo "main_print.php";}else{echo "vendor_print.php";}?>?print=<?php echo $invoice_no; ?>&vendor_id=<?php echo $client_id; ?>" target="_blank" class="btn btn-info pull-right mx-1 mt-1">
                        <i class="now-ui-icons files_paper"></i>
                        Print
                        </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>