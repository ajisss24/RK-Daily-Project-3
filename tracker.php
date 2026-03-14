<?php
// tracker.php - Core Logic: Implementasi 12 Langkah Pseudocode
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$alumni_id_to_track = $_GET['id'];

// Simulasi Logika Scraper (Langkah 5, diletakkan di atas sebagai helper function)
function mock_scrape_internet($queries, $sumber, $nama_lengkap) {
    // Mocking hasil berdasarkan nama target untuk mendalami test case
    $hasil = [];
    if(stripos($nama_lengkap, 'Azis Disha Rono Suryo') !== false) {
        $hasil[] = ['sumber' => 'LinkedIn', 'link' => 'linkedin.com/in/azisdisha', 'judul' => 'Azis Disha Rono Suryo - Software Engineer', 'snippet' => 'Lulusan UMM, bekerja di PT Digital Teknologi sejak 2021.', 'tanggal' => '2023-01-01'];
        $hasil[] = ['sumber' => 'GitHub', 'link' => 'github.com/azisdisha', 'judul' => 'Azis Disha Rono Suryo', 'snippet' => 'Universitas Muhammadiyah Malang. Software Eng.', 'tanggal' => '2023-05-12'];
    } elseif (stripos($nama_lengkap, 'Alfarizi Hardiansyah') !== false) {
        $hasil[] = ['sumber' => 'Google Scholar', 'link' => 'scholar.google.com/alfarizi', 'judul' => 'Analisis Data Cerdas - Alfarizi Hardiansyah', 'snippet' => 'Peneliti Universitas Muhammadiyah Malang.', 'tanggal' => '2022-08-11'];
    } elseif (stripos($nama_lengkap, 'Govin Riofany Luthfi') !== false) {
        $hasil[] = ['sumber' => 'Google Search', 'link' => 'google.com/search?q=govin', 'judul' => 'Profil Govin Riofany Luthfi', 'snippet' => 'Network Engineer di Perusahaan Telekomunikasi Jakarta.', 'tanggal' => '2022-11-09'];
    } elseif (stripos($nama_lengkap, 'Muchammad Dwi Ferdi') !== false) {
        $hasil[] = ['sumber' => 'Facebook', 'link' => 'facebook.com/ferdidwi', 'judul' => 'Ferdi Ilham', 'snippet' => 'Bekerja di Sidoarjo.', 'tanggal' => '2023-01-05'];
    } else {
        // Fallback untuk nama apa saja yang dicoba oleh user (Misal: Ahmad Fauzi)
        $hasil[] = ['sumber' => 'LinkedIn', 'link' => 'linkedin.com/in/' . strtolower(str_replace(' ', '', $nama_lengkap)), 'judul' => $nama_lengkap . ' - Profesional', 'snippet' => 'Lulusan Universitas Muhammadiyah Malang, kini berkarir di bidang teknologi.', 'tanggal' => '2023-10-10'];
        $hasil[] = ['sumber' => 'Google Scholar', 'link' => 'scholar.google.com/?q=' . urlencode($nama_lengkap), 'judul' => 'Publikasi Jurnal oleh ' . $nama_lengkap, 'snippet' => 'Universitas Muhammadiyah Malang. Peneliti aktif.', 'tanggal' => '2022-04-12'];
    }
    return $hasil;
}

function Simpan_Jejak_Bukti($pdo, $alumni_id, $kandidat_terpilih) {
    $stmt = $pdo->prepare("INSERT INTO jejak_bukti (alumni_id, sumber_temuan, ringkasan_info, confidence_score, tanggal_ditemukan, pointer_bukti) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($kandidat_terpilih as $k) {
        if($k['status_kandidat'] == 'Tidak Cocok') continue;
        $ringkasan = json_encode(['jabatan' => $k['jabatan'], 'instansi' => $k['instansi'], 'lokasi' => $k['lokasi']], JSON_UNESCAPED_SLASHES);
        $pointer = json_encode(['judul' => $k['judul_asli'], 'link' => $k['link_profil'], 'snippet' => $k['snippet_asli']], JSON_UNESCAPED_SLASHES);
        $stmt->execute([
            $alumni_id,
            $k['sumber'],
            $ringkasan,
            $k['skor'],
            date('Y-m-d'),
            $pointer
        ]);
    }
}

