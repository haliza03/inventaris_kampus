<?php
session_start();
$conn = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function registerUser($username, $password, $role) {
    global $conn;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hash, $role);
    return $stmt->execute();
}

function loginUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
    }
    return false;
}

$page = $_GET['page'] ?? 'login';

echo '<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>';
switch ($page) {
    case 'dashboard':
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mahasiswa') {
            include 'dashboard_mahasiswa.php';
        } else {
            include 'dashboard.php';
        }
        break;
    case 'login':
        include 'login.php';
        break;
    case 'register':
        include 'register.php';
        break;
    case 'logout':
        include 'logout.php';
        break;
    case 'tambah_barang':
        include 'tambah_barang.php';
        break;
    case 'edit_barang':
        include 'edit_barang.php';
        break;
    case 'hapus_barang':
        include 'hapus_barang.php';
        break;

    default:
        echo '<p>Halaman tidak ditemukan.</p>';
        break;
}
echo '</body>
</html>';
?>
