<?php
$MaND=$_SESSION['MaND'];
$hoten=$_SESSION['hoten'];
    try{
        $sql ="select * from nguoidung nd join chucvu cv on nd.MaCV=cv.MaCV where MaND='$MaND'";
        $ds=$dbh->prepare($sql);
        $ds->execute();
        $result= $ds->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
        echo ' <div class="TTCN">';
        echo '
        <div class="left">
            <img src="./assets/img/profiles/'.$row['IMG'].'"class="avata"/>
            <h4>'.$hoten.'</h4>
            <form  method="post">
            <button class="btntd" value="" name="btndmk">Đổi Mật Khẩu</button>
            </form>
        </div>
        ';
        if(isset($_REQUEST['btnluu'])){
            $mkcu=md5($_REQUEST['mkcu']);
            $mkmoi=md5($_REQUEST['mkmoi']);
            $mknhaplai=md5($_REQUEST['mknhaplai']);
        if($mkcu!==$_SESSION['pass']){
            echo '
            <div class="err" >
                Mật khẩu cũ không đúng!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/canhan.php";
                }, 2000); 
            </script>';
            exit; // Kết thúc chương trình nếu mật khẩu cũ không đúng
        }
        if ($mkmoi !== $mknhaplai) {
            echo'
             <div class="err" >
                 Mật khẩu mới và nhập lại mật khẩu không khớp!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/canhan.php";
                }, 2000); 
            </script>';
            exit; 
            }
            // Kiểm tra xem mật khẩu mới có giống với mật khẩu cũ không
            if ($mkmoi === $mkcu) {
                echo '
                <div class="err" >
                Mật khẩu mới không được trùng với mật khẩu cũ!
                </div>
                <script>
                    setTimeout(function(){
                        window.location.href = "http://localhost:8080/CNMoi/QLNS/canhan.php";
                    }, 2000); 
                </script>';
                exit; // Kết thúc chương trình nếu mật khẩu mới trùng với mật khẩu cũ
            }
            $sql="update taikhoan set MatKhau='$mkmoi' where MaND='$MaND'";
            $dbh->exec($sql);
            echo '
            <div class="notification" >
               Thay đổi mật khẩu mới thành công!
            </div>
            <script>
                setTimeout(function(){
                    window.location.href = "http://localhost:8080/CNMoi/QLNS/login.php";
                }, 2000); 
            </script>';
        }   
        echo '
        <div class="right">';
            if(isset($_REQUEST['btndmk'])){
                echo '
                <form  method="post">
                <table>
                    <tr>
                        <td colspan="2"><h2>Thay đổi mật khẩu</h2></td>
                    </tr>
                    <tr>
                    <td>Nhập mật khẩu cũ: </td>
                        <td><input type="password" class="iput" name="mkcu" required ></td>
                    </tr>
                    <tr>
                    <td>Nhập mật khẩu mới: </td>
                        <td><input type="password" class="iput" name="mkmoi" required ></td>
                    </tr>
                    <td>Nhập lại mật khẩu : </td>
                        <td><input type="password" class="iput" name="mknhaplai" required ></td>
                    </tr>
                </table>
                <input type="submit" value="Lưu " class="btnluu" name="btnluu">
                </form>
                ';
                
            }else{
                echo'
                <form  method="post">
                <table>
                    <tr>
                        <td colspan="2"><h2>Thông tin cá nhân</h2></td>
                    </tr>
                    <tr>
                        <td>Họ và tên: </td>
                        <td><input type="text" name="HoVaTen"  readonly  class="iput"  value="'.$row['HoVaTen'].'"></td>
                    </tr>
                    <tr>
                        <td>Giới tính: </td>
                        <td><input type="text"name="gioitinh"  readonly class="iput"  value="'.$row['GioiTinh'].'"></td>
                    </tr>
                    <tr>
                        <td>Ngày sinh:  </td>
                        <td><input type="date" name="ngaysinh"  readonly class="date" value="'.$row['NgaySinh'].'"></td>
                    </tr>
                    <tr>
                        <td>Chức vụ: </td>
                        <td><input type="text" class="iput" readonly  value="'.$row['TenCV'].'"></td>
                    </tr>
                    <tr>
                    <td>Ngày bắt đầu: </td>
                        <td><input type="date" class="iput" readonly  value="'.$row['NgayBatDau'].'"></td>
                    </tr>   
                    <tr>
                        <td>Số điện thoại: </td>
                        <td><input type="number" name="Sdt" class="iput"  value="'.$row['Sdt'].'"></td>
                    </tr>
                     <tr>
                        <td>Email: </td>
                        <td><input type="mail" name="Email" class="iput"  value="'.$row['Email'].'"></td>
                    </tr>
                    <tr>
                        <td>Số tài khoản: </td>
                        <td><input type="number" name="Stk" class="iput"  value="'.$row['Stk'].'"></td>
                    </tr>   
                    <tr>
                        <td>Địa chỉ: </td>
                        <td><input type="text" name="DiaChi" class="iput"   value="'.$row['DiaChi'].'"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td> <button class="btncntt" value="" name="btncntt">Cập nhật</button></td>
                    </tr>  
                </table>
                </form>
                
                ';
            }
        echo '</div>';
        echo '</div>';
        include_once('updatett.php');
    }

    }catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo "Lỗi: " . $e->getMessage();
        }
?>