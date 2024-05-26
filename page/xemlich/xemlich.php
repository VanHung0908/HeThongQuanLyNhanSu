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
        display: flex;
        flex-direction: row;
        overflow: hidden; /* Tránh hiển thị thanh cuộn nếu bảng quá lớn */
    }
    .calendar .days table {
        height: 100%; /* Bảng sẽ chiếm hết không gian của lớp .days */
        width: 100%; /* Bảng sẽ chiếm hết không gian của lớp .days */
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
        margin-top: 10px;
        padding: 5px;
        background-color: #f5f5f5;
        border-radius: 5px;
        width: 100%;
    }
    /* CSS for list items and icons */
    .ca-lam-list {
        list-style: none;
        padding: 0;
    }

    .ca-lam-item {
        display: flex; /* Sử dụng flexbox để căn chỉnh các phần tử con */
        align-items: center; /* Canh chỉnh các phần tử con theo trục dọc */
        margin-bottom: 5px;
        border-bottom: 1px solid #ccc; /* Tạo đường gạch ngang */
    padding-bottom: 5px; /* Khoảng cách giữa các mục */
    margin-bottom: 5px; /* Khoảng cách giữa các mục */
    }

    .ca-lam-item:last-child {
        border-bottom: none; /* Loại bỏ đường gạch ngang ở phần tử cuối cùng */
    }
    .delete-icon, .approve-icon {
        margin-left: auto; /* Đẩy các biểu tượng về phía bên phải của mỗi mục */
        cursor: pointer;
        font-size: 18px;
        margin: 5px;
    }

    /* Hover effect for icons */
    .delete-icon:hover, .approve-icon:hover {
        transform: scale(1.2);
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
    <div class="days">
        <table>
            <tbody>
                <tr id="days-of-week"></tr>
                <tr id="calendar-days"></tr>
                <tr id="data-for-days"></tr>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" id="hiddenMaLLV">
<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // JavaScript code
    let currentDate = new Date();
    const today = new Date(); // Lưu ngày hiện tại

    function renderCalendar(lichLamViec) {
        const daysOfWeekRow = document.getElementById("days-of-week");
        const calendarDaysRow = document.getElementById("calendar-days");
        const dataForDaysRow = document.getElementById("data-for-days");

        daysOfWeekRow.innerHTML = '';
        calendarDaysRow.innerHTML = '';
        dataForDaysRow.innerHTML = '';

        const daysOfWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        const currentDateFormatted = currentDate.toISOString().slice(0, 10);

        // Populate row for days of week
        for (let i = 0; i < 7; i++) {
            const dayOfWeekCell = document.createElement('th');
            dayOfWeekCell.textContent = daysOfWeek[i];
            daysOfWeekRow.appendChild(dayOfWeekCell);
        }

        // Populate row for calendar days
        for (let i = 0; i < 7; i++) {
            const day = new Date(currentDate);
            day.setDate(day.getDate() - (day.getDay() - 1) + i);
            const dayCell = document.createElement('td');
            dayCell.textContent = day.getDate();

            // Add circle class to current day
            if (day.toDateString() === today.toDateString()) {
                dayCell.classList.add('today');
            }

            calendarDaysRow.appendChild(dayCell);
        }

        // Populate row for data for each day
        for (let i = 0; i < 7; i++) {
            const day = new Date(currentDate);
            day.setDate(day.getDate() - (day.getDay() - 1) + i);
            const lichLamViecNgay = lichLamViec.filter(item => item.ngayDangKy === day.toISOString().slice(0, 10));
            const dataCell = document.createElement('td');
            
            if (lichLamViecNgay.length > 0) {
                const infoList = document.createElement('ul');
                infoList.classList.add('ca-lam-list');

                lichLamViecNgay.forEach(item => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('ca-lam-item');

                    // Kiểm tra nếu ghiChu không rỗng thì hiển thị nó, ngược lại không hiển thị
                    listItem.textContent = `${item.caLam}${item.ghiChu ? ' - ' + item.ghiChu : ''} - ${item.nhanvien}`;
                    infoList.appendChild(listItem);
             });

                dataCell.appendChild(infoList);

            } else {
                dataCell.textContent = '';
            }
            dataForDaysRow.appendChild(dataCell);
        }

        const startDateString = currentDate.toDateString().split(' ')[1] + ' ' + currentDate.getDate();
        const endDate = new Date(currentDate);
        endDate.setDate(endDate.getDate() + 6);
        const endDateString = endDate.toDateString().split(' ')[1] + ' ' + endDate.getDate();
        document.querySelector('.date-info').textContent = `${startDateString} - ${endDateString}`;
    }
    function ajax(){
           $.ajax({
            url: 'xulyxemlich.php',
            method: 'POST',
            success: function (data) {
                let processedData = processData(JSON.parse(data));
                renderCalendar(processedData);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    function processData(data) {
        let processedData = [];

        data.forEach(item => {
            let processedItem = {
                MaLLV: item.MaLLV,
                TrangThai: item.TrangThai,
                ngayDangKy: item.Ngay,
                caLam: item.CaLam,
                ghiChu: item.GhiChu ? item.GhiChu : '',
                nhanvien:item.NhanVien
            };
            processedData.push(processedItem);
        });

        return processedData;
    }
    function nextWeek() {
        currentDate.setDate(currentDate.getDate() + 7);
        ajax();

    }

    function prevWeek() {
        currentDate.setDate(currentDate.getDate() - 7);
        ajax();

    }

    ajax();
 
</script>
</body>
</html>
