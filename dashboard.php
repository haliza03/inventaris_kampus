<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Cek login
if (!isset($_SESSION['user'])) {
    echo "<p>Harus login dulu.</p>";
    return;
}

// Inisialisasi variabel user
$role = $_SESSION['user']['role'];
$username = htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8');
$gambar = ($role === 'dosen') ? 'dosen.png' : 'mahasiswa.png';

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Include dashboard sesuai role
if ($role === 'mahasiswa') {
    include 'dashboard_mahasiswa.php';
} elseif ($role === 'dosen') {
    include 'dashboard_dosen.php';
} else {
    echo "<p>Peran tidak dikenali.</p>";
}
?>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    .logout {
        float: right;
        text-decoration: none;
        color: white;
        background: #d32f2f;
        padding: 10px 16px;
        border-radius: 5px;
        font-weight: bold;
    }

    .profile {
        text-align: center;
        margin-top: 60px;
    }

    .profile img {
        width: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    .tambah-barang {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 16px;
        background-color: #1976d2;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .tambah-barang:hover {
        background-color: #0d47a1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    th, td {
        border: 1px solid #e0e0e0;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #e3f2fd;
    }

    tr:hover {
        background-color: #f1f9ff;
    }

    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        margin: 2px;
    }

    .edit {
        background-color: #fbc02d;
    }

    .hapus {
        background-color: #e53935;
    }

    .btn:hover {
        opacity: 0.9;
    }

    button {
        padding: 6px 14px;
        border: none;
        background-color: #4caf50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #388e3c;
    }

    .img-preview {
        width: 80px;
        border-radius: 8px;
    }
</style>




<?php
// Ambil data barang
$result = $conn->query("SELECT barang.*, ruangan.nama_ruangan FROM barang 
                        JOIN ruangan ON barang.id_ruangan = ruangan.id");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
       

        
        echo '</td></tr>';
    }
} 
$conn->close();