-- Database: medical_website


-- Tabel Admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Dokter
CREATE TABLE dokter (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    spesialisasi VARCHAR(100) NOT NULL,
    gelar VARCHAR(50),
    deskripsi TEXT,
    foto VARCHAR(255),
    total_pasien INT DEFAULT 0,
    total_sertifikat INT DEFAULT 0,
    total_penghargaan INT DEFAULT 0,
    telepon VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Sertifikat Dokter
CREATE TABLE sertifikat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dokter_id INT,
    nama_sertifikat VARCHAR(200) NOT NULL,
    institusi VARCHAR(200),
    tahun YEAR,
    deskripsi TEXT,
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE
);

-- Tabel Riwayat Pendidikan & Karir
CREATE TABLE riwayat_pendidikan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dokter_id INT,
    jenis ENUM('pendidikan', 'karir') NOT NULL,
    judul VARCHAR(200) NOT NULL,
    institusi VARCHAR(200),
    periode VARCHAR(50),
    deskripsi TEXT,
    urutan INT DEFAULT 0,
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE
);

-- Tabel Jadwal Praktek
CREATE TABLE jadwal_praktek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dokter_id INT,
    nama_tempat VARCHAR(200) NOT NULL,
    alamat TEXT,
    hari VARCHAR(50),
    jam_mulai TIME,
    jam_selesai TIME,
    telepon VARCHAR(20),
    gambar VARCHAR(255),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE
);

-- Tabel Kategori Organ
CREATE TABLE kategori_organ (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    icon VARCHAR(255),
    warna VARCHAR(20) DEFAULT '#3b82f6',
    urutan INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
);

-- Tabel Penyakit
CREATE TABLE penyakit (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kategori_id INT,
    nama VARCHAR(200) NOT NULL,
    deskripsi_singkat TEXT,
    penyebab_utama TEXT,
    gejala TEXT,
    bahaya TEXT,
    cara_mencegah TEXT,
    cara_mengurangi TEXT,
    gambar VARCHAR(255),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    FOREIGN KEY (kategori_id) REFERENCES kategori_organ(id) ON DELETE CASCADE
);

-- Tabel Layanan Medis
CREATE TABLE layanan_medis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    icon VARCHAR(255),
    warna VARCHAR(20) DEFAULT '#3b82f6',
    link_eksternal VARCHAR(255),
    urutan INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
);

-- Tabel Keahlian Khusus Dokter
CREATE TABLE keahlian_khusus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dokter_id INT,
    nama_keahlian VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    icon VARCHAR(255),
    warna VARCHAR(20) DEFAULT '#fbbf24',
    FOREIGN KEY (dokter_id) REFERENCES dokter(id) ON DELETE CASCADE
);

-- Insert Data Dummy
-- Admin
INSERT INTO admin (username, password, nama) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Dokter
INSERT INTO dokter (nama, spesialisasi, gelar, deskripsi, total_pasien, total_sertifikat, total_penghargaan, telepon, email) VALUES 
('dr. Arif Rahman', 'Spesialis Penyakit Dalam', 'Sp.PD, FINASIM, FINEM, AIFO-K, FISQua', 
'Berpengalaman lebih dari 15 tahun dalam menangani berbagai penyakit dalam seperti diabetes, hipertensi, gangguan pencernaan, dan penyakit autoimun. Berkomitmen memberikan pelayanan kesehatan terbaik dengan pendekatan yang komprehensif dan personal.', 
1000, 1000, 1000, '0812-3456-7890', 'dr.arif@example.com');

-- Sertifikat
INSERT INTO sertifikat (dokter_id, nama_sertifikat, institusi, tahun) VALUES 
(1, 'Spesialis Penyakit Dalam', 'Fakultas Kedokteran Universitas Indonesia', 2008),
(1, 'Fellow Endokrinologi', 'RS Cipto Mangunkusumo', 2010),
(1, 'Sertifikat Diabetes', 'Indonesian Society of Endocrinology', 2012),
(1, 'Advanced Internal Medicine', 'American College of Physicians', 2015);

