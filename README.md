# SIM UKK (Sistem Informasi Manajemen Uji Kompetensi Keahlian)

**SIM UKK** adalah aplikasi berbasis web yang dikembangkan untuk memfasilitasi proses pelaksanaan Uji Kompetensi Keahlian (UKK) di Sekolah Menengah Kejuruan (SMK).

Dibangun menggunakan framework **Laravel**, sistem ini mendigitalkan alur administrasi, mulai dari pendataan peserta dan penguji, pengelolaan standar kompetensi, penilaian (asesmen), hingga pelaporan dan pencetakan sertifikat kompetensi.

## 📖 Tentang Aplikasi

Proyek ini bertujuan untuk menggantikan atau melengkapi proses administrasi UKK yang konvensional. Dengan sistem terintegrasi, sekolah dapat mengelola data ujian dengan lebih terstruktur, transparan, dan efisien. Aplikasi ini membagi alur kerja ke dalam beberapa peran pengguna (*multi-role*) untuk memastikan setiap pihak (Admin, Penguji, dan Siswa) mendapatkan akses sesuai tugasnya.

## ✨ Fitur Utama

Aplikasi ini mencakup berbagai modul fungsional:

### 1. Manajemen Data Master (Admin)
* **Manajemen Jurusan & Kelas:** Pengaturan data kompetensi keahlian.
* **Manajemen Pengguna:** Pengelolaan akun untuk Admin, Penguji (Assessor), dan Siswa.
* **Import Data:** Fitur untuk mengunggah data siswa atau pengguna secara massal menggunakan Excel.

### 2. Standar Kompetensi
* **Unit Kompetensi:** Mengelola standar kompetensi, elemen kompetensi, dan kriteria penilaian sesuai kurikulum yang berlaku.
* **Mapping Penguji:** Menentukan penguji (assessor) yang bertanggung jawab untuk standar atau jurusan tertentu.

### 3. Pelaksanaan Ujian & Asesmen
* **Penjadwalan:** Pengaturan jadwal pelaksanaan UKK.
* **Proses Penilaian (Assessor):** Antarmuka khusus bagi penguji untuk memberikan nilai kepada siswa berdasarkan elemen kompetensi yang diuji.
* **Status Kelulusan:** Penentuan status kompeten atau belum kompeten berdasarkan hasil penilaian.

### 4. Pelaporan & Sertifikat
* **Rekap Nilai:** Laporan hasil ujian siswa secara keseluruhan.
* **Cetak Sertifikat:** Fitur bagi siswa yang lulus untuk melihat atau mencetak sertifikat kompetensi keahlian mereka.
* **Ekspor Laporan:** Unduh data laporan hasil ujian ke dalam format Excel.

### 5. Hak Akses Bertingkat
* **Administrator:** Kontrol penuh atas pengaturan sistem, data induk, dan laporan.
* **Assessor (Penguji):** Fokus pada penilaian siswa dan melihat daftar peserta ujian.
* **Student (Siswa):** Akses untuk melihat jadwal, profil diri, hasil ujian, dan sertifikat.

## 💻 Teknologi

Aplikasi ini dibangun menggunakan teknologi web modern yang stabil:
* **Framework:** Laravel (PHP)
* **Database:** MySQL
* **Frontend:** Blade Templates dengan Bootstrap/Tailwind CSS
* **Fitur Pendukung:** SweetAlert (Notifikasi), ApexCharts (Visualisasi Data), Maatwebsite Excel (Export/Import).
