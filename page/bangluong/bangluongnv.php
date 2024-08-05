<?php
 include_once('xulyluuBL.php');
  $thang = date('m');
  $nam = date('Y');
if (isset($_POST['submit'])) {
    $_SESSION['thang'] = $thang =$_POST['thang'];
    $_SESSION['nam']=$nam = $_POST['nam']; 
}
    try {
        // Chuẩn bị câu truy vấn SQL
        $sql = "
            SELECT 
            HoVaTen,
            bcn.MaND, 
            MONTH(Ngay) AS Thang, 
            YEAR(Ngay) AS Nam,
            ROUND(SUM(Sogiolam), 1) AS TongGioLam,
            nd.BHXH,
            nd.LuongTheoGio,
            ROUND(nd.LuongTheoGio * SUM(Sogiolam), 0) AS TongThuNhap,
            ROUND((nd.LuongTheoGio * SUM(Sogiolam)) * 0.08, 0) AS TongKhauTru,
            ROUND((nd.LuongTheoGio * SUM(Sogiolam)) - ((nd.LuongTheoGio * SUM(Sogiolam)) * 0.08),0) AS ThucNhan
        FROM 
            bangchamcong bcn 
        JOIN 
            nguoidung nd ON nd.MaND = bcn.MaND
        WHERE
            MONTH(Ngay) = :thang AND YEAR(Ngay) = :nam
        GROUP BY 
            MaND, YEAR(Ngay), MONTH(Ngay)
        ORDER BY 
            Nam DESC, Thang DESC, HoVaTen;
        ";
        // Chuẩn bị và thực thi truy vấn
        $bluong = $dbh->prepare($sql);
        $bluong->bindParam(':thang', $thang);
        $bluong->bindParam(':nam', $nam);
        $bluong->execute();
        // Lấy kết quả trả về
        $result = $bluong->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class='dsnp'>";
        echo "<div  class='head'>
        <h2>BẢNG LƯƠNG NHÂN VIÊN </h2> 
        <h3> Tháng: $thang Năm : $nam </h3>
        </div>";
        echo "
        <form method='post' class='formtim'>
            <label for='thang'>Tháng:</label>
            <input type='number'  name='thang' min='1' max='12' value='$thang' ' >
            <label for='nam'>Năm:</label>
            <input type='number' name='nam' min='1900' max='2099' value='$nam' ' >
            <input type='submit' name='submit' value='Tìm kiếm'  class='timkiem'>
        </form>
        ";
        if (!empty($result)) {
                echo "<form method='post' class='formbl' >";
                echo "<table border='1' class='ds'>";
                echo "<tr>
                    <th>STT</th>
                    <th>Họ và Tên</th>
                    <th>Tháng</th>
                    <th>Năm</th>
                    <th>Tổng giờ làm</th>
                    <th>Lương theo giờ</th>
                    <th>BHXH</th>
                    <th>Tổng thu nhập</th>
                    <th>Tổng khấu trừ</th>
                    <th>Thực nhận</th>
                    <th>Thao tác</th>
                </tr>";
                $dem=1;
                // Lặp qua kết quả và hiển thị trong bảng
                foreach ($result as $row) {
                    $MaND=$row['MaND'];
                    $TongGioLam=$row['TongGioLam'];
                    $LuongTheoGio = number_format($row['LuongTheoGio'],0,',','.');
                    $BHXH = $row['BHXH'];
                    $TongThuNhap=number_format($row['TongThuNhap'],0,',','.');
                    $TongKhauTru=number_format($row['TongKhauTru'],0,',','.');
                    $TongThucNhan=number_format($row['ThucNhan'],0,',','.');
                    echo "<tr>";
                    echo "<td>" . $dem . "</td>";
                    echo "<td>" . $row['HoVaTen'] . "</td>";
                    echo "<td>" .$thang. "</td>";
                    echo "<td>" .$nam. "</td>";
                    echo "<td>" . $TongGioLam. "</td>";
                    echo "<td>" . $LuongTheoGio . "</td>";
                    echo "<td>" . $BHXH . "</td>";
                    echo "<td>" . $TongThuNhap . "</td>";
                    echo "<td>" . $TongKhauTru . "</td>";
                    echo "<td>" . $TongThucNhan . "</td>";
                    echo '<td><button  type="button" class="btncapnhat" data-toggle="modal" data-target="#capnhat" value="'.$row["MaND"].'" >Cập nhật</button></td>';
                    echo "</tr>";
                    $dem++;}
                    echo "</table>";
                    // Lấy tháng hiện tại
                    $currentMonth = date('m');
                    // Lấy tháng muốn lưu từ biến $thang
                    $selectedMonth = $thang;
                    
                    // Kiểm tra xem tháng hiện tại có phải là tháng trước tháng muốn lưu không
                    if ($selectedMonth < $currentMonth) {
                        // Kiểm tra xem có dữ liệu trong bảng bangluong cho tháng và năm đang xem xét hay không
                        $sql = "SELECT COUNT(*) AS count FROM bangluong WHERE Thang = $thang AND Nam = $nam";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $count = $row['count'];
                    
                        // Nếu có dữ liệu, kiểm tra trạng thái và hiển thị nút tương ứng
                        if ($count > 0) {
                            $sql = "SELECT trangThai FROM bangluong WHERE Thang = $thang AND Nam = $nam";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $trangThai = $row['trangThai'];
                    
                            // Hiển thị nút tùy thuộc vào trạng thái
                            if ($trangThai == 1) {
                                echo "<button type='submit' name='save' class='btnsave' disabled>Đã Lưu</button>";
                            } else {
                                echo "<button type='submit' name='save' class='btnsave' >Lưu</button>";
                            }
                        } else {
                            // Nếu không có dữ liệu, hiển thị nút "Lưu" cho phép người dùng lưu dữ liệu mới
                            echo "<button type='submit' name='save' class='btnsave'>Lưu</button>";
                        }
                    } else {
                        // Nếu không phải tháng trước, không hiển thị nút "Lưu"
                        // echo "<button type='submit' name='save' class='btnsave' style='background-color: #CCC; color:black' disabled>Lưu</button>";
                    }
                } else {
                    echo "<p>Hiện chưa có thông tin.</p>";
                }
                    echo "<br>
                    </form>
                    <br>
                    ";
                    } catch (PDOException $e) {
                        // Xử lý ngoại lệ nếu có lỗi xảy ra
                        echo "Lỗi: " . $e->getMessage();
                    }
                    include_once('update_salary.php');
    ?>

