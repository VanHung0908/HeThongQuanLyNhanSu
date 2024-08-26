<?php
session_start();
header('Content-Type: application/json');

include_once("../../includes/config.php");

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem MaND có được truyền vào qua POST không
    if (isset($_POST['mand'])) {
        $mand = $_POST['mand'];

        $sql = "SELECT * FROM NguoiDung WHERE MaND = :mand";
        $query = $dbh->prepare($sql);
        $query->bindParam(':mand', $mand, PDO::PARAM_INT);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if ($query->rowCount() > 0) {
            $response['status'] = 'success';
            $response['data'] = $result;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Không tìm thấy thông tin người dùng';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Thiếu thông tin MaND';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Yêu cầu không hợp lệ, vui lòng sử dụng phương thức POST';
}

echo json_encode($response);
?>
