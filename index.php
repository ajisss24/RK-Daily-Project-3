<?php
require_once 'db.php';

// Prepare query target alumni
$stmt = $pdo->query("SELECT * FROM alumni ORDER BY status_pelacakan ASC");
$alumni_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelacakan Alumni</title>
    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container animate-fade-in">
        <header>
            <h1><i class="fas fa-search-location"></i> Alumni Tracker</h1>
            <div>
                <a href="add.php" class="btn btn-primary" id="addBtn">
                    <i class="fas fa-plus"></i> &nbsp;Tambah Data Alumni
                </a>
            </div>
        </header>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success">
            <strong>Sistem Selesai!</strong> Proses Pelacakan Data untuk "<?= htmlspecialchars($_GET['nama'] ?? '') ?>" telah selesai dijalankan.
        </div>
        <?php elseif(isset($_GET['status']) && $_GET['status'] == 'added'): ?>
        <div class="alert alert-success">
            <strong>Sukses!</strong> Data target alumni baru telah berhasil ditambahkan.
        </div>
        <?php endif; ?>

        <div class="glass-card">
            <h2>Daftar Target Alumni</h2>
            <p style="color: var(--text-muted); margin-top:-10px; margin-bottom: 20px;">
                Tabel berikut menampilkan data target alumni. Klik tombol <strong>Lacak</strong> untuk memproses pencarian per individu, atau klik tombol <strong>Detail</strong> untuk melihat bukti dan riwayat pelacakan.
            </p>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nomor ID</th>
                            <th>Nama Lengkap</th>
                            <th>Lulus - Prodi</th>
                            <th>Kota & Keilmuan</th>
                            <th>Pekerjaan / Lokasi Saat Ini</th>
                            <th>Status Pelacakan</th>
                            <th>Aksi Sistem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alumni_list as $al): ?>
                        <tr>
                            <td>#<?= $al['id'] ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($al['nama_lengkap']) ?></td>
                            <td><?= $al['tahun_lulus'] ?> - <?= htmlspecialchars($al['prodi']) ?></td>
                            <td>
                                <div><i class="fas fa-map-marker-alt" style="color:#f87171;"></i> <?= htmlspecialchars($al['kota']) ?></div>
                                <div style="font-size: 0.8em; color: var(--text-muted);"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($al['bidang_keilmuan']) ?></div>
                            </td>
                            <td>
                                <?php if($al['jabatan']): ?>
                                    <div style="font-weight: 500; font-size: 0.9em;"><?= htmlspecialchars($al['jabatan']) ?> di <?= htmlspecialchars($al['perusahaan']) ?></div>
                                    <div style="font-size: 0.8em; color: var(--text-muted);"><?= htmlspecialchars($al['lokasi']) ?></div>
                                <?php else: ?>
                                    <span style="color:var(--text-muted); font-size: 0.85em;">Belum ditarik datanya</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $badge = 'badge-neutral';
                                    if ($al['status_pelacakan'] == 'Teridentifikasi') $badge = 'badge-success';
                                    elseif ($al['status_pelacakan'] == 'Perlu Verifikasi Manual') $badge = 'badge-warning';
                                    elseif ($al['status_pelacakan'] == 'Belum Ditemukan') $badge = 'badge-danger';
                                ?>
                                <span class="badge <?= $badge ?>"><?= $al['status_pelacakan'] ?></span>
                            </td>
                            <td style="white-space: nowrap;">
                                <a href="tracker.php?id=<?= $al['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size:0.8rem; margin-right: 0.3rem;" title="Jalankan Proses Pelacakan">
                                    <i class="fas fa-satellite-dish"></i> Lacak
                                </a>
                                <a href="detail.php?id=<?= $al['id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size:0.8rem;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($alumni_list) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 2rem; color: var(--text-muted);">
                                Belum ada data target alumni. Silakan gunakan tombol Tambah Data Alumni.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
