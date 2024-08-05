<?php
session_start();
if(isset($_REQUEST['save'])){
    $thang = $_SESSION['thang'];
    $nam = $_SESSION['nam'];
    try {
        $sql = "INSERT INTO bangluong (MaND, Thang, Nam, TongGioLam, BHXH, LuongTheoGio, TongThuNhap, TongKhauTru, ThucNhan,TrangThai)
            SELECT 
            bcn.MaND, 
            :thang, 
            :nam,
            ROUND(SUM(Sogiolam), 1) AS TongGioLam,
            nd.BHXH,
            nd.LuongTheoGio,
            ROUND(nd.LuongTheoGio * SUM(Sogiolam), 0) AS TongThuNhap,
            ROUND((nd.LuongTheoGio * SUM(Sogiolam)) * 0.08, 0) AS TongKhauTru,
            ROUND((nd.LuongTheoGio * SUM(Sogiolam)) - ((nd.LuongTheoGio * SUM(Sogiolam)) * 0.08),0) AS ThucNhan,
            1
        FROM 
            bangchamcong bcn 
        JOIN 
            nguoidung nd ON nd.MaND = bcn.MaND
        WHERE
            MONTH(Ngay) = :thang AND YEAR(Ngay) = :nam
        GROUP BY 
            MaND, YEAR(Ngay), MONTH(Ngay)";

        $insertStmt = $dbh->prepare($sql);
        $insertStmt->bindParam(':thang', $thang);
        $insertStmt->bindParam(':nam', $nam);
        
        if ($insertStmt->execute()) {
            $dataSaved=true;
            echo '
            <div class="notification" id="notification">
                Lưu bảng lương thành công!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "' . API_URL . '/quanly.php?page=bangluong";
                }, 2000); 
            </script>';
        }
        
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi: " . $e->getMessage();
    }
   
    
}
?>
