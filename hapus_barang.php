<?php
// Mulai session dan koneksi jika belum tersedia
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "inventaris_kampus");
    if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
}

// Cek role user
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dosen') {
    echo '<p>Akses ditolak.</p>';
    return;
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo '<p>ID tidak valid.</p>';
    return;
}

// Hapus barang
$stmt = $conn->prepare("DELETE FROM barang WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: ?page=dashboard");
    exit;
} else {
    echo '<p>Gagal menghapus barang.</p>';
    $stmt->close();
}
?>
