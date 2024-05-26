<?php
$sql = "SELECT COUNT(*) AS total, 
CASE 
    WHEN NgayKetThuc = '0000-00-00' THEN 'Đang làm việc' 
    ELSE 'Đã nghỉ việc' 
END AS status 
FROM NguoiDung 
GROUP BY 
CASE 
    WHEN NgayKetThuc = '0000-00-00' THEN 'Đang làm việc' 
    ELSE 'Đã nghỉ việc' 
END";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dữ liệu cho biểu đồ
$data = array();
foreach ($result as $row) {
$data[$row['status']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pie Chart</title>
<!-- Include thư viện Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
<div style="width: 60%; margin: auto;">
<!-- Đặt canvas cho biểu đồ -->
<canvas id="myChart"></canvas>
</div>

<script>
// Lấy dữ liệu từ PHP và tạo biểu đồ
var data = <?php echo json_encode($data); ?>;
var labels = Object.keys(data);
var values = Object.values(data);

// Tạo biểu đồ tròn
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
type: 'pie',
data: {
 labels: labels,
 datasets: [{
     label: 'Employee Status',
     data: values,
     backgroundColor: [
         'rgba(255, 99, 132, 0.7)',
         'rgba(54, 162, 235, 0.7)',
     ],
     borderWidth: 1
 }]
},
options: {
 responsive: true,
 plugins: {
     legend: {
         position: 'top',
     },
     title: {
         display: true,
         text: 'Employee Status'
     }
 }
}
});
</script>
</body>
</html>