# Alumni Tracking System

Proyek ini adalah implementasi sistem pelacakan alumni berbasis *web* menggunakan PHP murni dan MySQL, berlandaskan 12 Langkah Pseudocode Aplikasi pada Daily Project 2 Mata Kuliah Rekayasa Kebutuhan. Sistem ini dirancang untuk menyelesaikan tugas Daily Project 3 Mata Kuliah Rekayasa Kebutuhan.

## ✨ Fitur Sesuai Pseudocode
Sistem memuat simulasi logika dan penerapan algoritma dari 12 langkah pseudocode secara berurutan saat tracker diaktifkan.

1. **Persiapan Target (Langkah 1)** - Profil alumni diambil dan dirangkai variasinya.
2. **Penentuan Sumber (Langkah 2)** - Variabel daftar sumber telah didefinisikan (Google, LinkedIn, dll).
3. **Scheduler Pelacakan (Langkah 3)** - Simulasi jadwal 7 hari diperiksa.
4. **Query Builder (Langkah 4)** - String query dibentuk untuk persiapan _scraping_.
5. **Mengambil Hasil (Langkah 5)** - Sistem melakukan pengambilan data (_mock network layer/dummy response_).
6. **Ekstraksi Sinyal (Langkah 6)** - Memparsing identitas dan info dari teks kembalian _scraper_.
7. **Disambiguasi Profil (Langkah 7)** - Perhitungan _scoring_ kecocokan entitas (≥70 Kemungkinan Kuat, ≥40 Perlu Verif).
8. **Penentuan Status (Langkah 8)** - Penjadwalan perubahan status alumni (Teridentifikasi / Verifikasi / Tidak Ditemukan).
9. **Cross Validasi (Langkah 9)** - Menambahkan +20 poin _confidence score_ jika ditemukan pada >1 sumber.
10. **Jejak Bukti (Langkah 10)** - Meyimpan tabel pointer info dan sumber ke `jejak_bukti`.
11. **Tracking History (Langkah 11)** - Mencatat Snapshot perubahan status (Data Lama vs Baru).
12. **Integrasi Simpan (Langkah 12)** - Prosedur pemanggilan penyatuan data di titik akhir.

---

## 📂 Struktur Folder Project

```text
alumni_tracker/
│
├── add.php          # Halaman Form untuk menambahkan data (Target) alumni baru ke dalam sistem
├── database.sql     # Script SQL pembentukan tabel & dummy data target (Wajib di-import)
├── db.php           # Konfigurasi koneksi PDO ke MySQL Database
├── detail.php       # Halaman untuk melihat detail alumni, jejak bukti, & riwayat perubahan status
├── index.php        # Halaman Dashboard Utama (Menampilkan daftar alumni & status)
├── README.md        # File dokumentasi aplikasi (File ini)
├── style.css        # File Styling UI Dashboard agar lebih dinamis dan rapi
└── tracker.php      # Berisi 12 tahap pseudocode algoritma pencarian per target
```

## 🚀 Cara Menjalankan Aplikasi (VS Code + Laragon + HeidiSQL)
1. Buka folder *project* ini (`alumni_tracker`) langsung menggunakan aplikasi **Visual Studio Code (VS Code)**.
2. Buka aplikasi **Laragon** di komputer Anda, lalu klik tombol **Start All**. Pastikan modul **MySQL** menyala (running).
3. Klik tombol **Database** di Laragon untuk membuka **HeidiSQL**. Klik *Open* untuk masuk.
4. Di panel sebelah kiri HeidiSQL, klik kanan (atau klik kosong) area list database `->` **Create new** `->` **Database**.
5. Beri nama databasenya `alumni_tracker`, lalu klik OK. Klik database `alumni_tracker` tersebut di bagian daftar panel kiri agar aktif.
6. Buka menu **File `->` Load SQL file...** (`Ctrl+O`), dan cari lalu pilih file `database.sql` yang ada di dalam *project* ini.
7. Jalankan *script sql* dengan menekan tombol **Execute** (ikon ▶️ warna biru) atau tekan tombol `F9` pada *keyboard*.
8. Kembali ke layar VS Code. Buka panel terminal baru dengan `Ctrl + \`` (atau menu **Terminal `->` New Terminal**).
9. Ketik perintah berikut dan tekan Enter: `php -S localhost:8000`
10. Buka Browser. Akses URL: `http://localhost:8000/`. Dashboard pencarian alumni sudah siap digunakan!

---

## 🧪 Tabel Pengujian Aplikasi (Kualitas Perangkat Lunak)

Sesuai **Desain Daily Project 2**, berikut disajikan hasil pengujian fungsionalitas dan non-fungsionalitas aplikasi.

