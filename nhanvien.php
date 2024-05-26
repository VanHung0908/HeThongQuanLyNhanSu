<?php
	session_start();
	error_reporting(0);
	include_once ("includes/config.php");
	if(!isset($_SESSION['userlogin'])){
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
        <title>NhanVien - HRMS admin template</title>
          <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <script src="../assets/js/jquery-3.2.1.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../assets/css/dangkylichlam.css">
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
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <?php include_once("includes/header.php"); ?>
		
            <?php
    $username = $_SESSION['userlogin'];
    
    // Kiểm tra xem username có bắt đầu bằng 'QL' không
    if(substr($username, 0, 2) == 'QL') {
        // Nếu có, thực hiện include sidebar.php
        include('includes/sidebar.php');
    }
    // Kiểm tra xem username có bắt đầu bằng 'NV' không
    elseif(substr($username, 0, 2) == 'NV') {
        // Nếu có, thực hiện include sidebar_emp.php
        include('includes/sidebar_emp.php');
    }
    else {
        // Nếu không phải 'QL' hoặc 'NV', báo lỗi
        echo "Lỗi: Username không hợp lệ.";
    }

?>
			<!-- Page Wrapper -->
            <div class="page-wrapper">
				<!-- Page Content -->
                <div class="content container-fluid">
            <?php
              if(isset($_REQUEST['page'])){
                $page=$_REQUEST['page'];
                if($page =='chamcong') {
                    $MaND=$_SESSION['MaND'];
                    try{
                        $sql ="select * from khuonmat where MaND=$MaND  ";
                        $nhandien = $dbh->query($sql);

                        if ($nhandien->rowCount() > 0) {
                            include_once ('./page/chamcong/test.php');
                        } else {
                            include_once ('./page/chamcong/train.php');
                        }
                    }catch (PDOException $e) {
                            // Xử lý ngoại lệ nếu có lỗi xảy ra
                            echo "Lỗi: " . $e->getMessage();
                        }
                }    elseif($page =='dangkilich') {
                        include_once ('./page/DKLich/dangkylich.php');
                    } elseif($page == 'xemlich') {
                        include_once('./page/xemlich/xemlich.php');
                    }
                    elseif($page == 'dangkynghiphep') {
                        include_once('./page/DKNghiPhep/DKNP.php');
                    }
                    elseif($page == 'xembangluong') {
                        include_once('./page/xemluong/xembangluong.php');
                    }
                    elseif($page == 'canhan') {
                        include_once ('./page/canhan/canhan.php');
                    }
              }
            ?>

  </div>
</div>
   </div>
        </div>
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		<!-- Slimscroll JS -->
		<script src="assets/js/jquery.slimscroll.min.js"></script>
		<!-- Chart JS -->
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/plugins/raphael/raphael.min.js"></script>
		<script src="assets/js/chart.js"></script>
		<!-- Custom JS -->
		<script src="assets/js/app.js"></script>
		<!-- javascript links ends here  -->
    </body>
</html>



  