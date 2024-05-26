<?php
session_start();
include_once 'includes/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaLLV = $_POST['MaLLV'];
    try {
        // Chuẩn bị câu truy vấn SQL
        $sql = "delete FROM lichlamviec where MaLLV='$MaLLV'";
        $dbh->exec($sql);
       } catch (PDOException $e) {
           // Xử lý ngoại lệ nếu có lỗi xảy ra
           echo "Lỗi: " . $e->getMessage();
       }
}
// Đóng kết nối PDO
$dbh = null;
?>

