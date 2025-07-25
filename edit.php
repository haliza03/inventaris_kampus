<?php
session_start();

// Cek apakah sudah login dan berperan sebagai dosen
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dosen') {
    die("Akses ditolak.");
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Fungsi aman
function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    die("ID barang tidak ditemukan.");
}
$id = intval($_GET['id']);

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $ruangan = $_POST['id_ruangan'];
    $foto = $_FILES['foto']['name'];

    if (!empty($foto)) {
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, jumlah=?, id_ruangan=?, foto=? WHERE id=?");
        $stmt->bind_param("siisi", $nama, $jumlah, $ruangan, $foto, $id);
    } else {
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, jumlah=?, id_ruangan=? WHERE id=?");
        $stmt->bind_param("siii", $nama, $jumlah, $ruangan, $id);
    }
    $stmt->execute();
    header("Location: dashboard_dosen.php");
    exit;
}

// Ambil data barang
$data = $conn->query("SELECT * FROM barang WHERE id=$id")->fetch_assoc();
$ruang = $conn->query("SELECT * FROM ruangan");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        form input, select {
            margin-bottom: 10px;
            padding: 5px;
            width: 300px;
        }

        button {
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <h2>Edit Barang</h2>
    <form method="POST" enctype="multipart/form-data">
        Nama: <input type="text" name="nama_barang" value="<?= escape($data['nama_barang']) ?>"><br>
        Jumlah: <input type="number" name="jumlah" value="<?= escape($data['jumlah']) ?>"><br>
        Ruangan: 
        <select name="id_ruangan">
            <?php while ($r = $ruang->fetch_assoc()): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id'] == $data['id_ruangan'] ? 'selected' : '' ?>>
                    <?= escape($r['nama_ruangan']) ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        Foto Saat Ini:<br>
        <img src="uploads/<?= escape($data['foto']) ?>" width="120"><br><br>
        Ganti Foto (opsional): <input type="file" name="foto"><br><br>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <br>
    <a href="dashboard_dosen.php">← Kembali ke Dashboard</a>
</body>
</html>
