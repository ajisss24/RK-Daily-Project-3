<?php
// db.php - Koneksi ke Database MySQL
$host = 'localhost';
$dbname = 'alumni_tracker';
$username = 'root'; // Sesuaikan jika menggunakan password
$password = ''; // Kosongkan jika XAMPP default

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ubah fetch mode default ke FETCH_ASSOC
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>
