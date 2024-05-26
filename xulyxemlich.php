<?php
session_start();
include_once 'includes/config.php';

try {
    // Chuẩn bị câu truy vấn SQL
    $sql = "SELECT MaLLV,Ngay,CaLam,TrangThai,GhiChu,NhanVien FROM lichlamviec where TrangThai='Đã duyệt' ";
    
    // Thực hiện truy vấn   
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
    // Lấy kết quả trả về
    $lichLamViec = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kiểm tra nếu có dữ liệu
    if ($lichLamViec) {
        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($lichLamViec);
    } else {
        // Trả về thông báo nếu không có dữ liệu
        echo json_encode(array('message' => 'Không có dữ liệu.'));
    }
} catch (PDOException $e) {
    // Xử lý ngoại lệ nếu có lỗi xảy ra
    echo json_encode(array('error' => 'Lỗi: ' . $e->getMessage()));
}
// Đóng kết nối PDO
$dbh = null;
?>
