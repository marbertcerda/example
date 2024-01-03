<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>User - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    <script src="https://kit.fontawesome.com/5a10e0b94b.js" crossorigin="anonymous"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <style>
    .eme {

        transition: 2s;
    }

    .eme:hover {
        background-color: #caf0f8;
    }
    </style>




</head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>



<?php
session_start();


$userID = $_SESSION['user_id'];
require "connect.php";
$sql = "SELECT * FROM `user` WHERE user_id = $userID  ";
$dataset = $link->query($sql);

if (mysqli_num_rows($dataset) > 0) {
    while ($data = $dataset->fetch_assoc()) {
        $userName = $data['name'];
    }
}




?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" style="background-color: #224251;" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Vtrack </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="userpending.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Go Back</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">



            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>










            <!-- Divider -->
            <hr class="sidebar-divider">





            <!-- Divider -->


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light  topbar mb-4 static-top shadow"
                    style="background-color: #243547;">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>






                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>





                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $userName; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">



                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>

                    </div>

                    <!-- Content Row -->




                    <div class="row">


                        <?php

                        $carID = $_GET['id'];

                        $sql = "SELECT `car`.`car_id`, `car`.`model`, `car`.`brand`, `car`.`seat_capacity`, `car`.`car_picture`, `car`.`car_owner`, `car`.`car_owner_email`, `car`.`car_owner_phoneno`, `car`.`address`, `car`.`status`, `car`.`campus`
                            FROM `car`
                            WHERE `car`.`car_id` = '$carID' ";

                        $dataset = $link->query($sql) or die('Error Query');
                        if (mysqli_num_rows($dataset) > 0) {

                            while ($data = $dataset->fetch_assoc()) {
                                echo "  <!-- Card Card -->
                                            
                                          <div class='col-xl-4 col-md-6 mb-4 '><a href='index.php ' style='text-decoration: none; color: black;'>
                                              <div class='card border-left-primary shadow h-100 py-2 eme' 
                                                  style='height: 20px; width: 330px; '>
                                                  <div class='card-body'>
                                                      <div class='row no-gutters align-items-center'>
                                                          <div class='col mr-2'>
                                                              <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'>
                                                              $data[campus]</div>
                                                              <div class='h5 mb-0 font-weight-bold text-gray-800'>
                                                                  <img src='cars/$data[car_picture]' alt=''
                                                                      style='width:290px; height: 180px; margin-top: 20px; object-fit: cover; border-radius: 20px; '>
                                                              </div>
                                                          </div>
                                                          <div class='col-auto'>
                                                          <br>
                                                         <span class='font-weight-bold text-uppercase'> $data[brand] </span> <br>
                                                          $data[model] <br>
                                                              Capacity: $data[seat_capacity] <br>
                                                              Car ID: $data[car_id]
                  
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div></a>
                                          </div> ";
                            }
                        }



                        ?>



                        <?php

                        $reqNo = $_GET['reqno'];

                        $details = "SELECT `reservation`.`booking_no`, `reservation`.`user_id`, `user`.`name`, `user`.`position`, `user`.`campus`, `user`.`user_email`, `reservation`.`booking_no`, 
                        `booking_details`.`date`, `booking_details`.`time`, `booking_details`.`destination`, `booking_details`.`Dropoff`, `booking_details`.`passengers`, `reservation`.`request_no`, `reservation`.`driver_id`, `driver`.`name` as 'driver', 
                        `driver`.`address`, `driver`.`driver_email`, `driver`.`contact`
                        FROM `reservation` 
                            LEFT JOIN `user` ON `reservation`.`user_id` = `user`.`user_id` 
                            LEFT JOIN `booking_details` ON `reservation`.`booking_no` = `booking_details`.`booking_no` 
                            LEFT JOIN `driver` ON `reservation`.`driver_id` = `driver`.`driver_id`
                        WHERE `reservation`.`request_no` = '$reqNo'";

                        $dataset2 = $link->query($details) or die('Error Query');
                        if (mysqli_num_rows($dataset2) > 0) {

                            while ($data = $dataset2->fetch_assoc()) {

                                $date =  new DateTime($data['date'] . $data['time']);
                                $formattedTime = $date->format('g:i a');

                                $emppId = $data['user_id'];
                                $empName = $data['name'];
                                $position = $data['position'];
                                $campus = $data['campus'];
                                $userEmail = $data['user_email'];

                                $bookingNo = $data['booking_no'];
                                $bookingDate = $data['date'];
                                $time = $data['time'];
                                $destination = $data['destination'];
                                $passengers = $data['passengers'];


                                $driver = $data['driver'];
                                $driverID = $data['driver_id'];
                                $driverAddress = $data['address'];
                                $driveremail = $data['driver_email'];
                                $driverContact = $data['contact'];
                                $dropofflocation = $data['Dropoff'];



                                $userEmail = $data['user_email'];


                                $passengersArray = explode(",", $passengers);
                            }
                        }


                        ?>



                        <div class='col-xl-4 col-md-6 mb-4 '>
                            <div class='card border-left-primary shadow h-100 py-2 eme'
                                style='height: 20px; width: 330px; '>
                                <div class='card-body'>
                                    <div class='row no-gutters align-items-center'>
                                        <div class='col mr-2'>
                                            <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'>
                                                Booking No: <?php echo $bookingNo; ?></div>

                                            <div class="row">

                                                <div class='col-sm-6 h5 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px; text-align: left;">
                                                    <br>

                                                    Booking Date: <br><br>
                                                    Preferred Time: <br><br>
                                                    Destination: <br><br>
                                                    Drop Off Location: <br><br>


                                                </div>

                                                <div class='col-sm-6 h5 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px;">
                                                    <br>

                                                    <?php echo $bookingDate; ?><br><br>
                                                    <?php echo $formattedTime; ?><br><br>
                                                    <?php echo $destination; ?><br><br>
                                                    <?php echo $dropofflocation; ?><br><br>


                                                </div>


                                            </div>




                                            <hr>
                                            <div class="row ">

                                                <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'>
                                                    Driver Details</div>


                                            </div>

                                            <div class="row">

                                                <div class='col-sm-6 h6 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px; text-align: left;">
                                                    <br>

                                                    Driver ID: <br><br>
                                                    Name: <br><br>
                                                    Address: <br><br>
                                                    Email: <br><br>
                                                    Number: <br><br>


                                                </div>

                                                <div class='col-sm-6 h6 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px;">
                                                    <br>

                                                    <?php echo $driverID; ?><br><br>
                                                    <?php echo $driver; ?><br><br>
                                                    <?php echo $driverAddress; ?><br><br>
                                                    <?php echo $driveremail; ?><br><br>
                                                    <?php echo $driverContact; ?><br><br>






                                                </div>






                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class='col-xl-4 col-md-6 mb-4 '>
                            <div class='card border-left-primary shadow h-100 py-2 eme'
                                style='height: 20px; width: 330px; '>
                                <div class='card-body'>
                                    <div class='row no-gutters align-items-center'>
                                        <div class='col mr-2'>
                                            <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'>
                                                User Details</div>



                                            <div class="row">

                                                <div class='col-sm-4 h6 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px; text-align: left;">
                                                    <br>

                                                    User ID: <br><br>
                                                    Name: <br><br>
                                                    Position: <br><br><br>
                                                    Campus: <br><br>
                                                    Email: <br><br>


                                                </div>

                                                <div class='col-sm-8 h6 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px;">
                                                    <br>

                                                    <?php echo $emppId; ?><br><br>
                                                    <?php echo  $empName; ?><br><br>
                                                    <?php echo $position; ?><br><br>
                                                    <?php echo $campus; ?><br><br>
                                                    <?php echo $userEmail; ?><br><br>





                                                </div>

                                                <hr>
                                                <div class="row ">


                                                    <div class='text-xs font-weight-bold text-primary text-uppercase mb-1'
                                                        style="padding: 22px;">
                                                        Passengers</div>


                                                </div>


                                                <div class='col-sm-12 h6 mb-0 font-weight-bold text-gray-800'
                                                    style="font-size: 14px;">


                                                    <?php

                                                    $i = 0;
                                                    foreach ($passengersArray as $value) {
                                                        ++$i;

                                                        echo "$i . $value <br> ";
                                                    }

                                                    ?><br>






                                                </div>






                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>




                </div>



            </div>
        </div>

    </div>
    <!-- /.container-fluid -->













    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2021</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>










    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>