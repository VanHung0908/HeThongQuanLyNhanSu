<?php
// Kiểm tra xem có dữ liệu được gửi từ form không
if (isset($_POST['btn_themnv'])) {
    if(isset($_FILES['file'])){
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        $allowed_types = array("image/jpeg", "image/jpg", "image/png", "image/gif");

        $name = $_FILES["file"]["name"];
        $size = $_FILES["file"]["size"];
        $type = $_FILES["file"]["type"];
        $tmp_name = $_FILES["file"]["tmp_name"];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        if (in_array($type, $allowed_types) && in_array($ext, $allowed_ext)) {
            $name = pathinfo($name, PATHINFO_FILENAME);
            $newname = $name."_".rand(100,999).".".$ext;

            if(move_uploaded_file($tmp_name, "./assets/img/profiles/".$newname)){
                $img = ' <img src="./assets/img/profiles/'.$newname.'" alt="" width="200px">' ;
            }
            
            $hodem = $_POST['hodem'];
            $ten = $_POST['ten'];
            $hoten = $hodem . ' ' . $ten;
            $ngaysinh = $_POST['ngaysinh'];
            $gioitinh = $_POST['gt'];
            $diachi = $_POST['diachi'];
            $sdt = $_POST['sdt'];
            $stk = $_POST['stk'];
            $email = $_POST['email'];
            $NBD = $_POST['NBD'];
            $HeSoLuong = $_POST['HeSoLuong'];
            $BHXH = $_POST['BHXH'];
            $name = mb_strtolower($ten, 'UTF-8');

            $trans = array(
                'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
                'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
                'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
                'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
                'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
                'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
                'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
                'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
                'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
                'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
                'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
                'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
                'đ' => 'd'
            );

            // Thực hiện chuyển đổi
            $name = strtr($name, $trans);
            
            try {
                // Chuẩn bị câu truy vấn SQL
                $sql = "INSERT INTO nguoidung (HoVaTen, NgaySinh, GioiTinh, Sdt, Email, IMG, Stk, MaCV, DiaChi, Luongtheogio, BHXH, NgayBatDau)
                        VALUES ('$hoten', '$ngaysinh', '$gioitinh', '$sdt', '$email', '$newname', '$stk', 3, '$diachi', '$HeSoLuong', '$BHXH', '$NBD')";
                $sql .= "; INSERT INTO taikhoan (MaND, TenDangNhap, MatKhau) VALUES (LAST_INSERT_ID(), CONCAT('NV','$name', DATE_FORMAT(NOW(), '%d%H%i')), MD5('123'))";
                $result = $dbh->exec($sql);

                // Kiểm tra giá trị trả về của $result
                if ($result !== false) {
                    echo '
                    <div class="notification" id="notification">
                        Thêm nhân viên mới thành công!
                    </div>
                    <script>
                        setTimeout(function(){
                            window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=xemdsnv";
                        }, 2000);
                    </script>';
                } else {
                    echo '
                    <div class="err" id="notification">
                        Thêm nhân viên mới thất bại!
                    </div>
                    <script>
                        setTimeout(function(){
                            window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=xemdsnv";
                        }, 2000);
                    </script>';
                }
            } catch (PDOException $e) {
                // Xử lý ngoại lệ nếu có lỗi xảy ra
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo '
            <div class="err" id="notification">
                File không hợp lệ! Vui lòng tải lên file ảnh (jpg, jpeg, png, gif).
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/quanly.php?page=xemdsnv";
                }, 2000);
            </script>';
        }
    }
}
// Đóng kết nối PDO
?>
