<?php
session_start();
include_once 'includes/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day = $_POST['date'];
    $weekday = $_POST['weekday'];
    $ca = $_POST['ca'];
    $note = $_POST['note'];
    $username = $_SESSION['hoten'];
    // Đổi định dạng của ngày từ "yyyy mm dd" sang "yyyy-mm-dd"
    $day_parts = explode('/', $day); // Tách ngày thành mảng các phần tử
    $day_formatted = $day_parts[2] . '-' . $day_parts[1] . '-' . $day_parts[0];
    // In ra dữ liệu đã thay đổi
    try {
        // Chuẩn bị câu truy vấn SQL
        $sql = "INSERT INTO lichlamviec (Ngay,CaLam,TrangThai,GhiChu,NhanVien) 
        VALUES (' $day_formatted','$ca','Chờ duyệt','$note','$username' )";

        // Thực hiện insert
        $dbh->exec($sql);
 
        // Redirect về trang chính sau khi thêm thành công
        echo "<script>
        Swal.fire({
        icon: 'success',
        title: 'Đăng ký thành công',
        confirmButtonText: 'OK'
        });
        </script>";
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi: " . $e->getMessage();
    }
}

// Đóng kết nối PDO
$dbh = null;
?>


