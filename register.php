<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if (registerUser($username, $password, $role)) {
        header("Location: ?page=login");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Registrasi gagal.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 300px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 22px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f1f3f6;
            transition: 0.2s ease-in-out;
        }

        .form-container input:focus,
        .form-container select:focus {
            outline: none;
            border-color: #000;
            background-color: #fff;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .form-container button:hover {
            background-color: #333;
        }

        .form-footer {
            margin-top: 14px;
            font-size: 14px;
        }

        .form-footer a {
            color: purple;
            font-weight: bold;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registrasi</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="dosen">Dosen</option>
        </select><br>
        <button type="submit">Daftar</button>
    </form>
    <p class="form-footer"><a href="?page=login">Kembali ke Login</a></p>
</div>

</body>
</html>
