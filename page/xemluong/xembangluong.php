<?php
    $thang = date('m')-1;
    $nam = date('Y');
    $MaND=$_SESSION['MaND'];
    if(isset($_POST['submit'])){
        $thang= $_POST['thang'];
        $nam = $_POST['nam'];
    }

    try {
        // Chuẩn bị câu truy vấn SQL
        $sql = "Select * from bangluong bl 
                join nguoidung nd 
                    on bl.MaND=nd.MaND
                join chucvu cv
                    on cv.MaCV=nd.MaCV
                where bl.MaND=$MaND AND thang= :thang And nam = :nam
                ";

        // Chuẩn bị và thực thi truy vấn
        $xemluong = $dbh->prepare($sql);
        $xemluong->bindParam(':thang', $thang);
        $xemluong->bindParam(':nam', $nam);
        $xemluong->execute();

        // Lấy kết quả trả về
        $result = $xemluong->fetchAll(PDO::FETCH_ASSOC);

        // Bắt đầu bảng HTML
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
        echo "<div>";
        echo "<form method='post' >";
        echo "<table border='1' class='bodyluong'>";
            foreach ($result as $row) {
                echo "
                <tr >
                    <td colspan=2><p><strong>Họ và Tên:</strong> " . $row['HoVaTen'] . "</p></td>
                </tr>
                <tr >
                    <td colspan=2><p><strong>Tháng </strong>" . $thang . " <strong>Năm:</strong>  " . $nam . "</p></td>
                </tr>
                <tr >
                <td colspan=2><p><strong>Chức vụ:</strong> " . $row['TenCV'] . "</p></td>
                </tr>   
                <tr>
                    <th>Lương</th>
                    <th>Khấu trừ</th>
                </tr>
                <tr>
                    <td>Lương theo giờ: " . number_format($row['LuongTheoGio'], 0, ',', '.') . "</td>
                    <td> BHXH:" . $row['BHXH'] . " </td>
                </tr>
                <tr>
                    <td>Tổng giờ làm: " . $row['TongGioLam'] . "</td>
                    <td></td>
                </tr>
                <tr>
                    <td> <strong>Tổng thu nhập:</strong> " . number_format($row['TongThuNhap'], 0, ',', '.') . "</td>
                    <td><strong>Tổng khấu trừ:</strong> " . number_format($row['TongKhauTru'], 0, ',', '.') . "</td>
                </tr>
                <tr>
                    <th colspan=2>
                    <p><strong>Thực nhận:</strong>" . number_format($row['ThucNhan'], 0, ',', '.') . " VND</p>
                    </th>
                </tr>
                <tr class = 'none'>
                <td colspan=2><br></td></tr>
                ";
            }
        } else {
            echo "<p>Hiện chưa có thông tin.</p>";
        }
        echo "<form>";
        echo "</div>";
        echo "</div>";
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi: " . $e->getMessage();
    }
?>
