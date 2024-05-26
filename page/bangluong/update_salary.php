<?php
if (isset($_POST['update'])) {
    $MaND=$_POST['MaND'];
    $hslnew = $_POST['hslnew'];
    $thangupdate = $_POST['thangupdate'];

    try {
        // Cập nhật hệ số lương mới vào cơ sở dữ liệu
        // Code cập nhật hệ số lương ở đây...

        // Kiểm tra và cập nhật hệ số lương ban đầu nếu tháng cập nhật là tháng sau tháng hiện tại
        $currentMonth = date('m');
        if ($thangupdate == $currentMonth) {
            // Thực hiện cập nhật hệ số lương ban đầu thành hệ số lương mới
            $sql = "UPDATE nguoidung AS nd
            INNER JOIN bangchamcong AS cc ON nd.MaND = cc.MaND
            SET nd.luongtheogio = $hslnew
            WHERE nd.MaND='$MaND' AND MONTH(cc.Ngay) = $thangupdate";
            $dbh->exec($sql);
            echo '
            <div class="notification" id="notification">
                Cập nhật lương theo giờ thành công!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=bangluong";
                }, 3000); 
            </script>';
            exit();
        }

        // Redirect hoặc hiển thị thông báo thành công...
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi: " . $e->getMessage();
    }
}
?>
