<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calendar</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    /* CSS styles */
    * {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
    }
    .flex {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .container {
        width: 80%;
        height: 80%;
        background: rgb(32, 32, 32);
    }
    /* Calendar styles */
    .calendar {
        width: 100%;
        height: 100%;
        background: white;
        box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.7), -10px -10px 20px rgba(0, 0, 0, 0.45);
    }
    .calendar .head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #ff9a8b;
    }
    .calendar .head .control {
        display: flex;
        align-items: center;
    }
    .calendar .head .control button {
        background: #ff9a8b;
        border: none;
        color: white;
        font-size: 16px;
        padding: 8px 12px;
        margin: 0 5px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .calendar .head .control button:hover {
        background: #ff6a88;
    }
    .calendar .date-info {
        background-color: #ff9a8b;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        color: white;
    }
    .calendar .days {
        width: 100%;
        height: 20%;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        margin: auto;
        padding: 10px;
        margin-bottom: 20px;
    }
    .calendar .days table {
        width: 100%;
        border-collapse: collapse;
    }
    .calendar .days th,
    .calendar .days td {
        border: 2px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .calendar .days .today {
        background: #ff6a88;
        cursor: pointer;
        color: white;
        border-radius: 50%;
        transition: 0.2s;
    }
    .calendar .days .today:hover {
        background: #f95c79;
    }
    .calendar .days .day:not(.today):hover {
        border-radius: 0;
        border: 0.5px solid black;
        background: rgba(0, 0, 0, 0.25);
        cursor: pointer;
    }
    .calendar .days .day:nth-child(7n) {
        border-right: none;
    }
    .calendar .days .day:nth-last-child(-n+7) {
        border-bottom: none;
    }
    #er {
        color: red;
        font-style: italic;
    }
    .er {
        border: 1px solid red;
    }
    .dk {
        position: relative;
        margin: 5px;
    }
    .registered-info {
        margin-top: 20px;
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 5px;
    }
  

</style>
</head>
<body>
    <div class="calendar">
        <div class="head">
            <div class="control">
                <button class="prev" onclick="prevWeek()">Previous</button>
                <button class="next" onclick="nextWeek()">Next</button>
            </div>
            <div class="date-info"></div>
        </div>
        <div class="days flex">
            <table class="tbl">
                <thead>
                    <!-- Table header -->
                    <tr>
                        <th>Thứ</th>
                        <th class="day">Mon</th>
                        <th class="day">Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <tr>
                        <th>Ngày</th>
                        <td class="day">31</td>
                        <td class="day">1</td>
                        <td class="day">2</td>
                        <td class="day">3</td>
                        <td class="day">4</td>
                        <td class="day">5</td>
                        <td class="day">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="registeredInfoContainer">
            <table id="registeredInfoTable" class="table">
                <form action="" method="post">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Thứ</th>
                        <th>Ca làm việc</th>
                        <th>Ghi chú</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                </form>
                <tbody id="registeredInfoBody">
                    <!-- Các dòng thông tin đã đăng ký sẽ được thêm vào đây -->
                </tbody>
            </table>
        </div>
        <div class="result"></div>
      
    </div>

    <!-- Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Đăng ký lịch làm việc</h3>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="xulydklich.php" method="post">
                        <input type="hidden" name="day" id="modalDay" value="">
                        <input type="hidden" name="workDay" id="workDay" value="">
                        <div class="row form-group">

                            <div class="col-9">
                                <span id="modalDayDisplay"></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3">
                                <label for=""> Chọn ca làm việc</label>
                            </div>
                            <div class="col-9">
                                <select name="ca" id="caSelect">
                                    <option value="[6:00-14:00]">Ca A [6:00-14:00]</option>
                                    <option value="[6:00-10:00]">Ca A [6:00-10:00]</option>
                                    <option value="[10:00-14:00]">Ca A [10:00-14:00]</option>
                                    <option value="[14:00-22:00]">Ca B [14:00-22:00]</option>
                                    <option value="[14:00-18:00]">Ca B [14:00-18:00]</option>
                                    <option value="[18:00-22:00]">Ca B [18:00-22:00]</option>
                                </select>
                                <span id="er">(*)</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-3">
                                <label for=""> Ghi chú</label>
                            </div>
                            <div class="col-9">
                                <textarea name="ghichu" id="ghichu" cols="50" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Đăng ký</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript code
        let currentDate = new Date();

        function renderCalendar() {
            const daysContainer = document.getElementById("calendar-body");
            daysContainer.innerHTML = '';

            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(startOfWeek.getDate() - (startOfWeek.getDay() - 1)); // Set to the first day of the current week
            const endOfWeek = new Date(currentDate);
            endOfWeek.setDate(endOfWeek.getDate() + (7 - endOfWeek.getDay())); // Set to the last day of the current week

            const row = document.createElement('tr');

            const caCell = document.createElement('td');
            row.appendChild(caCell);

            for (let i = 0; i < 7; i++) {
                const day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);

                const cell = document.createElement('td');
                cell.textContent = day.getDate();
                cell.setAttribute('data-day', day.toISOString().slice(0, 10)); // Set data-day attribute to ISO string of the date
                cell.setAttribute('data-weekday', day.getDay()); // Set data-weekday attribute to the day of the week
                cell.classList.add('day');

                if (isToday(day)) {
                    cell.classList.add('today');
                }

                cell.addEventListener('click', function() {
                    const day = this.getAttribute('data-day');
                    const weekday = this.getAttribute('data-weekday');
                    openModal(day, weekday);
                });

                row.appendChild(cell);
            }

            daysContainer.appendChild(row);

            const startDateString = startOfWeek.toDateString().split(' ')[1] + ' ' + startOfWeek.getDate();
            const endDateString = endOfWeek.toDateString().split(' ')[1] + ' ' + endOfWeek.getDate();
            document.querySelector('.date-info').textContent = `${startDateString} - ${endDateString}`;
        }

        function nextWeek() {
            currentDate.setDate(currentDate.getDate() + 7);
            renderCalendar();
        }

        function prevWeek() {
            currentDate.setDate(currentDate.getDate() - 7);
            renderCalendar();
        }

        function isToday(date) {
            const today = new Date();
            return date.getDate() === today.getDate() &&
                date.getMonth() === today.getMonth() &&
                date.getFullYear() === today.getFullYear();
        }

        function openModal(day, weekday) {
            const today = new Date();
            const fiveDaysLater = new Date();
            fiveDaysLater.setDate(today.getDate() + 3); // Tăng ngày hiện tại lên 3 ngày

            // Kiểm tra nếu ngày được chọn lớn hơn hoặc bằng ngày 3 ngày sau ngày hiện tại
             if (new Date(day) >= fiveDaysLater) {
                $('#myModal').modal('show');
                document.getElementById('modalDay').value = day;
                document.getElementById('workDay').value = weekday;
                document.getElementById('modalDayDisplay').innerText = weekdayText(weekday);
             } else {
                 // Hiển thị thông báo hoặc thực hiện hành động khác nếu không đủ điều kiện
                 alert("Bạn chỉ có thể đăng ký lịch làm việc cho 3 ngày tiếp theo từ ngày hiện tại.");
             }
        }


        // Hàm để chuyển đổi số thứ thành văn bản thứ
        function weekdayText(weekday) {
            const weekdays = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"];
            return weekdays[weekday];
        }


        function submitForm() {
            var selectedValue = document.getElementById('modalDay').value;
            var workDayValue = document.getElementById('workDay').value;
            var caValue = document.getElementById('caSelect').value;
            var noteValue = document.getElementById('ghichu').value;
            // Cập nhật giá trị đã chọn vào nút đã mở modal
            if (selectedValue) {
                var targetButton = document.querySelector('.dk button[data-day="' + selectedValue + '"]');
                if (targetButton) {
                    targetButton.innerText = workDayValue + ' - ' + selectedValue + ' - ' + caValue + ' - ' + noteValue;
                }
            }
            // Hiển thị thông tin đã đăng ký
            showRegisteredInfo(selectedValue, workDayValue, caValue, noteValue);
            // Ẩn modal
            $('#myModal').modal('hide');
        }

        function showRegisteredInfo(day, weekday, ca, note) {
        // Chuyển đổi ngày thành dạng "ngày tháng năm"
        const date = new Date(day);
        const formattedDate = ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();

        const registeredInfoBody = document.getElementById('registeredInfoBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${formattedDate}</td>
            <td>${weekdayText(weekday)}</td>
            <td>${ca}</td>
            <td>${note}</td>
            <td><button class="btn btn-danger" onclick="removeRegisteredInfo(this)">Xóa</button>
            <button class="btn btn-success" onclick="saveData(this)">Lưu</button></td>
        `;
        registeredInfoBody.appendChild(newRow);
    }

        function removeRegisteredInfo(button) {
            const row = button.closest('tr');
            row.remove();
        }
        renderCalendar();
        function showSuccessNotification() {
            var notification = document.getElementById("success-notification");
            notification.style.display = "block"; // Hiển thị thông báo
            setTimeout(function(){
                notification.style.display = "none"; // Ẩn thông báo sau một khoảng thời gian
            }, 3000); // Thời gian hiển thị thông báo (3 giây)
        }

        function saveData(button) {
    const row = button.closest('tr');

    const dateCell = row.querySelector('td:first-child');
    const weekdayCell = row.querySelector('td:nth-child(2)');
    const caCell = row.querySelector('td:nth-child(3)');
    const noteCell = row.querySelector('td:nth-child(4)');

    if (!dateCell || !weekdayCell || !caCell || !noteCell) {
        console.error("Không thể lấy dữ liệu từ các ô trong hàng.");
        return;
    }

    const date = dateCell.textContent.trim();
    const weekday = weekdayCell.textContent.trim();
    const ca = caCell.textContent.trim();
    const note = noteCell.textContent.trim();

    $.ajax({
      url:'xulydklich.php',
      type:'post',
      dataType:'html',
      data:{
        date: date,
        weekday: weekday,
        ca: ca,
        note: note
      }
    }).done(function(ketqua){
      $('.result').html(ketqua);
      row.remove();
    });

}

    </script>
</body>
</html>
