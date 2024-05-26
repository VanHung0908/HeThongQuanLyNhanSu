<!-- Modal Thêm Nhân viên -->
<div class="modal fade" id="themNhanVienModal" tabindex="-1" role="dialog" aria-labelledby="themNhanVienModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="themNhanVienModalLabel">THÊM NHÂN VIÊN</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <!-- Form thêm nhân viên -->
<form method="post" id="formThemNhanVien" class="row" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="form-group row">
            <label for="hodem" class="col-md-3 col-form-label">Họ đệm:</label>
            <div class="col-md-9">
                <input type="text" name="hodem" id="hodem" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="ten" class="col-md-3 col-form-label">Tên:</label>
            <div class="col-md-9">
                <input type="text" name="ten" id="ten" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Giới Tính:</label>
            <div class="col-md-9">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gt" id="nam" value="Nam" checked>
                    <label class="form-check-label" for="nam">Nam</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gt" id="nu" value="Nữ">
                    <label class="form-check-label" for="nu">Nữ</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="ngaysinh" class="col-md-3 col-form-label">Ngày Sinh:</label>
            <div class="col-md-9">
                <input type="date" name="ngaysinh" id="ngaysinh" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Địa chỉ:</label>
            <div class="col-md-9">
                <input type="text" name="diachi" id="diachi" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="sdt" class="col-md-3 col-form-label">Số Điện Thoại:</label>
            <div class="col-md-9">
                <input type="text" name="sdt" id="sdt" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stk" class="col-md-3 col-form-label">Số Tài Khoản:</label>
            <div class="col-md-9">
                <input type="number" name="stk" id="stk" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-md-3 col-form-label">Email:</label>
            <div class="col-md-9">
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Ngày bắt đầu:</label>
            <div class="col-md-9">
                <input type="date" name="NBD" id="NBD" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Lương theo giờ:</label>
            <div class="col-md-9">
                <input type="number" name="HeSoLuong" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">BHXH:</label>
            <div class="col-md-9">
                <input type="text" name="BHXH" class="form-control" required value="0.08" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="img" class="col-md-3 col-form-label">Hình ảnh:</label>
            <div class="col-md-9">
                <input type="file" name="file" id="img" class="form-control-file" accept="image/*" required>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary" name="btn_themnv">Thêm</button>
    </div>
</form>
      </div>
    </div>
  </div>
</div> 

<script>
document.getElementById('formThemNhanVien').addEventListener('submit', function(event) {
    var hodem = document.getElementById('hodem').value.trim();
    var ten = document.getElementById('ten').value.trim();
    var sdt = document.getElementById('sdt').value.trim();
    var ngaysinh = document.getElementById('ngaysinh').value;
    var NBD = document.getElementById('NBD').value;
    var today = new Date().toISOString().split('T')[0];

    var specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/g;
    var phonePattern = /^\d{10}$/;

    // Check for special characters in hodem and ten
    if (specialCharPattern.test(hodem) || specialCharPattern.test(ten)) {
        alert('Họ đệm và tên không được chứa ký tự đặc biệt.');
        event.preventDefault();
        return;
    }

    // Check if there are leading or trailing spaces in hodem, ten, and sdt
    if (hodem !== document.getElementById('hodem').value || ten !== document.getElementById('ten').value || sdt !== document.getElementById('sdt').value) {
        alert('Họ đệm, tên, và số điện thoại không được chứa khoảng trắng ở đầu hoặc cuối.');
        event.preventDefault();
        return;
    }

    // Check if phone number is exactly 10 digits
    if (!phonePattern.test(sdt)) {
        alert('Số điện thoại phải là 10 số.');
        event.preventDefault();
        return;
    }

    // Check if ngaysinh is at least 18 years ago
    var birthDate = new Date(ngaysinh);
    var age = new Date().getFullYear() - birthDate.getFullYear();
    var month = new Date().getMonth() - birthDate.getMonth();
    if (month < 0 || (month === 0 && new Date().getDate() < birthDate.getDate())) {
        age--;
    }
    if (age < 18) {
        alert('Nhân viên phải ít nhất 18 tuổi.');
        event.preventDefault();
        return;
    }

    // Check if NBD is not after today
    if (NBD > today) {
        alert('Ngày bắt đầu không được sau ngày hiện tại.');
        event.preventDefault();
        return;
    }
});
</script>
