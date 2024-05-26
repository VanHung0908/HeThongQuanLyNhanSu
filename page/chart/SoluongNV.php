<?php
$sql = "SELECT COUNT(*) AS total, 
'Đang làm việc' AS status 
FROM NguoiDung 
WHERE NgayKetThuc = '0000-00-00'";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dữ liệu cho biểu đồ
$data = array();
foreach ($result as $row) {
 $row['total'];
 echo '
 <div class="chart-container">
 <div class="chart">
     <h4>Số lượng nhân viên.</h4>
 </div>
 <div class="chartso">
     <b>'.$row['total'].'</b>
     <b><i class="la la-user"></i></b>
 </div>
</div>

 ';
}
?>
