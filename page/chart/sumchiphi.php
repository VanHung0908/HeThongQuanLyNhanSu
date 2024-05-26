<?php
$current_month = date('n');
$current_year = date('Y');

// Câu truy vấn SQL
$sql = "SELECT 
            ROUND(SUM(Sogiolam * nd.LuongTheoGio), 0) AS TongThuNhap
        FROM 
            bangchamcong bcn 
        JOIN 
            nguoidung nd ON nd.MaND = bcn.MaND
        WHERE
            MONTH(Ngay) = $current_month AND YEAR(Ngay) = $current_year";

$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_income = 0;
if (!empty($result)) {
    $total_income = $result[0]['TongThuNhap'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Total Income Chart</title>
    <!-- Include thư viện Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <div style="width: 80%;margin: auto;">
        <!-- Đặt canvas cho biểu đồ -->
        <canvas id="bieudo"></canvas>
    </div>

    <script>
        // Dữ liệu tổng thu nhập
        var total_income = <?php echo $total_income; ?>;

        // Tạo biểu đồ cột
        var ctx = document.getElementById('bieudo').getContext('2d');
        var bieudo = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [''],
                datasets: [{
                    label: 'Total Income',
                    data: [total_income],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
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
                        text: 'Tổng Chi Phí Trong Tháng'
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