| Step | Pseudocode                                             | Fitur yang Diuji                                               | Input                                                           | Output yang Diharapkan                                                                        | Hasil Uji                                                                                                       | Status |
|:----:|--------------------------------------------------------|----------------------------------------------------------------|-----------------------------------------------------------------|-----------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------|:------:|
| 1    | Persiapan Profil Target Alumni                         | Pengambilan data dan perangkaian string variasi nama/afiliasi  | Data Alumni dari Database                                       | Target tersimpan dengan struktur nama, afiliasi, dan konteks.                                 | Profile target terbentuk sempurna dengan semua variasi tersimpan di *array memory*.                             | ✅      |
| 2    | Menentukan Sumber Pelacakan                            | Menyiapkan *list/array* dari *platform* sumber                 | *Hardcoded Array* daftar target pencarian                       | Daftar *Platform* dideklarasikan di memori (*Google*, *LinkedIn*, *Scholar*, dll)             | Variabel sumber *platform* siap dan *array* dialokasikan dengan benar.                                          | ✅      |
| 3    | Menjalankan Scheduler Pelacakan                        | Validas waktu jeda 7 hari untuk memicu pencarian               | Waktu *Current* (Hari Ini) vs Terakhir Diperbarui target        | Algoritma mengeksekusi sistem jika sudah saatnya.                                             | *Trigger button* secara manual mensimulasikan *event* penjadwalan ini dan fungsi tereksekusi.                    | ✅      |
| 4    | Membuat Query Pencarian                                | Pembentukan *String Query* Spesifik                            | Variasi Profil Target                                           | Array 5 *Query* Pencarian (contoh: "Nama UMM", "Nama LinkedIn")                               | *Array Query List* berhasil di-*generate*.                                                                      | ✅      |
| 5    | Mengambil Hasil Pencarian                              | Pengambilan hasil _scraping_ (_Mock_) HTTP request             | Parameter *array* daftar query & sumber                         | _Array_ URL, Judul, Tanggal, _Snippet_ dari sumber dikembalikan secara terstruktur.             | _Array Candidate Entity_ berhasil dikumpulkan via _function_ pengambilan _mock_.                                  | ✅      |
| 6    | Ekstraksi Informasi Kandidat                           | Membedah teks mentah web/snippet pencarian API menjadi *Key*   | Hasil Mentah Entitas Pencarian                                  | Data Kandidat memiliki format: nama, instansi, jabatan, dll.                                  | _String Extractor parsing_ terakumulasi menjadi *key* informasi kandidat.                                       | ✅      |
| 7    | Disambiguasi (Menghindari Nama Sama)                   | Perhitungan Algoritma _Scoring_ Identitas                      | *Key* Ekstraksi Informasi Kandidat                              | Penambahan bobot persentase kecocokan nama, kampus, dan prodi.                                | Sistem berhasil menentukan angka *Skor Kandidat*.                                                               | ✅      |
| 8    | Menentukan Status Alumni                               | Kategorisasi berdasarkan *Threshold Score*                     | Angka *Skor Kandidat* vs Ambang Batas 70 / 40                   | Label Status Kandidat sebagai (Kemungkinan Kuat, Perlu Verifikasi, Tidak Cocok)               | Sistem dengan akurat menyematkan status berdsarkan klasifikasi *if/elseif_.                                     | ✅      |
| 9    | Cross Validation Antar Sumber                          | Akumulasi Skor Validasi Lintas Platform                        | Status Kandidat dan daftar sumber ditemukannya kandidat         | Status _confidence score_ kandidat bertambah +20 jika ditemukan di ≥2 _platform_ berbeda.       | _Confidence score_ bertambah terbukti di saat proses *cross-check source* berjalan.                             | ✅      |
| 10   | Penyimpanan Hasil Pelacakan sebagai "Jejak Bukti"      | Integritas pengisian MySQL Record Jejak Bukti                  | *Array* Kandidat Terpilih (Lolos Seleksi Disambiguasi)            | Tersimpan rapi ke dalam *table* `jejak_bukti`.                                                | Bukti pencarian tervalidasi dan bisa dilihat langsung di URL `detail.php` di panel web.                         | ✅      |
| 11   | Penyimpanan Riwayat Perubahan (Tracking History)       | _Version Control_ Status Tracking                              | Data *Snapshot* Lama VS *Snapshot* Terupdate Alumni Target        | Menyimpan ke *table* `riwayat_perubahan` jika terjadi beda status pelacakan.                  | Riwayat transisi dari "Belum Dilacak" -> "Teridentifikasi" ter-log masuk DB `riwayat` dengan baik.                | ✅      |
| 12   | Integrasi dengan Sistem Pelacakan                      | Penggabungan Seluruh Modul dengan Transaksi Simpan             | Seluruh Prosedur di atas dijalankan dalam satu blok             | Meng-*Update* *Record Data Alumni* Induk dan memanggil semua *Sub-System Evidence*.           | Pelacakan berjalan penuh 1-siklus dari *Fetch DB* -> Evaluasi -> *Update Detail & Dashboard*.                   | ✅      |
