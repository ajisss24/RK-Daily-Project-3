<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_lengkap'];
    $inisial = $_POST['inisial'];
    $prodi = $_POST['prodi'];
    $tahun = $_POST['tahun_lulus'];
    $kota = $_POST['kota'];
    $bidang = $_POST['bidang_keilmuan'];

    $stmt = $pdo->prepare("INSERT INTO alumni (nama_lengkap, inisial, prodi, tahun_lulus, kota, bidang_keilmuan, waktu_sekarang) VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
    $stmt->execute([$nama, $inisial, $prodi, $tahun, $kota, $bidang]);

    header("Location: index.php?status=added");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Alumni Target</title>
    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: rgba(0,0,0,0.2);
            color: white;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
            background: rgba(0,0,0,0.4);
        }
        input::placeholder {
            color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <div class="container animate-fade-in" style="max-width: 600px; padding-top: 4rem;">
        <header>
            <h1><i class="fas fa-user-plus"></i> Tambah Target Alumni Baru</h1>
        </header>

        <div class="glass-card">
            <form action="add.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Azis Disha Rono Suryo">
                </div>
                <div class="form-group">
                    <label>Inisial Nama (Opsional)</label>
                    <input type="text" name="inisial" placeholder="Contoh: ADRS">
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <input type="text" name="prodi" required placeholder="Contoh: Teknik Informatika">
                </div>
                <div class="form-group">
                    <label>Tahun Lulus (Atau Perkiraan)</label>
                    <input type="number" name="tahun_lulus" required placeholder="Contoh: 2024">
                </div>
                <div class="form-group">
                    <label>Kota Asal / Domisili / Wilayah Kerja</label>
                    <input type="text" name="kota" required placeholder="Contoh: Malang">
                </div>
                <div class="form-group">
                    <label>Bidang Keahlian / Peminatan</label>
                    <input type="text" name="bidang_keilmuan" required placeholder="Contoh: Software Engineering">
                </div>
                
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0 1.5rem 0;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <a href="index.php" class="btn btn-outline" style="flex: 1; justify-content: center; padding: 0.8rem; font-size: 1rem;">
                        <i class="fas fa-arrow-left"></i> &nbsp;Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center; padding: 0.8rem; font-size: 1rem;">
                        <i class="fas fa-save"></i> &nbsp;Simpan Data Alumni
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
