<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Smarthr - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - HRMS admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="account-page">
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <!-- Account Logo -->
                <div class="account-logo">
                    <a href=""><img src="./assets/img/logo4.png" alt="Company Logo"></a>
                </div>
                <!-- /Account Logo -->
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Login</h3>
                        <!-- Account Form -->
                        <form id="loginForm">
                            <div class="form-group">
                                <label>User Name</label>
                                <input class="form-control" name="username" id="username" required type="text">
                                <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Password</label>
                                    </div>
                                </div>
                                <input class="form-control" name="password" id="password" required type="password">
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Login</button>
                            </div>
                        </form>
                        <!-- /Account Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script>
      document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch('page/api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'username': username,
                'password': password
            })
        });
        
        // Check if response is in JSON format
        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json();
            
            if (result.status === 'success') {
                window.location.href = result.redirect;
            } else {
                const errorMessage = document.getElementById('error-message');
                errorMessage.textContent = result.message;
                errorMessage.style.display = 'block';
            }
        } else {
            throw new Error('Response is not JSON');
        }
    } catch (error) {
        console.error('Error:', error);
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = 'An error occurred. Please try again.';
        errorMessage.style.display = 'block';
    }
});

    </script>
</body>
</html>
