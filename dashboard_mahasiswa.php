
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Cek login & role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mahasiswa') {
    echo "<p>Harus login sebagai mahasiswa.</p>";
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "inventaris_kampus");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

// Fungsi escape
if (!function_exists('escape')) {
    function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
?>

<style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        margin: 0;
        background: #f0f2f5;
    }

    .container {
        display: flex;
        height: 100vh;
    }

    .sidebar {
        width: 200px;
        background: linear-gradient(180deg, #1976d2, #2196f3);
        color: white;
        padding: 20px 10px;
    }

    .sidebar a {
        display: block;
        margin: 12px 0;
        padding: 10px;
        text-decoration: none;
        color: white;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        text-align: center;
        transition: background 0.3s;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.3);
    }

    .main {
        flex: 1;
        padding: 25px;
        background: #fff;
        overflow-y: auto;
        border-left: 1px solid #ddd;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header img {
        margin-left: 12px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .header img:hover {
        transform: scale(1.1);
    }

    .search-bar {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    button.cari {
        padding: 10px 20px;
        background: #1976d2;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button.cari:hover {
        background: #0d47a1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 12px;
        border: 1px solid #e0e0e0;
        text-align: center;
    }

    th {
        background-color: #e3f2fd;
        font-weight: bold;
    }

    tr:hover {
        background-color: #f1f9ff;
    }

       img.img-preview {
        width: 80px;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
      .user-icon {
    width: 26px;
    height: 26px;
    margin-left: 20px;
    vertical-align: middle;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.2s;
}
.user-icon:hover {
    transform: scale(1.1);
}

</style>

<div class="container">
    <div class="sidebar">
        <h3 style="text-align: center;">Menu</h3>
        <a href="?page=dashboard">Dashboard</a>
        <a href="?page=about">About</a>
        <a href="?page=logout">Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h2>Data Inventaris Barang</h2>
            <div>
                <img src="gear.png" alt="setting" class="gear" width="22" height="22" style="vertical-align:middle;">
                <img src="user-icon.png" alt="user" class="user-icon" width="22" height="22" style="vertical-align:middle; position:relative; top:11px;">
            </div>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Cari barang...">
            <button class="cari">Cari</button>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Foto Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Lokasi Barang</th>
                <th>Pinjam Barang</th>
            </tr>

            <?php
            $no = 1;
            $query = "SELECT barang.*, ruangan.nama_ruangan FROM barang 
                      JOIN ruangan ON barang.id_ruangan = ruangan.id";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $no++ . '</td>';
                    echo '<td><img src="uploads/' . escape($row['foto']) . '" class="img-preview" alt="Foto"></td>';
                    echo '<td>' . escape($row['nama_barang']) . '</td>';
                    echo '<td>' . escape($row['jumlah']) . '</td>';
                    echo '<td>' . escape($row['nama_ruangan']) . '</td>';
                    echo '<td>';
                    echo '<form method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="pinjam_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="pinjam" class="cari">Pinjam Barang</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">Data tidak ditemukan.</td></tr>';
            }

            // Proses pinjam barang
            if (isset($_POST['pinjam']) && isset($_POST['pinjam_id'])) {
                echo '<script>alert("Barang berhasil dipinjam. Silakan hubungi admin!");</script>';
            }
            ?>
        </table>
    </div>
</div>
