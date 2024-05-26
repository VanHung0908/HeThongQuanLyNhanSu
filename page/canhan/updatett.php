<?php
    if(isset($_REQUEST['btncntt'])){
        $MaND=$_SESSION['MaND'];
        $hoten=$_REQUEST['HoVaTen'];
        $gioitinh=$_REQUEST['gioitinh'];
        $NgaySinh=$_REQUEST['ngaysinh'];
        $Sdt=$_REQUEST['Sdt'];
        $Stk=$_REQUEST['Stk'];
        $Email=$_REQUEST['Email'];
        $DiaChi=$_REQUEST['DiaChi'];

        try {
            // Chuẩn bị câu truy vấn SQL
            $sql = "update nguoidung set HoVaTen='$hoten',GioiTinh='$gioitinh',NgaySinh='$NgaySinh',Sdt='$Sdt',Email='$Email',Stk='$Stk',DiaChi='$DiaChi' where MaND='$MaND'";
            $dbh->exec($sql);
            echo '
            <div class="notification" >
               Cập nhật thông tin thành công!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=Canhan";
                }, 2000); 
            </script>';
           } catch (PDOException $e) {
               // Xử lý ngoại lệ nếu có lỗi xảy ra
               echo "Lỗi: " . $e->getMessage();
           }
    }
?>