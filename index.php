<?php
	session_start();
	error_reporting(0);
	include_once "./includes/config.php";
	if( !isset($_SESSION['userlogin'])){
		header('location:login.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Dashboard - HRMS admin template</title>
      
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="assets/css/line-awesome.min.css">
		
		<!-- Chart CSS -->
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
	
    <body>
        <div class="main-wrapper">
            <?php include_once("includes/header.php"); ?>
            <?php
   	 $username = $_SESSION['userlogin'];
    // Kiểm tra xem username có bắt đầu bằng 'QL' không
    if(strpos($username, 'QL') === 0) {
        // Nếu có, thực hiện include sidebar.php
        include_once('./includes/sidebar.php');	
    }
    // Kiểm tra xem username có bắt đầu bằng 'NV' không
    else if(strpos($username, 'NV') === 0) {
        // Nếu có, thực hiện include sidebar_emp.php
        include_once('./includes/sidebar_emp.php');
    }
?>
            <div class="page-wrapper">
				<!-- Page Content -->
                <div class="content container-fluid">
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome <?php echo htmlentities(ucfirst($_SESSION['hoten']), ENT_QUOTES, 'UTF-8');?>!</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
			<?php
				if(strpos($username, 'QL') === 0) {
					echo '
					<div class="row">
						<div class="col">
							<div class="card-group ">
								<div class="card ">
							';
									 include_once('./page/chart/SoluongNV.php'); 
							echo'	</div>
								<div class="card">
							';
									 include_once('./page/chart/sumchiphi.php'); 
							echo '</div>
								<div class="card">
							';	
								 include_once('./page/chart/SumNV.php'); 	
							echo'</div>
							</div>
						</div>	
					</div>
							
					<div class="card-footer">
					';

						 include_once('./page/chart/Sumall.php'); 
					echo' </div>
					';
				}
				// Kiểm tra xem username có bắt đầu bằng 'NV' không
				else if(strpos($username, 'NV') === 0) {
					include_once('./page/xemlich/xemlich.php'); 
				}
			?>
					
							</div>
						</div>
					</div>
				
				</div>
   </div>
        </div>
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/jquery.slimscroll.min.js"></script>
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/plugins/raphael/raphael.min.js"></script>
		<script src="assets/js/chart.js"></script>
		<script src="assets/js/app.js"></script>
    </body>
</html>

