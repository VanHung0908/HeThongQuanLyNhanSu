<?php
if(isset($_REQUEST['btnduyet'])){
    $MaBCC = $_REQUEST['btnduyet'];
    $sl = "SELECT * FROM bangchamcong WHERE MaBCC='$MaBCC'";
    
    if ($sl) {
        $SG = $dbh->prepare($sl);
        $SG->execute();
        $result = $SG->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as $row) {
            $gio_sau = strtotime($row['GioCheckout']);
            $gio_truoc = strtotime($row['GioCheckin']);
            $ngay_checkin = $row['Ngay'];
            $sogio = ($gio_sau - $gio_truoc) / 3600;
            
            // Lấy ngày check-in theo định dạng 'd-m'
            $ngay_checkin = date('d-m', strtotime($ngay_checkin));
            
            // Kiểm tra nếu ngày check-in là ngày lễ
            if ($ngay_checkin == '01-01' || $ngay_checkin == '30-04' || $ngay_checkin == '01-05' || $ngay_checkin == '02-09') {
                $sogio *= 3;
            }
            
            $sogio = number_format($sogio, 1);
            $sql = "UPDATE bangchamcong SET TrangThai='Đã duyệt', Sogiolam='$sogio' WHERE MaBCC='$MaBCC'";
            $dbh->exec($sql);
            echo '
            <div class="notification" id="notification">
                Duyệt chấm công thành công!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "' . API_URL . '/quanly.php?page=kiemtrachamcong";
                }, 2000); 
            </script>';
        }
    }
}

if (isset($_REQUEST['btnxoa'])) {
    $MaBCC = $_REQUEST['btnxoa'];
    $sql = "DELETE FROM bangchamcong WHERE MaBCC='$MaBCC'";
    $dbh->exec($sql);
    echo '
    <div class="notification" id="notification">
        Xóa chấm công thành công!
    </div>
    <script>
        setTimeout(function(){
            window.location.href = "' . API_URL . '/quanly.php?page=kiemtrachamcong";
        }, 2000); 
    </script>';
}
$dbh = null;
?>
