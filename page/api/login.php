<?php
session_start();
header('Content-Type: application/json');

include_once("../../includes/config.php");

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        $password = MD5($password);
        
        $sql = "SELECT TenDangNhap, MatKhau, MaND FROM TaiKhoan WHERE TenDangNhap=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                $hashpass = $row->MatKhau;
                $MaND = $row->MaND; 
            }
            if ($password === $hashpass) {
                $_SESSION['userlogin'] = $username;
                $_SESSION['MaND'] = $MaND; // Lưu MaND vào session
                $response['status'] = 'success';
                $response['message'] = 'Login successful';
                $response['redirect'] = 'index.php';
                $response['MaND'] = $MaND;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Username hoặc Password không đúng';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Username hoặc Password không đúng';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Username and password are required';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>