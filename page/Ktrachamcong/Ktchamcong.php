<?php
    // Ngày mặc định là ngày hiện tại
    $ngayBatDau = date("Y-m-d");
    $ngayKetThuc = date("Y-m-d");

    // Nếu có yêu cầu POST từ form chọn ngày
    if(isset($_POST['submit'])){
        $ngayBatDau = $_POST['ngayBatDau'];
        $ngayKetThuc = $_POST['ngayKetThuc'];
    }

    try {
        // Chuẩn bị câu truy vấn SQL
        $sql = "SELECT *
                FROM bangchamcong AS bcc
                INNER JOIN nguoidung AS nd ON nd.MaND = bcc.MaND
                WHERE bcc.Ngay BETWEEN :ngayBatDau AND :ngayKetThuc
                ORDER BY  bcc.Ngay DESC,MaBCC DESC";

        // Chuẩn bị và thực thi truy vấn
        $lnp = $dbh->prepare($sql);
        $lnp->bindParam(':ngayBatDau', $ngayBatDau);
        $lnp->bindParam(':ngayKetThuc', $ngayKetThuc);
        $lnp->execute();

        // Lấy kết quả trả về
        $result = $lnp->fetchAll(PDO::FETCH_ASSOC);

        // Bắt đầu bảng HTML
        echo "<div class='dsnp'>";
        echo "<h2 class='head'>DANH SÁCH CHẤM CÔNG</h2>";
        echo "<form method='post' class='locngay'>";
        // Phần chọn ngày
        echo "<label for='ngayBatDau'>Từ ngày:</label>";
        echo "<input type='date' id='ngayBatDau' name='ngayBatDau' value='$ngayBatDau'>";
        echo "<label for='ngayKetThuc' class='denngay'> Đến ngày:  </label>";
        echo "<input type='date' id='ngayKetThuc' name='ngayKetThuc' value='$ngayKetThuc'>";
        echo "<input type='submit' name='submit' value='Tìm' class='loc'>";
        echo "</form>";
        echo "<form method='post'>";
        echo "<table border='1' class='ds'>";
        echo "<tr><th>STT</th><th>Họ và Tên</th><th>Ngày</th><th>Giờ bắt đầu</th><th>Giờ kết thúc</th><th>Thao tác</th></tr>";
        $dem=1;
        // Lặp qua kết quả và hiển thị trong bảng
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $dem . "</td>";
            echo "<td>" . $row['HoVaTen'] . "</td>";
            echo "<td>" . $row['Ngay'] . "</td>";
            echo "<td>" . $row['GioCheckin'] . "</td>";
            echo "<td>" . $row['GioCheckout'] . "</td>";

            if($row['TrangThai']!='Đã duyệt'){
                echo '<td align="center"><button onclick="return confirm(\'Bạn có chắc muốn xóa không?\'); " type="submit" value="'.$row["MaBCC"].'" name="btnxoa" class="btnxoa">Xóa</button>
                <button  value="'.$row["MaBCC"].'" name="btnduyet" class="btnduyet">Duyệt</button></td>';
            }else{
                echo '<td><button class="btnduyet">Đã duyệt</button></td>';
            }
            echo "</tr>";
            $dem++;
        }
        // Kết thúc bảng HTML
        echo "</table>";
        echo "<form>";
        echo"<br>";
        echo "</div>";
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi xảy ra
        echo "Lỗi: " . $e->getMessage();
    }
include_once('xulyktcc.php');
?>
