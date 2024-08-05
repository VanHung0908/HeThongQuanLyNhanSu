<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlns";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Thực hiện truy vấn để lấy dữ liệu huấn luyện từ cơ sở dữ liệu
$sql = "SELECT MaND, Mota FROM khuonmat";
$result = $conn->query($sql);

// Mảng để lưu trữ dữ liệu huấn luyện
$trainingData = array();

// Nếu có kết quả từ truy vấn
if ($result->num_rows > 0) {
    // Lặp qua từng dòng kết quả
    while($row = $result->fetch_assoc()) {
        // Trích xuất nhãn và các mô tả khuôn mặt từ dữ liệu và thêm vào mảng trainingData
        $label = $row["MaND"];
        $descriptors = json_decode($row["Mota"]);
        $trainingData[] = array(
            'label' => $label,  
            'descriptors' => $descriptors
        );
    }
}

// Trả về dữ liệu huấn luyện dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($trainingData);

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
