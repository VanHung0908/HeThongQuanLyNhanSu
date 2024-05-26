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
        .captured-image {
            width: 50%;
        }
        .captured-image img {
            width: 100%;
        }
        #snap {
            margin-top: 10px;
            margin-left: 20%;
        }
        #compareButton {
            display: none; /* Initially hide the button */
            position: relative;
            left: 40%;
        }
        .chamcong {
            display: flex;
        }
        .chamcong .nut {
            background-color: #4CAF50;
            border: 1px solid #f5f5f5;
            border-radius: 5px;
            color: #FFF;
            padding: 5px;
            min-width: 120px;
            margin-top: 10px;
            cursor: pointer;
        }
        .chamcong .nut.disabled {
            background-color: #9E9E9E;
            cursor: not-allowed;
        }
        
    </style>
</head>
<body>
    <div id="container">
        <video id="video" autoplay></video>
        <div class="captured-image" id="capturedImageContainer"></div>
    </div>
    <div class="chamcong">
        <button id="snap" class="nut">Chụp ảnh</button>
        <button id="compareButton" class="nut">Chấm công</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.js"
        integrity="sha256-JOJ7NmVm2chxYZ1KPcAYd2bwVK7NaFj9QKMp7DClews=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/face-landmarks-detection"></script>
    <script>
        const video = document.getElementById('video');
        const snapButton = document.getElementById('snap');
        const capturedImageContainer = document.getElementById('capturedImageContainer');
        const compareButton = document.getElementById('compareButton');

        let capturedImage = null;

        async function initCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error accessing the camera: ', err);
            }
        }

        async function takeSnapshot() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            capturedImage = canvas.toDataURL('image/jpeg');
            displayCapturedImage(capturedImage);
            compareButton.style.display = 'block';
        }

        function displayCapturedImage(imageData) {
            capturedImageContainer.innerHTML = '';
            const img = document.createElement('img');
            img.src = imageData;
            img.alt = `Captured Image`;
            capturedImageContainer.appendChild(img);
        }

        function setButtonsState(disabled) {
            snapButton.disabled = disabled;
            compareButton.disabled = disabled;
            if (disabled) {
                snapButton.classList.add('disabled');
                compareButton.classList.add('disabled');
            } else {
                snapButton.classList.remove('disabled');
                compareButton.classList.remove('disabled');
            }
        }

        snapButton.addEventListener('click', takeSnapshot);

        compareButton.addEventListener('click', async () => {
            compareButton.textContent = 'Vui lòng chờ...';
            setButtonsState(true);
            if (capturedImage) {
                await compareWithTrainedData(capturedImage);
            } else {
                console.error('Please capture an image first.');
                setButtonsState(false);
                compareButton.textContent = 'Chấm công';
            }
        });

        async function initFaceAPI() {
            await faceapi.nets.ssdMobilenetv1.loadFromUri('./models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
        }

        async function compareWithTrainedData(imageData) {
            await initFaceAPI();
            const response = await fetch('get_training_data.php');
            const trainingData = await response.json();

            const faceDescriptors = [];
            trainingData.forEach(({ label, descriptors }) => {
                const float32ArrayDescriptors = descriptors.map(descriptor => new Float32Array(descriptor));
                faceDescriptors.push(new faceapi.LabeledFaceDescriptors(label, float32ArrayDescriptors));
            });

            const faceMatcher = new faceapi.FaceMatcher(faceDescriptors, 0.4);
            const image = await getImageFromDataUrl(imageData);
            const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors();

            if (detections.length > 0) {
                const resizedDetections = faceapi.resizeResults(detections, image);
                for (const detection of resizedDetections) {
                    const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
                    if (bestMatch.label !== 'unknown') {
                        console.log(`Kết quả: Nhận diện khuôn mặt của ${bestMatch.label} với độ chính xác ${bestMatch.distance}`);
                        await addToAttendance(bestMatch.label);
                        alert('Chấm công thành công.');
                    } else {
                        alert('Không nhận diện được khuôn mặt.');
                    }
                }
            } else {
                alert('Không tìm thấy khuôn mặt.');
            }
            compareButton.textContent = 'Hoàn tất';
            setButtonsState(false);
        }

        async function getImageFromDataUrl(dataUrl) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = function() {
                    resolve(img);
                };
                img.onerror = function() {
                    reject(new Error('Failed to load image'));
                };
                img.src = dataUrl;
            });
        }

        async function addToAttendance(label) {
            try {
                const currentDate = new Date();
                const currentTime = currentDate.getHours() + ':' + currentDate.getMinutes() + ':' + currentDate.getSeconds();
                const currentDateFormatted = currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getDate();

                const response = await fetch('insertgiolam.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        label: label,
                        time: currentTime,
                        date: currentDateFormatted
                    })
                });
                console.log(response);
                if (response.ok) {
                    console.log('Dữ liệu đã được thêm vào bảng chấm công.');
                } else {
                    console.error('Lỗi khi thêm dữ liệu vào bảng chấm công.');
                }
            } catch (error) {
                console.error('Lỗi:', error);
            }
        }

        window.onload = initCamera;
    </script>
</body>
</html>
