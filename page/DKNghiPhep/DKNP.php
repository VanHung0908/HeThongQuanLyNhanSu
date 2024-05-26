<div class="dknp">
    <form action="" method="post">
        <table class="bangdk">
            <h2 class="head">ĐĂNG KÝ NGHỈ PHÉP</h2>
            <tr>
                <td>Từ ngày</td>
                <td>Đến ngày</td>
            </tr>
            <tr>
               <td> <input type="date" name="ngaybatdau"id="ngaybatdau"></td>
               <td> <input type="date" name="ngayketthuc" id="ngayketthuc"></td>
            </tr>
            <tr>
            <td colspan="2">Lý do</td>
            </tr>
            <tr >
                <td colspan="2"> <textarea name="Lydo" cols="80" rows="10"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input onclick="reloadPage();" type="submit" value="Gửi duyệt" class="btngui" name='btngui'>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    // Lấy ngày hiện tại
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //Tháng bắt đầu từ 0
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    // Đặt ngày hiện tại vào trường "Từ ngày" và "Đến ngày"
    var ngayBatDauInput = document.getElementById("ngaybatdau");
    ngayBatDauInput.value = today;

    var ngayKetThucInput = document.getElementById("ngayketthuc");
    ngayKetThucInput.value = today;

    // Kiểm tra khi trường "Từ ngày" thay đổi
    ngayBatDauInput.addEventListener('change', function() {
        var selectedStartDate = new Date(this.value);
        var currentDate = new Date();

        // Kiểm tra nếu ngày bắt đầu nhỏ hơn ngày hiện tại
        if (selectedStartDate < currentDate) {
            alert("Ngày bắt đầu không thể nhỏ hơn ngày hiện tại.");
            this.value = today; // Đặt lại ngày thành ngày hiện tại
            return;
        }
    });

    // Kiểm tra khi trường "Đến ngày" thay đổi
    ngayKetThucInput.addEventListener('change', function() {
        var selectedStartDate = new Date(ngayBatDauInput.value);
        var selectedEndDate = new Date(this.value);

        // Kiểm tra nếu ngày kết thúc nhỏ hơn ngày bắt đầu
        if (selectedEndDate < selectedStartDate) {
            alert("Ngày kết thúc không thể nhỏ hơn ngày bắt đầu.");
            this.value = ngayBatDauInput.value; // Đặt lại ngày thành ngày bắt đầu
        }
    });
</script>
<?php
    include_once('../includes/config.php');
    if(isset($_REQUEST['btngui'])){
        $ngaybd=$_REQUEST['ngaybatdau'];
        $ngaykt=$_REQUEST['ngayketthuc'];
        $Lydo=$_REQUEST['Lydo'];
        $MaND=$_SESSION['MaND'];
        try{
            $sql="insert into lichnghiphep(NgayBatDau,NgayKetThuc,LyDo,MaND,TrangThai)
            values('$ngaybd',' $ngaykt','$Lydo','$MaND','Chờ duyệt')";
            $dbh->exec($sql);
            echo '
                <div class="notification" id="notification">
                Lịch nghỉ phép được thêm thành công!
                </div>  <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/nhanvien.php?page=dangkynghiphep";
                }, 2000);
            </script>';
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo "Lỗi: " . $e->getMessage();
        }
    }
    $dbh=null;
?>