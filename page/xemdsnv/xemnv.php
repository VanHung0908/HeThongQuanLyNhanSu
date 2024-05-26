<?php

try {

    // Chuẩn bị câu truy vấn SQL
    $sql = "SELECT nd.*, cv.TenCV
            FROM nguoidung AS nd
            INNER JOIN chucvu AS cv ON nd.MaCV = cv.MaCV
            
             ";

    // Thêm điều kiện lọc dựa vào lựa chọn của người dùng
    if(isset($_POST['filter'])) {
        if($_POST['status'] == 'active') {
            $sql .= "WHERE NgayKetThuc = '0000-00-00'";
        } elseif($_POST['status'] == 'inactive') {
            $sql .= "WHERE NgayKetThuc != '0000-00-00'";
        }elseif($_POST['status'] == 'account') {
            $sql = "select *,tk.MaND from taikhoan tk join nguoidung nd on tk.MaND=nd.MaND group by tk.MaND  ";
        }
    }else{
        $sql .= "WHERE NgayKetThuc = '0000-00-00'";
    }

    // Thực hiện truy vấn
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Bắt đầu bảng HTML
    echo "<div class='dsnp'>";
    echo "<h2 class='head'>DANH SÁCH NHÂN VIÊN</h2>";
    echo "<form method='POST'>";
    echo "<table border='1' class='dsnv'>";
    echo '
    <form method="POST" style="margin-bottom: 10px;">
        <label  class="nhanvien">
            <input type="radio" name="status" value="active" checked> Nhân viên đang làm
        </label>
        <label class="nhanvien">
            <input type="radio" name="status" value="inactive"> Nhân viên đã nghỉ
        </label>
        <label class="nhanvien">
        <input type="radio" name="status" value="account"> Tài khoản nhân viên
    </label>
        <input type="submit" name="filter" value="Tìm" class="loc">
    </form>
    ';
     if(isset($_POST['status']) && $_POST['status'] == 'account'){
        echo "<tr>
        <th>STT</th>
        <th>Họ và Tên</th>
        <th>Tên đăng nhập</th>
        <th>Create at</th>
        </tr>";
        $dem=1;
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $dem. "</td>";
            echo "<td>" . $row['HoVaTen'] . "</td>";
            echo "<td>" . $row['TenDangNhap'] . "</td>";
            echo "<td>" . $row['Last_login'] . "</td>";
            $dem++;
        }
        echo "</table>";
        echo "</form> <br>";
        echo "</div>";
     }else{
        echo "<tr>
        <th>STT</th>
        <th>Họ và Tên</th>
        <th>Ngày Sinh</th>
        <th>Giới Tính</th>
        <th>Số Điện Thoại</th>
        <th>Email</th>
        <th>Số Tài Khoản</th>
        <th>Chức Vụ</th>
        <th>Thao tác</th>
    </tr>";

    // Lặp qua kết quả và hiển thị trong bảng
    $dem=1;
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $dem. "</td>";
        echo "<td>" . $row['HoVaTen'] . "</td>";
        echo "<td>" . $row['NgaySinh'] . "</td>";
        echo "<td>" . $row['GioiTinh'] . "</td>";
        echo "<td>" . $row['Sdt'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Stk'] . "</td>";
        echo "<td>" . $row['TenCV'] . "</td>";
        $dem++;
        if($row['NgayKetThuc'] == '0000-00-00') {
                echo '<td><button onclick="return confirm(\'Bạn có chắc muốn xóa nhân viên này không?\');" type="submit" value="'.$row["MaND"].'" name="btnxoa" class="btnxoa">Xóa</button>';
        } else {
        }
        
        echo "</tr>";
    }

    // Kết thúc bảng HTML
    echo "</table>";
    echo "</form>";
    
    echo '
    <br>
    <div class="container">
    <div class="row">
    <div class="col-md-11 text-right">
    <button type="button" class="btnthemnv btn-primary" data-toggle="modal" data-target="#themNhanVienModal" >
    Thêm nhân viên
    </button>
    </div>
    </div>
    <br>
    </div>';
    echo "</div>";
     }

} catch (PDOException $e) {
    // Xử lý ngoại lệ nếu có lỗi xảy ra
    echo "Lỗi: " . $e->getMessage();
}
include_once('modelthemnv.php');
include_once('themnv.php');
include_once('xoanv.php');
?>
