<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (loginUser($username, $password)) {
        header("Location: ?page=dashboard");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Login gagal.</p>";
    }
}
?>

<div class="login-wrapper">
    <div class="login-box">
        <img src="user-icon.png" alt="User Icon" class="user-icon"> <!-- Ganti dengan path ikon profil -->

        <form method="POST">
            <input type="text" name="username" placeholder="USERNAME" required class="input-style"><br>
            <input type="password" name="password" placeholder="PASSWORD" required class="input-style"><br>
            <select name="role" required class="input-style">
                <option value="">ROLE</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
            </select><br>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>

        <p class="register-link">
            BELOM PUNYA AKUN? <a href="?page=register">BIKIN AKUN</a>
        </p>
    </div>
</div>
