<?php
session_start();
error_reporting(0);
include_once("includes/config.php");
if(isset($_POST['login'])){
	$_SESSION['userlogin'] = $_POST['username'];
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$password=MD5($password);
	$sql = "SELECT TenDangNhap, MatKhau FROM TaiKhoan WHERE TenDangNhap=:username ";
	$query = $dbh->prepare($sql);
	$query->bindParam(':username',$username,PDO::PARAM_STR);
	$query-> execute();
	$results=$query->fetchAll(PDO::FETCH_OBJ);
	if($query->rowCount() > 0){
		foreach ($results as $row) {
			$hashpass=$row->MatKhau;
		}//verifying Password
		if ($password===$hashpass) {
			$_SESSION['userlogin']=$_POST['username'];
			$_SESSION['pass']=$password;
			header("Location:index.php");
		}else {
			$wrongusername = "<div class='alert alert-danger'>Tên đăng nhập hoặc mật khẩu không chính xác. Vui lòng thử lại.</div>";
		}
	}else {
		$wrongusername = "<div class='alert alert-danger'>Tên đăng nhập hoặc mật khẩu không chính xác. Vui lòng thử lại.</div>";
	}
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
		<title>Login - HRMS admin</title>
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
	</head>
	<body class="account-page">
		<!-- Main Wrapper -->
		<div class="main-wrapper">
			<div class="account-content">
				<div class="container">
					<!-- Account Logo -->
					<div class="account-logo">
						<a href=""><img src="./assets/img/logo4.png" alt="Company Logo"></a>
					</div>
					<!-- /Account Logo -->
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<!-- Account Form -->
							<form method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<label>User Name</label>
									<input class="form-control <?php if($wrongusername) echo 'is-invalid'; ?>" name="username" required type="text">
                                <?php echo $wrongusername; ?>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col">
											<label>Password</label>
										</div>
									</div>
									<input class="form-control" name="password" required type="password">
								</div>
								<?php if($wrongusername){echo $wrongusername;}?>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" name="login" type="submit">Login</button>
										
								</div>
									
							</form>
							<!-- /Account Form -->
							
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</body>
</html>