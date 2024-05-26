<?php
// Kiểm tra nếu người dùng đã nhập năm và tồn tại trong request'
$currentYear = date("Y");
if(isset($_GET['year'])) {
    $selectedYear = $_GET['year'];
    // Truy vấn SQL lấy dữ liệu theo năm
    $sql = "SELECT Thang, SUM(TongThuNhap) AS TongThuNhap FROM BangLuong WHERE Nam = :year GROUP BY Thang";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = array();
    foreach ($result as $row)  {
        $data[$row['Thang']] = $row['TongThuNhap'];
    }
} else {
    // Nếu không có năm được chọn, mặc định lấy năm hiện tại
    $sql = "SELECT Thang, SUM(TongThuNhap) AS TongThuNhap FROM BangLuong WHERE Nam = :currentYear GROUP BY Thang";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = array();
    foreach ($result as $row)  {
        $data[$row['Thang']] = $row['TongThuNhap'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Column Chart</title>
    <!-- Include thư viện Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <!-- Input nhập năm -->
        <form method="get" class="search">
            <input type="number" name="year" id="year" placeholder="Nhập năm...">
            <!-- Nút tìm kiếm -->
            <button type="submit" class='loc'>Tìm</button>
        </form>
        <!-- Đặt canvas cho biểu đồ -->
        <canvas id="chart"></canvas>
    </div>

    <script>
        // Lấy dữ liệu từ PHP và tạo biểu đồ
        var data = <?php echo json_encode($data); ?>;
        var labels = Object.keys(data);
        var values = Object.values(data);
        
        // Mảng màu sắc cho các cột
        var colors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
        ];

        // Tạo biểu đồ cột
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tổng Chi Phí',
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Tổng Chi Phí Theo Tháng'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