-- Riwayat Pendidikan & Karir
INSERT INTO riwayat_pendidikan (dokter_id, jenis, judul, institusi, periode, deskripsi, urutan) VALUES 
(1, 'pendidikan', 'Dokter Spesialis Penyakit Dalam', 'Program Pendidikan Dokter Spesialis Penyakit Dalam Fakultas Kedokteran Universitas Indonesia', '2005-2008', '', 1),
(1, 'karir', 'Dokter Spesialis - RS Cipto Mangunkusumo', 'Dokter spesialis penyakit dalam di departemen penyakit dalam, menangani kasus penyakit kompleks dan mengajar mahasiswa kedokteran', '2008-2015', '', 1),
(1, 'karir', 'Fellowship Endokrinologi', 'Program fellowship dibidang endokrinologi dan diabetes, memperdalam keahlian dalam penanganan penyakit hormonal', '2010-2011', '', 2),
(1, 'karir', 'Konsultan Senior - RS Prima Medika', 'Konsultan senior penyakit dalam, memimpin tim medis dan mengembangkan program pencegahan penyakit tidak menular', '2015-sekarang', '', 3);

-- Jadwal Praktek
INSERT INTO jadwal_praktek (dokter_id, nama_tempat, alamat, hari, jam_mulai, jam_selesai, telepon, status) VALUES 
(1, 'Rumah Sakit Siloam', 'Jl. Siloam No. 6, Semanggi Jakarta', 'Senin, Rabu, Jumat', '08:00', '12:00', '(021) 123-4567', 'aktif'),
(1, 'Klinik Sehat Keluarga Pondok Indah', 'Jl. Metro Pondok Indah Blok IA, Jakarta Selatan', 'Selasa, Kamis', '14:00', '18:00', '(021) 234-5678', 'aktif'),
(1, 'RS Prima Medika', 'Jl. Bendungan Hilir Raya No. 17, Tanah Abang Jakarta Pusat', 'Sabtu', '09:00', '13:00', '(021) 345-6789', 'aktif');

-- Kategori Organ
INSERT INTO kategori_organ (nama, deskripsi, urutan) VALUES 
('Mulut & Kerongkongan', 'Eksplorasi berbagai kategori penyakit dalam berdasarkan sistem organ dan juga karakteristiknya', 1),
('Lambung', 'Penyakit-penyakit yang berhubungan dengan sistem pencernaan lambung', 2),
('Usus Halus & Usus Besar', 'Gangguan dan penyakit pada sistem pencernaan usus', 3),
('Hati, Empedu & Pankreas', 'Penyakit pada organ hati, kantung empedu dan pankreas', 4);

-- Penyakit
INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi) VALUES 
(2, 'GERD (Gastroesophageal Reflux Disease)', 
'GERD adalah kondisi asam lambung naik ke kerongkongan (regurgitasi lambung) secara terus-menerus yang lama kelamaan bisa merusak organ.',
'• Sfingter esofagus melemah\n• Hernia hiatus\n• Kegemukan, merokok, stress, atau kekurangan makanan tertentu', 
'• Panas di dada (heartburn), terutama setelah makan atau berbaring\n• Asam/makanan naik ke mulut\n• Sulit menelan (disfagia)\n• Batuk kronis, suara serak',
'• Jika tidak segera diatasi dapat menyebabkan luka di kerongkongan\n• Luka bisa menyempet kerongkongan\n• Risiko kanker kerongkongan',
'• Hindari makanan pedas, asam, kopi, coklat, alkohol\n• Kurangi porsi makan, makan secara perlahan\n• Jaga berat badan & berhenti merokok\n• Hindari berbaring langsung setelah makan',
'• Hindari makanan pemicu, asam, kopi, coklat\n• Kurangi stres, olahraga teratur\n• Elevasi kepala saat tidur (lebih tinggi 15-20 cm)\n• Obat sesuai anjuran dokter: antasid, PPI (omeprazole)',
2),

