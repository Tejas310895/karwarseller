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
          <h6 class="mb-0">Order Report <?php echo $display_delivery_date; ?></h6>
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
                  <a href="" class="btn btn-success mx-3 mt-2">View</a>
                  <a href="" class="btn btn-info mx-3 mt-2">Print</a>
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