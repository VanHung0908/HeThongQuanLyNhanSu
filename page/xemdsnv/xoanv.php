<?php
if(isset($_REQUEST['btnxoa'])){
        $MaND=$_REQUEST['btnxoa'];
        try{
            $ngayKetThuc = date("Y-m-d");
            $sql="update nguoidung set NgayKetThuc=  '$ngayKetThuc' where MaND=$MaND";
            $sqli="delete from taikhoan where MaND=$MaND";
            $result = $dbh->exec($sql);
            $result = $dbh->exec($sqli);
            echo '
                <div class="notification" id="notification">
                Xóa nhân viên thành công!
                </div>
                <script>
                    setTimeout(function(){
                        window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=xemdsnv";
                    }, 2000); 
                </script>';
        }catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo "Lỗi: " . $e->getMessage();
        }
    }
?>