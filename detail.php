<?php
require_once 'db.php';

// Pastikan parameter ID tersedia
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$alumni_id = $_GET['id'];

// Fetch Detail Alumni
$stmt = $pdo->prepare("SELECT * FROM alumni WHERE id = ?");
$stmt->execute([$alumni_id]);
$alumni = $stmt->fetch();

if (!$alumni) {
    die("Data Alumni tidak ditemukan.");
}

// Fetch Jejak Bukti
$stmtJejak = $pdo->prepare("SELECT * FROM jejak_bukti WHERE alumni_id = ? ORDER BY tanggal_ditemukan DESC");
$stmtJejak->execute([$alumni_id]);
$jejak_bukti = $stmtJejak->fetchAll();

// Fetch Riwayat Perubahan
$stmtRiwayat = $pdo->prepare("SELECT * FROM riwayat_perubahan WHERE alumni_id = ? ORDER BY tanggal_perubahan DESC");
$stmtRiwayat->execute([$alumni_id]);
$riwayat_perubahan = $stmtRiwayat->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelacakan - <?= htmlspecialchars($alumni['nama_lengkap']) ?></title>
    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 2fr;
            }
        }
        .info-group {
            margin-bottom: 1rem;
        }
        .info-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-size: 1rem;
            font-weight: 500;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--border-color);
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: var(--primary);
            border: 2px solid var(--bg-color);
        }
    </style>
