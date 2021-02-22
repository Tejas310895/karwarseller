<!--

=========================================================
* Now UI Dashboard - v1.5.0
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-dashboard
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->

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
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="images/karwarslogo.png">
  <link rel="icon" type="image/png" href="images/karwarslogo.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Karwars Seller Panel
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="now-ui-dashboard/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="now-ui-dashboard/assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="now-ui-dashboard/assets/demo/demo.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<body class="">
    <div class="wrapper ">
      <?php 
      
      include("includes/sidebar.php");

      ?>
      <div class="main-panel" id="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
          <div class="container-fluid">
            <div class="navbar-wrapper">
              <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                  <span class="navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </button>
              </div>
              <a class="navbar-brand mt-2" href="#"><?php if(isset($_GET['orders'])){echo "Orders Dashboard";}elseif(isset($_GET['reports'])){echo "Order Reports";}else{echo "Dashboard";} ?></a>
            </div>
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
            </button> -->
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
              <ul class="navbar-nav">
                <!-- <li class="nav-item">
                  <a class="nav-link" href="logout.php" onclick="return confirm('Are you sure?')">
                    <i class="now-ui-icons media-1_button-power"></i>
                    <p>
                      <span class="d-lg-none d-md-block">Logout</span>
                    </p>
                  </a>
                </li> -->
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
        <div class="panel-header panel-header-lg">
          <!-- <canvas id="bigDashboardChart"></canvas> -->
        </div>
        <div class="content">
          <?php 
          
          if(isset($_GET['orders'])){
                      
              include("orders.php");
              
            }

            if(isset($_GET['reports'])){
                      
              include("reports.php");
              
            }
          
          ?>
        </div>
      </div>
  </div>
  <!--   Core JS Files   -->
  <script src="now-ui-dashboard/assets/js/core/jquery.min.js"></script>
  <script src="now-ui-dashboard/assets/js/core/popper.min.js"></script>
  <script src="now-ui-dashboard/assets/js/core/bootstrap.min.js"></script>
  <script src="now-ui-dashboard/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="now-ui-dashboard/assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="now-ui-dashboard/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="now-ui-dashboard/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="now-ui-dashboard/assets/demo/demo.js"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      demo.initDashboardPageCharts();

    });
  </script>
</body>

</html>
<?php } ?>