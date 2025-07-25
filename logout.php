<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_POST['confirm_logout'])) {
    echo '<form method="POST" style="text-align:center; margin-top:100px;">
        <h2>Anda yakin ingin logout?</h2>
        <button type="submit" name="confirm_logout" value="1">Logout</button>
        <a href="?page=dashboard" style="margin-left:20px;">Batal</a>
    </form>';
    exit;
}
session_destroy();
header("Location: ?page=login");
exit;
?>