(2, 'Gastritis Kronis', 
'Gastritis kronis adalah peradangan pada dinding lambung yang berlangsung lama (menahun), sering kambuh, dan bisa merusak jaringan lambung bila tidak diobati.',
'• Infeksi bakteri Helicobacter pylori (H. pylori)\n• Penggunaan obat antiinflamasi (NSAID) jangka panjang\n• Pola makan tidak teratur, pedas, asam (atau asupan)\n• Faktor autoimun (tubuh menyerang lambung sendiri)',
'• Nyeri atau perih di ulu hati\n• Mual dan muntah, terutama setelah makan\n• Kehilangan nafsu makan\n• Rasa kenyang berkepanjangan',
'• Luka lambung (ulkus)\n• Perdarahan lambung\n• Anemia karena kekurangan zat besi\n• Risiko kanker lambung (jangka panjang)',
'• Hindari makanan pedas, asam, kopi, alkohol\n• Kurangi stres, makan secara teratur\n• Berhenti merokok dan konsumsi alkohol\n• Obat sesuai anjuran dokter dan tidak berlebihan',
'• Hindari makanan pedas, asam, kopi, coklat, alkohol\n• Konsumsi obat sesuai jadwal (PPI), antibiotik bila ada H. pylori\n• Makan dalam porsi kecil tapi sering\n• Diet rendah: antibiotik plus karnitit H. pylori, antasida, PPI (omeprazole)',
2),

(1, 'Somatitis', 
'Peradangan pada mukosa mulut yang menyebabkan luka atau sariawan di dalam mulut.',
'• Trauma ringan (tergigit, sikat gigi kasar)\n• Infeksi virus, bakteri, atau jamur\n• Kekurangan vitamin B12, asam folat, zat besi\n• Sistem imun menurun, stress',
'• Luka atau sariawan di mulut\n• Nyeri saat makan atau minum\n• Kesulitan menelan\n• Demam ringan',
'• Dehidrasi karena sulit minum\n• Malnutrisi karena sulit makan\n• Infeksi sekunder\n• Penyebaran infeksi',
'• Menjaga kebersihan mulut\n• Gunakan sikat gigi lembut\n• Hindari makanan pedas atau asam\n• Konsumsi vitamin yang cukup',
'• Kumur dengan air garam atau obat kumur khusus\n• Hindari makanan pedas, asam, panas\n• Kompres dingin untuk mengurangi nyeri\n• Obat pereda nyeri sesuai anjuran dokter',
1);

-- Layanan Medis
INSERT INTO layanan_medis (nama, deskripsi, urutan) VALUES 
('Poli Klinik Penyakit Dalam', 'Layanan konsultasi dan pengobatan penyakit dalam', 1),
('Terapi Stemsel', 'Terapi regeneratif menggunakan sel punca untuk berbagai kondisi', 2),
('Home Care (Via Rumah Sakit UNIMUS)', 'Layanan perawatan kesehatan di rumah pasien', 3),
('Telekonsultasi (Via Rumah Sakit UNIMUS)', 'Konsultasi kesehatan online dengan dokter spesialis', 4);

-- Keahlian Khusus
INSERT INTO keahlian_khusus (dokter_id, nama_keahlian, deskripsi) VALUES 
(1, 'Diabetes Mellitus', 'Penanganan dan edukasi diabetes melitus tipe 1 dan tipe 2, termasuk komplikasi diabetes'),
(1, 'Hipertensi', 'Diagnosis dan terapi hipertensi primer dan sekunder, pencegahan komplikasi kardiovaskular'),
(1, 'Penyakit Kardiovaskular', 'Penanganan penyakit jantung dan pembuluh darah, pencegahan penyakit jantung koroner'),
(1, 'Penyakit Ginjal', 'Diagnosis dan terapi penyakit ginjal akut dan kronik, pencegahan gagal ginjal'),
(1, 'Penyakit Paru', 'Penanganan asma, PPOK, pneumonia dan penyakit paru lainnya yang terkait penyakit dalam');