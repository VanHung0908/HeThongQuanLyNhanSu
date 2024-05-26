<?php
include_once 'includes/config.php';
$data = json_decode(file_get_contents("php://input"));

// Chuẩn bị các giá trị để thêm vào cơ sở dữ liệu
$label = $data->label;
$time = $data->time;
$date = $data->date;

// Kiểm tra xem đã có bản ghi check-in cho người dùng với nhãn label này trong ngày hôm nay hay chưa
$sqlCheckIn = "SELECT * FROM bangchamcong WHERE MaND = :label AND Ngay = :date AND GioCheckout='00:00:00'";
$stmtCheckIn = $dbh->prepare($sqlCheckIn);
$stmtCheckIn->bindParam(':label', $label);
$stmtCheckIn->bindParam(':date', $date);
$stmtCheckIn->execute();
$checkInRecord = $stmtCheckIn->fetch(PDO::FETCH_ASSOC);

if ($checkInRecord) {
    // Nếu đã có bản ghi check-in, thực hiện cập nhật thời gian checkout
    $sqlUpdateCheckout = "UPDATE bangchamcong SET GioCheckout = :time WHERE MaND = :label AND Ngay = :date";
    $stmtUpdateCheckout = $dbh->prepare($sqlUpdateCheckout);
    $stmtUpdateCheckout->bindParam(':time', $time);
    $stmtUpdateCheckout->bindParam(':label', $label);
    $stmtUpdateCheckout->bindParam(':date', $date);
    
    if ($stmtUpdateCheckout->execute()) {
        echo "Thời gian checkout đã được cập nhật.";
    } else {
        echo "Lỗi khi cập nhật thời gian checkout.";
    }
} else {
    // Nếu chưa có bản ghi check-in, thực hiện insert bản ghi mới
    $sqlInsert = "INSERT INTO bangchamcong (MaND, GioCheckin, Ngay, TrangThai) VALUES (:label, :time, :date, 'Chờ duyệt')";
    $stmtInsert = $dbh->prepare($sqlInsert);
    $stmtInsert->bindParam(':label', $label);
    $stmtInsert->bindParam(':time', $time);
    $stmtInsert->bindParam(':date', $date);
    
    if ($stmtInsert->execute()) {
        echo "Dữ liệu đã được thêm vào bảng chấm công.";
    } else {
        echo "Lỗi khi thêm dữ liệu vào bảng chấm công.";
    }
}

// Đóng kết nối
$dbh = null;
?>