</head>
<body>
    <div class="container animate-fade-in">
        <header>
            <h1><i class="fas fa-user-circle"></i> Profil & Detail Bukti Target</h1>
            <a href="index.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </header>

        <div class="grid">
            <!-- Left Column: User Profile Info -->
            <div class="glass-card" style="align-self: start;">
                <h2 style="margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    Identitas Entitas
                </h2>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <?php
                        $inisial_len = strlen($alumni['inisial']);
                        $font_size = $inisial_len > 4 ? '1.2rem' : ($inisial_len > 3 ? '1.4rem' : '1.8rem');
                    ?>
                    <div style="width: 80px; height: 80px; min-width: 80px; border-radius: 50%; background: linear-gradient(135deg, #818cf8, #34d399); display: flex; align-items: center; justify-content: center; font-size: <?= $font_size ?>; font-weight: bold; color: white; text-align: center; word-break: break-all; padding: 5px; box-sizing: border-box; line-height: 1;">
                        <?= htmlspecialchars($alumni['inisial']) ?>
                    </div>
                    <div>
                        <div style="font-size: 1.25rem; font-weight: 700;"><?= htmlspecialchars($alumni['nama_lengkap']) ?></div>
                        <?php 
                            $badge = 'badge-neutral';
                            if ($alumni['status_pelacakan'] == 'Teridentifikasi') $badge = 'badge-success';
                            elseif ($alumni['status_pelacakan'] == 'Perlu Verifikasi Manual') $badge = 'badge-warning';
                            elseif ($alumni['status_pelacakan'] == 'Belum Ditemukan') $badge = 'badge-danger';
                        ?>
                        <div style="margin-top: 5px;"><span class="badge <?= $badge ?>"><?= $alumni['status_pelacakan'] ?></span></div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">Program Studi - Tahun Lulus</div>
                    <div class="info-value"><?= htmlspecialchars($alumni['prodi']) ?> (<?= $alumni['tahun_lulus'] ?>)</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Lokasi Kampus / Asal</div>
                    <div class="info-value"><i class="fas fa-map-marker-alt" style="color:#f87171;"></i> <?= htmlspecialchars($alumni['kota']) ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Bidang Keahlian Target</div>
                    <div class="info-value"><i class="fas fa-briefcase" style="color:#34d399;"></i> <?= htmlspecialchars($alumni['bidang_keilmuan']) ?></div>
                </div>
                
                <h3 style="margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Status / Karir Saat Ini:</h3>
                
                <?php if($alumni['jabatan']): ?>
                    <div class="info-group">
                        <div class="info-label">Posisi / Jabatan</div>
                        <div class="info-value"><?= htmlspecialchars($alumni['jabatan']) ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Instansi / Perusahaan</div>
                        <div class="info-value"><?= htmlspecialchars($alumni['perusahaan']) ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Lokasi Bekerja</div>
                        <div class="info-value"><?= htmlspecialchars($alumni['lokasi']) ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><i class="far fa-clock"></i> <?= $alumni['tanggal_update'] ?></div>
                    </div>
                <?php else: ?>
                    <div class="alert" style="background-color: rgba(255,255,255,0.05); color: var(--text-muted);">
                        Belum ada data terekstrak yang tervalidasi.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Evidence & Histori -->
            <div>
                <!-- Jejak Bukti -->
                <div class="glass-card" style="margin-bottom: 1.5rem;">
                    <h2 style="margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-fingerprint" style="color: #818cf8;"></i> Jejak Bukti Penemuan
                    </h2>
                    
                    <?php if (count($jejak_bukti) > 0): ?>
                        <div style="overflow-x: auto;">
                            <table style="margin-top: 0;">
                                <thead>
                                    <tr>
                                        <th>Sumber</th>
                                        <th>Ringkasan Terekstrak</th>
                                        <th>Confidence Score</th>
                                        <th>Pointer Cek</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($jejak_bukti as $bukti): ?>
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);">
                                                <i class="fas fa-globe"></i> <?= htmlspecialchars($bukti['sumber_temuan']) ?>
                                            </span>
                                        </td>
                                        <td style="font-size: 0.85rem; color: #cbd5e1; max-width: 200px; padding-right: 1.5rem;">
                                            <?php 
                                            // Decode JSON untuk tampilan yang lebih rapi
                                            $ringkasan = json_decode($bukti['ringkasan_info'], true);
                                            if($ringkasan) {
                                                echo "<strong>Jabatan:</strong> " . htmlspecialchars($ringkasan['jabatan']) . "<br>";
                                                echo "<strong>Instansi:</strong> " . htmlspecialchars($ringkasan['instansi']) . "<br>";
                                                echo "<strong>Lokasi:</strong> " . htmlspecialchars($ringkasan['lokasi']);
                                            } else {
                                                echo htmlspecialchars($bukti['ringkasan_info']);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            // Batasi Tampilan Skor Maksimal 100 Untuk Data Lama
                                            $score = min(100, $bukti['confidence_score']);
                                            $barColor = $score >= 70 ? '#10b981' : ($score >= 40 ? '#f59e0b' : '#ef4444');
                                            ?>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span style="font-weight: 600; color: <?= $barColor ?>"><?= $score ?>%</span>
                                                <div style="flex:1; height: 6px; background-color: rgba(255,255,255,0.1); border-radius: 3px; min-width: 50px;">
                                                    <div style="height: 100%; border-radius: 3px; background-color: <?= $barColor ?>; width: <?= $score ?>%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size: 0.8rem; color: #cbd5e1; max-width: 250px; padding-right: 1.5rem; word-break: break-word;">
                                            <?php 
                                            $pointer = json_decode($bukti['pointer_bukti'], true);
                                            if($pointer) {
                                                echo "<strong>Judul:</strong> <a href='" . htmlspecialchars($pointer['link']) . "' style='color:#60a5fa;' target='_blank'>" . htmlspecialchars($pointer['judul']) . "</a><br>";
                                                echo "<span style='font-size:0.75rem; color:var(--text-muted);'>" . htmlspecialchars($pointer['snippet']) . "</span>";
                                            } else {
                                                echo htmlspecialchars($bukti['pointer_bukti']);
                                            }
                                            ?>
                                        </td>
                                        <td style="font-size: 0.85rem; white-space: nowrap; vertical-align: top;">
                                            <?= $bukti['tanggal_ditemukan'] ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 1rem; text-align: center; color: var(--text-muted); background: rgba(0,0,0,0.2); border-radius: 0.5rem; border: 1px dashed var(--border-color);">
                            Belum ada jejak bukti ditemukan. Jalankan pelacak (Scheduler).
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Riwayat Perubahan -->
                <div class="glass-card">
                    <h2 style="margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-history" style="color: #34d399;"></i> Histori Tracking
                    </h2>
                    
                    <?php if (count($riwayat_perubahan) > 0): ?>
                        <div class="timeline">
                            <?php foreach($riwayat_perubahan as $riwayat): ?>
                                <?php 
                                    // Parse JSON if possible for display
                                    $lama = json_decode($riwayat['data_lama'], true);
                                    $baru = json_decode($riwayat['data_baru'], true);
                                ?>
                                <div class="timeline-item">
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                                        <?= date('d M Y, H:i:s', strtotime($riwayat['tanggal_perubahan'])) ?>
                                    </div>
                                    <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                                        <div style="font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem;">
                                            Status berubah dari 
                                            <span class="badge badge-neutral" style="font-size: 0.7rem;"><?= $lama ? $lama['status_pelacakan'] : 'Unknown' ?></span> 
                                            ke 
                                            <span class="badge badge-success" style="font-size: 0.7rem;"><?= $baru ? $baru['status_pelacakan'] : 'Unknown' ?></span>
                                        </div>
                                        <div style="font-size: 0.85rem; color: #cbd5e1; display:flex; gap: 1rem;">
                                            <div style="flex:1;">
                                                <strong style="color: #f87171;">Data Lama:</strong><br>
                                                Jabatan: <?= $lama['jabatan'] ?? '-' ?><br>
                                                Instansi: <?= $lama['perusahaan'] ?? '-' ?>
                                            </div>
                                            <div style="flex:1;">
                                                <strong style="color: #34d399;">Data Baru:</strong><br>
                                                Jabatan: <?= $baru['jabatan'] ?? '-' ?><br>
                                                Instansi: <?= $baru['perusahaan'] ?? '-' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 1rem; text-align: center; color: var(--text-muted); background: rgba(0,0,0,0.2); border-radius: 0.5rem; border: 1px dashed var(--border-color);">
                            Belum ada riwayat perubahan data (Snapshot status).
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
