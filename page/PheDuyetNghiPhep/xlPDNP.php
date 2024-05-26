<?php
    if(isset($_REQUEST['btnduyet'])){
        $MaLNP=$_REQUEST['btnduyet'];
        echo $MaLNP;
        $sql="update lichnghiphep set TrangThai='Đã duyệt' where MaLNP='$MaLNP'";
        $dbh->exec($sql);
        echo '
        <div class="notification" id="notification">
            Duyệt nghỉ phép thành công!
        </div>
        <script>
            setTimeout(function(){
                window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=PheduyetNP";
            }, 2000); 
        </script>';
    }
    if(isset($_REQUEST['btnxoa'])){
        $MaLNP=$_REQUEST['btnxoa'];
        $sql="delete from lichnghiphep where MaLNP='$MaLNP'";
        $dbh->exec($sql);
        echo '
        <div class="notification" id="notification">
             Từ chối phê duyệt thành công!
        </div>
        <script>
            setTimeout(function(){
                window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=PheduyetNP";
            }, 2000); 
        </script>';
    }
    $dbh=null;
?>