<?php
// Lấy dữ liệu được gửi từ trình duyệt
$data = json_decode(file_get_contents('php://input'), true);

// Kết nối tới cơ sở dữ liệu
$servername = "localhost";
$username = "LamVanHung";
$password = "0908";
$dbname = "qlns";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lưu dữ liệu vào cơ sở dữ liệu
$faceDescriptors = $data['faceDescriptors']; // Dữ liệu mảng faceDescriptors từ JavaScript

// Bạn cần thực hiện việc lưu dữ liệu vào cơ sở dữ liệu ở đây, ví dụ:
$sql = "INSERT INTO khuonmat (MaND, mota) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $label, $mota);

foreach ($faceDescriptors as $faceDescriptor) {
    $label = $faceDescriptor['label'];
    $mota = json_encode($faceDescriptor['descriptors']);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// Trả về kết quả cho trình duyệt
$response = array("success" => true);
echo json_encode($response);
?>
