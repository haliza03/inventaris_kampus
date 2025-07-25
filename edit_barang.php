<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dosen') {
    die('Akses ditolak.');
}
$koneksi = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);
if (!function_exists('escape')) {
    function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

// Validasi ID
// Validasi dan tracking id barang
$id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    } elseif (isset($_GET['id'])) {
        $id = intval($_GET['id']);
    }
}
 else {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
    }
}
if ($id <= 0) {
    header("Location: index.php?page=dashboard");
    exit;
}


// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jumlah = $_POST['jumlah'];
    $id_ruangan = $_POST['id_ruangan'];
    // Ambil nama file foto lama
    $foto_lama = '';
    $stmt_foto = $koneksi->prepare("SELECT foto FROM barang WHERE id = ?");
    $stmt_foto->bind_param("i", $id);
    $stmt_foto->execute();
    $result_foto = $stmt_foto->get_result();
    if ($result_foto && $result_foto->num_rows > 0) {
        $foto_lama = $result_foto->fetch_assoc()['foto'];
    }
    $foto = $foto_lama;
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
    }
    $stmt = $koneksi->prepare("UPDATE barang SET nama_barang=?, jumlah=?, id_ruangan=?, foto=? WHERE id=?");
    $stmt->bind_param("siisi", $nama, $jumlah, $id_ruangan, $foto, $id);
    $stmt->execute();
    echo '<script>alert("Barang berhasil diupdate.");window.location.href="?page=dashboard";</script>';
    exit;
}

$stmt_data = $koneksi->prepare("SELECT * FROM barang WHERE id = ?");
$stmt_data->bind_param("i", $id);
$stmt_data->execute();
$result_data = $stmt_data->get_result();
if (!$result_data || $result_data->num_rows == 0) {
    echo '<script>alert("Barang berhasil diupdate.");window.location.href="index.php?page=dashboard";</script>';
    exit;
}
$data = $result_data->fetch_assoc();
?>


<!-- Mulai Struktur Halaman -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            padding: 20px;
            color: #fff;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: #ecf0f1;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            padding: 6px;
            width: 100%;
            max-width: 400px;
        }
        .img-preview {
            margin-top: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #aaa;
            border-radius: 6px;
        }
        .btn-simpan {
            margin-top: 20px;
            background-color: #2980b9;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-simpan:hover {
            background-color: #1f618d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li><a href="?page=dashboard" style="color: #fff;">Beranda</a></li>

        </ul>
    </div>

    <div class="main-content">
        <h2>Edit Barang</h2>
        <form action="edit_barang.php?id=<?= $data['id'] ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
           <label>Nama:</label>
<input type="text" name="nama" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>

            <label>Jumlah:</label>
<input type="number" name="jumlah" value="<?php echo htmlspecialchars($data['jumlah']); ?>" required>

            <label>Ruangan:</label>
            <select name="id_ruangan" required>
                <?php
                $stmt_ruang = $koneksi->prepare("SELECT id, nama_ruangan FROM ruangan");
                $stmt_ruang->execute();
                $result_ruang = $stmt_ruang->get_result();
                while ($r = $result_ruang->fetch_assoc()) {
                    $selected = ($r['id'] == $data['id_ruangan']) ? 'selected="selected"' : '';
                    echo "<option value='" . htmlspecialchars($r['id']) . "' $selected>" . htmlspecialchars($r['nama_ruangan']) . "</option>";
                }
                $stmt_ruang->close();
                ?>
            </select>

            <label>Foto Saat Ini:</label><br>
            <?php if (!empty($data['foto'])): ?>
                <img src="uploads/<?= $data['foto'] ?>" class="img-preview">
            <?php else: ?>
                <p>(Tidak ada foto)</p>
            <?php endif; ?>

            <label>Ganti Foto (opsional):</label>
            <input type="file" name="foto">

            <button type="submit" class="btn-simpan">Simpan Perubahan</button>
        </form>
    </div>
</div>

</body>
</html>