function Simpan_Riwayat_Perubahan($pdo, $alumni_id, $data_lama, $data_baru) {
    $lama_json = json_encode($data_lama);
    $baru_json = json_encode($data_baru);
    if ($lama_json !== $baru_json) {
        $stmt = $pdo->prepare("INSERT INTO riwayat_perubahan (alumni_id, data_lama, data_baru, tanggal_perubahan) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$alumni_id, $lama_json, $baru_json]);
    }
}

// AMBIL SATU ALUMNI (Revisi: Tracking per alumni)
$stmt_get = $pdo->prepare("SELECT * FROM alumni WHERE id = ?");
$stmt_get->execute([$alumni_id_to_track]);
$database_alumni = $stmt_get->fetchAll();

// 1. Persiapan Profil Target Alumni
foreach ($database_alumni as $data_alumni) {
    
    $profil_target = [];
    $profil_target['nama_variasi'] = [
        $data_alumni['nama_lengkap'],
        $data_alumni['inisial'],
        $data_alumni['inisial'] . ' ' . $data_alumni['nama_lengkap']
    ];
    $profil_target['afiliasi'] = ["Universitas Muhammadiyah Malang", "UMM", $data_alumni['prodi']];
    $profil_target['konteks'] = [$data_alumni['tahun_lulus'], $data_alumni['kota'], $data_alumni['bidang_keilmuan']];

    $alumni_id = $data_alumni['id'];

    // 2. Menentukan Sumber Pelacakan
    $daftar_sumber = ['Google Search', 'LinkedIn', 'Google Scholar', 'ResearchGate', 'ORCID', 'GitHub', 'Kaggle', 'Instagram', 'Facebook', 'Website Perusahaan', 'Direktori Kampus'];

    // 4. Membuat Query Pencarian
    $daftar_query = [];
    $nama = $data_alumni['nama_lengkap'];
    $prodi = $data_alumni['prodi'];
    $kota = $data_alumni['kota'];
    $pekerjaan = $data_alumni['bidang_keilmuan'];

    $daftar_query[] = "$nama Universitas Muhammadiyah Malang";
    $daftar_query[] = "$nama $prodi UMM";
    $daftar_query[] = "$nama site:scholar.google.com";
    $daftar_query[] = "$nama site:linkedin.com";
    $daftar_query[] = "$nama $pekerjaan $kota";

    // 5. Mengambil Hasil Pencarian
    $kandidat_hasil = mock_scrape_internet($daftar_query, $daftar_sumber, $nama);

    // 6. Ekstraksi Informasi Kandidat
    $sinyal_identitas = [];
    foreach ($kandidat_hasil as $hasil) {
        $sinyal = [
            'nama' => (stripos($hasil['judul'], $nama) !== false || stripos($nama, explode(' ', $hasil['judul'])[0]) !== false) ? $nama : 'Unknown',
            'instansi' => (stripos($hasil['snippet'], 'UMM') !== false || stripos($hasil['snippet'], 'Universitas Muhammadiyah Malang') !== false) ? 'UMM / Perusahaan' : 'Perusahaan Lain',
            'jabatan' => $pekerjaan,
            'lokasi' => (stripos($hasil['snippet'], $kota) !== false || stripos($hasil['snippet'], 'Jakarta') !== false || stripos($hasil['snippet'], 'Sidoarjo') !== false) ? $kota : '',
            'bidang_keahlian' => $pekerjaan,
            'tahun_aktivitas' => date('Y', strtotime($hasil['tanggal'])),
            'link_profil' => $hasil['link'],
            'sumber' => $hasil['sumber'],
            'judul_asli' => $hasil['judul'],
            'snippet_asli' => $hasil['snippet']
        ];
        $sinyal_identitas[] = $sinyal;
    }

    // 7. Disambiguasi (Menghindari Nama Sama)
    foreach ($sinyal_identitas as &$kandidat) {
        $skor = 0;
        // Penyesuaian pengecekan parsial nama
        if (stripos($kandidat['nama'], $data_alumni['nama_lengkap']) !== false || stripos($data_alumni['nama_lengkap'], $kandidat['nama']) !== false ) {
            $skor += 40;
        }
        if (stripos($kandidat['instansi'], 'UMM') !== false || in_array($kandidat['instansi'], $profil_target['afiliasi'])) {
            $skor += 30;
        }
        if ($kandidat['tahun_aktivitas'] >= $data_alumni['tahun_lulus'] || $kandidat['tahun_aktivitas'] > 2000) {
            $skor += 15;
        }
        if (stripos($kandidat['bidang_keahlian'], $data_alumni['bidang_keilmuan']) !== false) {
            $skor += 15;
        }

        if ($skor >= 70) {
            $kandidat['status_kandidat'] = "Kemungkinan Kuat";
        } elseif ($skor >= 40) {
            $kandidat['status_kandidat'] = "Perlu Verifikasi";
        } else {
            $kandidat['status_kandidat'] = "Tidak Cocok";
        }
        $kandidat['skor'] = $skor;
    }

    // 9. Cross Validation Antar Sumber
    $sumber_tercatat = [];
    foreach ($sinyal_identitas as &$kandidat) {
        $sumber_tercatat[] = $kandidat['sumber'];
    }
    $sumber_unik = array_unique($sumber_tercatat);
    
    foreach ($sinyal_identitas as &$kandidat) {
        if (count($sumber_unik) >= 2 && $kandidat['status_kandidat'] != 'Tidak Cocok') {
            $kandidat['skor'] += 20;
        }
        
        // Membatasi nilai skor maksimal 100
        if ($kandidat['skor'] > 100) {
            $kandidat['skor'] = 100;
        }
    }

    // 8. Menentukan Status Alumni
    $status_baru = "Belum Ditemukan";
    $data_update = [];
    $ada_kuat = false;
    $ada_verif = false;
    $kandidat_terpilih = [];

    foreach ($sinyal_identitas as $kandidat) {
        if ($kandidat['status_kandidat'] == 'Kemungkinan Kuat') {
            $ada_kuat = true;
            $kandidat_terpilih[] = $kandidat;
            $data_update = [
                'jabatan' => $kandidat['jabatan'],
                'perusahaan' => 'Tracking Analytics (Mocked)',
                'lokasi' => $kandidat['lokasi'] ?: $data_alumni['kota']
            ];
        } elseif ($kandidat['status_kandidat'] == 'Perlu Verifikasi' && !$ada_kuat) {
            $ada_verif = true;
            $kandidat_terpilih[] = $kandidat;
        }
    }

    if ($ada_kuat) {
        $status_baru = "Teridentifikasi";
    } elseif ($ada_verif) {
        $status_baru = "Perlu Verifikasi Manual";
    }

    $data_lama = [
        'status_pelacakan' => $data_alumni['status_pelacakan'],
        'jabatan' => $data_alumni['jabatan'],
        'perusahaan' => $data_alumni['perusahaan'],
        'lokasi' => $data_alumni['lokasi']
    ];
    
    $data_baru_snapshot = [
        'status_pelacakan' => $status_baru,
        'jabatan' => $data_update['jabatan'] ?? $data_alumni['jabatan'],
        'perusahaan' => $data_update['perusahaan'] ?? $data_alumni['perusahaan'],
        'lokasi' => $data_update['lokasi'] ?? $data_alumni['lokasi']
    ];

    // 12. Integrasi dengan Sistem Pelacakan
    if (count($kandidat_terpilih) > 0) {
        Simpan_Jejak_Bukti($pdo, $alumni_id, $kandidat_terpilih);
        Simpan_Riwayat_Perubahan($pdo, $alumni_id, $data_lama, $data_baru_snapshot);
    }

    // Simpan Profil Update
    $q_update = "UPDATE alumni SET status_pelacakan = ?, tanggal_update = NOW()";
    $params = [$status_baru];
    
    if (!empty($data_update)) {
        $q_update .= ", jabatan = ?, perusahaan = ?, lokasi = ?";
        $params[] = $data_update['jabatan'];
        $params[] = $data_update['perusahaan'];
        $params[] = $data_update['lokasi'];
    }
    $q_update .= " WHERE id = ?";
    $params[] = $alumni_id;
    
    $stmt_upd = $pdo->prepare($q_update);
    $stmt_upd->execute($params);
}

// Redirect dengan notifikasi nama
$nama_enc = urlencode($data_alumni['nama_lengkap']);
header("Location: index.php?status=success&nama=" . $nama_enc);
exit;
?>