<div class="modal fade" id="capnhat" tabindex="-1" role="dialog" aria-labelledby="capnhatLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="capnhatLabel">CẬP NHẬT LƯƠNG THEO GIỜ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
      </div>
      <form method="post"  onsubmit="return validateMonth();"> 
        <input type="hidden" name="MaND" id="MaND_input" value="">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="hslnew" class="col-md-3 col-form-label">Lương theo giờ mới:</label>
                <div class="col-md-9">
                    <input type="number" name="hslnew" id="hslnew" class="form-control" required  >
                </div>
            </div>
            <div class="form-group row">
                <label for="thangupdate" class="col-md-3 col-form-label">Tháng cần cập nhật:</label>
                <div class="col-md-9">
                    <input type="number" name="thangupdate" id="thangupdate" class="form-control" required value="<?php echo date('m'); ?>">
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <input type="submit" name="update" class="btn btn-primary"  value="Cập nhật">
            </div> 
        </form>
        <br>
    </div>
</div>
<script>
    // Lắng nghe sự kiện click trên nút button
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.getElementsByClassName('btncapnhat');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function() {
                // Lấy giá trị của thuộc tính value từ nút button được nhấn
                var MaND_value = this.getAttribute('value');
                // Gán giá trị vào input hidden
                document.getElementById('MaND_input').value = MaND_value;
            });
        }
    });
    function validateMonth() {
        var currentMonth = new Date().getMonth() + 1; // Lấy tháng hiện tại (tính từ 0)
        var selectedMonth = document.getElementById("thangupdate").value;
        
        if (parseInt(selectedMonth) < currentMonth) {
            Swal.fire({
            icon: 'error',
            title: 'Cập nhật không thành công',
            text: 'Tháng cập nhật không được nhỏ hơn tháng hiện tại!',
            confirmButtonText: 'OK'
            });
            return false; // Ngăn chặn việc submit form nếu thỏa điều kiện
        }
        if (parseInt(selectedMonth) > currentMonth) {
            Swal.fire({
            icon: 'error',
            title: 'Cập nhật không thành công',
            text: 'Tháng cập nhật không được lớn hơn tháng hiện tại!',
            confirmButtonText: 'OK'
            });
            return false; // Ngăn chặn việc submit form nếu thỏa điều kiện
        }
        return true; // Cho phép submit form nếu không có lỗi
    }
   
</script>
