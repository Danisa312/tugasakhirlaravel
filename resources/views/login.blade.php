<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .login-container h2 {
            text-align: center;
            color: #993e3c;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            background-color: #993e3c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login Admin</h2>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" placeholder="Masukkan username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Masukkan password">
    </div>
    <button onclick="login()">Login</button>
    <div id="message" class="error"></div>
</div>

<script>
    function login() {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const messageDiv = document.getElementById("message");
        messageDiv.textContent = "";

        axios.post('http://localhost:8000/api/login', {
            username: username,
            password: password
        })
        .then(response => {
            messageDiv.className = 'success';
            messageDiv.textContent = response.data.message;
            // Simpan token atau redirect ke dashboard
            localStorage.setItem('token', response.data.token);
            window.location.href = '/admin/dashboard'; // Ganti sesuai kebutuhan
        })
        .catch(error => {
            messageDiv.className = 'error';
            if (error.response) {
                messageDiv.textContent = error.response.data.message;
            } else {
                messageDiv.textContent = 'Terjadi kesalahan saat menghubungi server';
            }
        });
    }
</script>
</body>
</html>
