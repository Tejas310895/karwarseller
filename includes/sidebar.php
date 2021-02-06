<div class="sidebar" data-color="orange">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
        -->
      <div class="logo bg-white">
        <a href="#" class="simple-text logo-normal">
            <img src="images/karwarslogo.png" class="mx-auto d-block" width="100" height="60" alt="">
        </a>
      </div>
      <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
          <li class="<?php if(isset($_GET['orders'])){echo "active";} ?>">
            <a href="index.php?orders">
              <i class="now-ui-icons design_app"></i>
              <p>Orders</p>
            </a>
          </li>
          <li class="<?php if(isset($_GET['reports'])){echo "active";} ?>">
            <a href="index.php?reports">
              <i class="now-ui-icons business_chart-bar-32"></i>
              <p>Reports</p>
            </a>
          </li>
          <li class="">
            <a href="logout.php" onclick="return confirm('Are you sure?')">
              <i class="now-ui-icons media-1_button-power"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </div>
    </div>