<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include_once("../../includes/config.php");

// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
    
    if (!empty($username) && !empty($password)) {
        // Hash the password
        $password = MD5($password);
        
        // Prepare SQL statement
        $sql = "SELECT TenDangNhap, MatKhau FROM TaiKhoan WHERE TenDangNhap=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                $hashpass = $row->MatKhau;
            }
            
            // Verify password
            if ($password === $hashpass) {
                $_SESSION['userlogin'] = $username;
                $_SESSION['pass'] = $password;
                $response['status'] = 'success';
                $response['message'] = 'Login successful';
                $response['redirect'] = 'index.php'; // Redirect URL
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

// Output response as JSON
echo json_encode($response);
?>