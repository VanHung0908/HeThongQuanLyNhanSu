
<body>
<div id="container">
        <div id="videoContainer">
            <video id="video" autoplay></video>
            <button id="snap" class="nut">Chụp ảnh</button>
        </div>
        <div class="captured-image">
            <div id="capturedImageContainer"></div>
            <span class="date" id="currentDate" style="display:none;">ádasf</span>
            <button id="compareButton" class="nut" style="display:none;">Chấm công</button>
        </div>
    </div>
    <div id="map"></div>
    <script>
        let platform = new H.service.Platform({
            'apikey': 'XBb6v5AkVX7Min76bJN8OM4HxiPm_uRaG_7N-wVX4FM'
        });
        let map;
        // const targetLocation = { lat:   10.8165592, lng:   106.6865922};

        const targetLocation = { lat:    10.821203, lng:   106.7116815};
        const checkRadius = 200; 
        function initMap() {
            let defaultLayers = platform.createDefaultLayers();
            map = new H.Map(document.getElementById('map'), defaultLayers.vector.normal.map, {
                center: { lat: 0, lng: 0 },
                zoom: 15,
                pixelRatio: window.devicePixelRatio || 1
            });
            let ui = H.ui.UI.createDefault(map, defaultLayers);
            let mapEvents = new H.mapevents.MapEvents(map);
            let behavior = new H.mapevents.Behavior(mapEvents);
        }

        function getDistance(lat1, lon1, lat2, lon2) { 
            const R = 6371e3; // Earth's radius in meters
            const φ1  = lat1 * Math.PI / 180;// Chuyển đổi vĩ độ 1 sang radian
            const φ2 = lat2 * Math.PI / 180; 
            const Δφ = (lat2 - lat1) * Math.PI / 180;// Chênh lệch vĩ độ
            const Δλ = (lon2 - lon1) * Math.PI / 180;// Chênh lệch kinh độ

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
        
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; //  Khoảng cách theo mét
            //Công thức Haversine (trên bề mặt trái đất)
        }
        let currentPosition = { latitude: null, longitude: null };
        function updatePosition(position) {
            const { latitude, longitude } = position.coords;
            console.log(`Vĩ độ: ${latitude}, Kinh độ: ${longitude}`);
            currentPosition.latitude = latitude;
            currentPosition.longitude = longitude;
            map.setCenter({ lat: latitude, lng: longitude });

            let marker = new H.map.Marker({ lat: latitude, lng: longitude });
            map.addObject(marker);

            // Thêm vòng tròn với bán kính 500m
            let circle = new H.map.Circle(
                { lat: latitude, lng: longitude }, // Tọa độ trung tâm
                checkRadius, // Bán kính tính bằng mét
                {
                    style: {
                        strokeColor: 'rgba(0, 128, 255, 0.5)', // Màu viền vòng tròn
                        fillColor: 'rgba(0, 128, 255, 0.2)' // Màu nền vòng tròn
                    }
                }
            );
        map.addObject(circle);

    // Thêm điểm mốc màu đỏ
            addRedMarker(targetLocation);

            // Kiểm tra nếu vị trí hiện tại nằm trong vùng mốc
            const distance = getDistance(latitude, longitude, targetLocation.lat, targetLocation.lng);
            if (distance <= checkRadius) {
                setButtonsState(false); // Cho phép bấm nút Chấm công
            } else {
                setButtonsState(true); // Không cho phép bấm nút Chấm công
            }
        }

        function addRedMarker(location) {
            const redIcon = new H.map.Icon('https://img.icons8.com/ios-filled/50/FF0000/marker.png', { size: { w: 32, h: 32 } });
            let redMarker = new H.map.Marker(location, { icon: redIcon });
            map.addObject(redMarker);
        }

        function handleLocationError(error) {
            console.error('Error getting location: ', error);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(updatePosition, handleLocationError, {
                    enableHighAccuracy: true, // Sử dụng độ chính xác cao hơn
                    timeout: 5000, // Thời gian timeout
                    maximumAge: 0 // Không sử dụng vị trí cũ
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
            }
        }

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

            const faceMatcher = new faceapi.FaceMatcher(faceDescriptors, 0.5);
            const image = await getImageFromDataUrl(imageData);
            const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors();

            if (detections.length > 0) {
                const resizedDetections = faceapi.resizeResults(detections, image);
                for (const detection of resizedDetections) {
                    const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
                    if (bestMatch.label !== 'unknown') {
                        console.log(`Kết quả: Nhận diện khuôn mặt của ${bestMatch.label} với độ chính xác ${bestMatch.distance}`);
                        await addToAttendance(bestMatch.label);
                        Swal.fire({
                        icon: 'success',
                        title: 'Chấm công thành công',
                        confirmButtonText: 'OK'
                        });
                        setButtonsState(false);
                        compareButton.textContent = 'Chấm công';
                        return;
                    }
                }
                console.log('Khuôn mặt không khớp với dữ liệu đã lưu');
            } else {
                console.log('Không tìm thấy khuôn mặt trong hình ảnh');
            }
            Swal.fire({
            icon: 'error',
            title: 'Chấm công không thành công',
            text: 'Khuôn mặt không khớp hoặc không tìm thấy khuôn mặt',
            confirmButtonText: 'OK'
            });
            setButtonsState(false);
            compareButton.textContent = 'Chấm công';
        }
        async function getImageFromDataUrl(dataUrl) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.src = dataUrl;
            });
        }

        async function addToAttendance(label) {
            try {
                const currentDate = new Date();
                const currentTime = currentDate.getHours() + ':' + currentDate.getMinutes() + ':' + currentDate.getSeconds();
                const currentDateFormatted = currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getDate();
                const vido = currentPosition.latitude;
                const kinhdo = currentPosition.longitude;
                console.log(vido);
                const response = await fetch('insertgiolam.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        label: label,
                        time: currentTime,
                        date: currentDateFormatted,
                        vido: vido,
                        kinhdo: kinhdo
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
        window.addEventListener('DOMContentLoaded', () => {
                    initFaceAPI();
                });
        initCamera();
        initMap();
        getLocation();
    </script>
</body>
</html>