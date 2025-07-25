<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'dosen') {
    die("Akses ditolak.");
}
$conn = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
if (!function_exists('escape')) {
    function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama    = $_POST['nama_barang'];
    $jumlah  = $_POST['jumlah'];
    $ruangan = $_POST['id_ruangan'];
    $foto    = $_FILES['foto']['name'];

    move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);

    $stmt = $conn->prepare("INSERT INTO barang (nama_barang, jumlah, id_ruangan, foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $nama, $jumlah, $ruangan, $foto);
    $stmt->execute();

    header("Location: ?page=dashboard");
    exit;
}
$ruang = $conn->query("SELECT * FROM ruangan");
?>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f1f5f9;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #1e293b;
}

form {
    background: #ffffff;
    max-width: 500px;
    margin: 30px auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

form label {
    font-weight: 600;
    color: #334155;
}

form input[type="text"],
form input[type="number"],
form input[type="file"],
form select {
    width: 100%;
    padding: 10px 14px;
    margin-top: 6px;
    margin-bottom: 20px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    background-color: #f8fafc;
    transition: border-color 0.3s;
}

form input:focus,
form select:focus {
    outline: none;
    border-color: #3b82f6;
}

button[type="submit"] {
    background-color: #3b82f6;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #2563eb;
}
</style>
    <title>Tambah Barang</title>
</head>
<h2>Tambah Barang</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Nama:</label><br>
    <input type="text" name="nama_barang" required><br><br>

    <label>Jumlah:</label><br>
    <input type="number" name="jumlah" required><br><br>

    <label>Ruangan:</label><br>
    <select name="id_ruangan" required>
        <?php while ($r = $ruang->fetch_assoc()): ?>
            <option value="<?= $r['id'] ?>"><?= escape($r['nama_ruangan']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Foto:</label><br>
    <input type="file" name="foto" required><br><br>

    <button type="submit">Simpan</button>
</form>
