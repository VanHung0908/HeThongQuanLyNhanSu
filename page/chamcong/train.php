<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Demo</title>
    <style>
        #container {
            display: flex;
        }
        #video {
            width: 50%;
            margin-right: 10px;
        }
        .captured-images {
            width: 50%;
        }
        .captured-images img {
            width: 150px;
            margin: 10px;
        }
        .train {
            display: flex;
        }
        #snap{
            margin-left: 20%;
        }
        #saveButton {
            position: relative;
            left: 40%;
        }
        #saveButton {
            display: none; /* Initially hide the button */
            position: relative;
            left: 40%;
        }
        .train .nut{
        background-color: #4CAF50;
        border: 1px solid #f5f5f5;
        border-radius: 5px ;
        color: #FFF;
        padding: 5px;
        min-width: 120px;
        margin-top: 10px;
        cursor: pointer;
        }
        .train .nut.disabled{
            background-color: #9E9E9E;
            cursor: not-allowed;

        }
        #capturedImagesContainer {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px; 
        }
        #capturedImagesContainer img {
            width: 90%;
            height: auto;
        }

    </style>
</head>
<body>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.js"
        integrity="sha256-JOJ7NmVm2chxYZ1KPcAYd2bwVK7NaFj9QKMp7DClews=" crossorigin="anonymous"></script>
        <div id="container">
        <video id="video" autoplay></video>
        <div class="captured-images" id="capturedImagesContainer"></div>
    </div>
    <div class="train">
    <button id="snap" class="nut">Chụp ảnh</button>
    <button id="saveButton" class="nut">Lưu</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.0/toastify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/face-landmarks-detection"></script>
    <script>
        const video = document.getElementById('video');
        const snapButton = document.getElementById('snap');
        const capturedImagesContainer = document.getElementById('capturedImagesContainer');
        const saveButton = document.getElementById('saveButton'); // Đổi tên biến
        let isSnapClicked = false; // Biến để kiểm tra xem nút "Chụp ảnh" đã được bấm hay chưa
        async function initCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error accessing the camera: ', err);
            }
        }

        async function takeSnapshot() {
            isSnapClicked = true; // Đặt biến isSnapClicked thành true khi bấm nút "Chụp ảnh"
            snapButton.disabled = true; // Vô hiệu hóa nút "Chụp ảnh"
            return new Promise((resolve, reject) => {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                canvas.toBlob(blob => {
                    resolve(blob);
                }, 'image/png');
                saveButton.style.display = 'block';
            });
        }

        snapButton.addEventListener('click', async () => {
        if (!isSaveClicked) { // Kiểm tra xem nút "Lưu" đã được bấm hay chưa
            const capturedImages = [];
            for (let i = 0; i < 9; i++) {
                const blob = await takeSnapshot();
                capturedImages.push(blob);
                displayCapturedImages(capturedImages);
                await new Promise(resolve => setTimeout(resolve, 1000));
            }
        } else {
            alert("Bạn đã lưu dữ liệu, không thể chụp ảnh mới.");
        }
    });


        let isSaveClicked = false; // Biến để kiểm tra xem nút "Lưu" đã được bấm hay chưa

        saveButton.addEventListener('click', async () => {
    const capturedImages = document.querySelectorAll('.captured-images img');
    // Thay đổi nội dung của nút thành "Vui lòng chờ"
    saveButton.textContent = "Vui lòng chờ";
    // Áp dụng lớp CSS để hiển thị giao diện khi đang chờ
    saveButton.classList.add('disabled');
    try {
        await trainModel(capturedImages);
        saveButton.textContent = "Hoàn tất";
        isSaveClicked = true; // Đặt trạng thái của nút "Lưu" thành true
        alert('Nhận diện thành công.');
        // Vô hiệu hóa hai nút "Chụp ảnh" và "Lưu" sau khi bạn đã bấm nút "Lưu"
        snapButton.disabled = true;
        saveButton.disabled = true;
        // Xóa lớp CSS để khôi phục giao diện ban đầu của nút
        saveButton.classList.remove('disabled');
    } catch (error) {
        console.error('Error saving data:', error);
        // Nếu có lỗi, khôi phục trạng thái và giao diện ban đầu của nút
        saveButton.textContent = "Lưu";
        saveButton.classList.remove('disabled');
    }
});

        
        async function getImageFromBlob(blob) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        resolve(img);
                    };
                    img.onerror = function() {
                        reject(new Error('Failed to load image'));
                    };
                    img.src = event.target.result;
                };
                reader.onerror = function() {
                    reject(new Error('Failed to read blob'));
                };
                reader.readAsDataURL(blob);
            });
        }

        async function initFaceAPI() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('./models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
        }

        async function trainModel(images) {
            await initFaceAPI();
            const labels = [String(<?php echo json_encode($_SESSION['MaND']); ?>)];
            const faceDescriptors = [];
            for (const label of labels) {
                const descriptors = [];
                for (const imageElement of images) {
                    const blob = await fetch(imageElement.src).then(response => response.blob());
                    const image = await getImageFromBlob(blob);
                    const detection = await faceapi.detectSingleFace(image).withFaceLandmarks().withFaceDescriptor();
                    if (detection) {
                        descriptors.push(detection.descriptor);
                    }
                }
                faceDescriptors.push(new faceapi.LabeledFaceDescriptors(label, descriptors));
            }
            console.log('Trained Face Descriptors:', faceDescriptors);
            // Gọi hàm để lưu dữ liệu vào database ngay sau khi huấn luyện mô hình hoàn tất
            await saveTrainedDataToDatabase(faceDescriptors);
        }

        function displayCapturedImages(images) {
            capturedImagesContainer.innerHTML = '';
            images.forEach((image, index) => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(image);
                img.alt = `Captured Image ${index + 1}`;
                capturedImagesContainer.appendChild(img);
            });
        }

        async function saveTrainedDataToDatabase(faceDescriptors) {
    try {
        const response = await fetch('save_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ faceDescriptors: faceDescriptors })
        });
        if (response.ok) {
            const data = await response.json();
            console.log('Data saved successfully:', data);
            // Hiển thị dữ liệu trên trang
            displayDataOnPage(data);    
            window.location.reload();
        } else {
            console.error('Failed to save data:', response.statusText);
        }
    } catch (error) {
        console.error('Error saving data:', error);
    }
}

        window.onload = initCamera;
    </script>
</body>
</html>
