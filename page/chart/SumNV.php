<?php
$current_month = date('n');
$current_year = date('Y');

// Câu truy vấn SQL
$sql = "SELECT 
            HoVaTen,
            ROUND(SUM(Sogiolam * nd.LuongTheoGio), 0) AS TongThuNhap
        FROM 
            bangchamcong bcn 
        JOIN 
            nguoidung nd ON nd.MaND = bcn.MaND
        WHERE
            MONTH(Ngay) = $current_month AND YEAR(Ngay) = $current_year
        GROUP BY 
            HoVaTen
        ORDER BY 
            TongThuNhap DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result_array = array();
    foreach ($result as $row) {
        $result_array[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donut Chart</title>
    <!-- Include thư viện Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <!-- Đặt canvas cho biểu đồ -->
        <canvas id="sumthang"></canvas>
    </div>

    <script>
        // Dữ liệu từ câu truy vấn SQL
        var data = <?php echo json_encode($result_array); ?>;

        // Lấy tên và tổng thu nhập từ dữ liệu
        var labels = data.map(function(item) {
            return item.HoVaTen;
        });

        var values = data.map(function(item) {
            return item.TongThuNhap;
        });

        // Tạo biểu đồ bánh Donut
        var ctx = document.getElementById('sumthang').getContext('2d');
        var sumthang = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tổng Thu Nhập',
                    data: values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Tổng Thu Nhập Của Nhân Viên Theo Tháng'
                    }
                }
            }
        });
    </script>
</body>
</html>