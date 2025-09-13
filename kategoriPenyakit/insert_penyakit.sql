-- Insert additional diseases for Lambung category (kategori_id = 2)
INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi) VALUES 
(2, 'Tukak Lambung (Ulkus Peptikum)', 
'Tukak lambung adalah luka terbuka yang berkembang di lapisan dalam lambung atau bagian atas usus halus.',
'• Infeksi bakteri Helicobacter pylori (H. pylori)\n• Penggunaan obat antiinflamasi nonsteroid (NSAID) jangka panjang\n• Stres berlebihan\n• Konsumsi alkohol berlebihan\n• Merokok',
'• Nyeri perut bagian atas yang terasa seperti terbakar\n• Mual dan muntah\n• Kehilangan nafsu makan\n• Perut kembung\n• Muntah darah atau tinja berwarna hitam (tanda perdarahan)',
'• Perdarahan lambung yang bisa mengancam jiwa\n• Perforasi (lubang) di dinding lambung\n• Obstruksi lambung\n• Risiko kanker lambung',
'• Hindari makanan pedas dan asam\n• Batasi konsumsi alkohol dan berhenti merokok\n• Kelola stres dengan baik\n• Hindari penggunaan NSAID berlebihan',
'• Obat antibiotik untuk mengatasi H. pylori\n• Obat penghambat asam lambung (PPI)\n• Antasida untuk meredakan gejala\n• Operasi jika terjadi komplikasi serius'),

(2, 'Diare', 
'Diare adalah kondisi dimana seseorang buang air besar lebih sering dari biasanya dengan konsistensi tinja yang encer.',
'• Infeksi virus, bakteri, atau parasit\n• Keracunan makanan\n• Intoleransi makanan tertentu\n• Efek samping obat\n• Stres dan kecemasan',
'• Buang air besar lebih dari 3 kali sehari\n• Tinja encer atau cair\n• Kram perut\n• Mual dan muntah\n• Demam (pada kasus infeksi)',
'• Dehidrasi parah\n• Ketidakseimbangan elektrolit\n• Gangguan fungsi ginjal\n• Syok hipovolemik',
'• Cuci tangan dengan sabun sebelum makan\n• Hindari makanan yang tidak higienis\n• Minum air yang bersih\n• Vaksinasi untuk penyakit tertentu',
'• Minum banyak cairan (oralit)\n• Hindari makanan pedas dan berminyak\n• Konsumsi makanan yang mudah dicerna\n• Obat antidiare jika diperlukan'),

(2, 'Disentri', 
'Disentri adalah peradangan usus yang menyebabkan diare dengan darah dan lendir, biasanya disebabkan oleh infeksi bakteri atau parasit.',
'• Infeksi bakteri Shigella\n• Infeksi parasit Entamoeba histolytica\n• Kontaminasi makanan dan air\n• Sanitasi yang buruk',
'• Diare berdarah dengan lendir\n• Kram perut yang parah\n• Demam tinggi\n• Mual dan muntah\n• Kehilangan nafsu makan',
'• Dehidrasi berat\n• Sepsis (infeksi darah)\n• Perforasi usus\n• Abses hati (pada amebiasis)',
'• Cuci tangan dengan sabun\n• Hindari makanan mentah yang tidak higienis\n• Minum air yang sudah dimasak\n• Sanitasi lingkungan yang baik',
'• Antibiotik untuk infeksi bakteri\n• Obat antiparasit untuk amebiasis\n• Rehidrasi dengan oralit\n• Istirahat yang cukup'),

(2, 'IBS (Irritable Bowel Syndrome)', 
'IBS adalah gangguan fungsional pada sistem pencernaan yang menyebabkan ketidaknyamanan perut tanpa kerusakan fisik yang terlihat.',
'• Stres dan kecemasan\n• Perubahan pola makan\n• Sensitivitas terhadap makanan tertentu\n• Gangguan komunikasi otak-usus\n• Infeksi saluran pencernaan sebelumnya',
'• Nyeri perut yang hilang timbul\n• Perubahan pola buang air besar\n• Kembung dan gas berlebihan\n• Diare atau sembelit\n• Perasaan tidak tuntas setelah BAB',
'• Gangguan kualitas hidup\n• Depresi dan kecemasan\n• Malnutrisi\n• Komplikasi psikologis',
'• Kelola stres dengan baik\n• Identifikasi makanan pemicu\n• Makan teratur dengan porsi kecil\n• Olahraga teratur',
'• Diet FODMAP rendah\n• Probiotik untuk kesehatan usus\n• Obat antispasmodik\n• Terapi kognitif perilaku');

-- Insert additional diseases for Mulut & Kerongkongan category (kategori_id = 1)
INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi) VALUES 
(1, 'Karies gigi (gigi berlubang)', 
'Karies gigi adalah kerusakan pada struktur gigi yang disebabkan oleh asam yang dihasilkan bakteri dari sisa makanan.',
'• Bakteri Streptococcus mutans\n• Konsumsi gula berlebihan\n• Kebersihan mulut yang buruk\n• Kurang fluoride\n• Mulut kering',
'• Nyeri gigi yang tajam\n• Sensitivitas terhadap panas dan dingin\n• Lubang yang terlihat pada gigi\n• Bau mulut\n• Perubahan warna gigi',
'• Infeksi akar gigi\n• Abses gigi\n• Kehilangan gigi\n• Penyebaran infeksi ke organ lain',
'• Sikat gigi 2 kali sehari dengan pasta berfluoride\n• Gunakan benang gigi\n• Batasi konsumsi gula\n• Kunjungi dokter gigi secara rutin',
'• Tambal gigi untuk karies kecil\n• Perawatan saluran akar untuk karies besar\n• Crown atau mahkota gigi\n• Antibiotik jika ada infeksi'),

(1, 'Gingivitis (Radang Gusi)', 
'Gingivitis adalah peradangan pada gusi yang disebabkan oleh penumpukan plak bakteri di sekitar garis gusi.',
'• Penumpukan plak bakteri\n• Kebersihan mulut yang buruk\n• Merokok\n• Diabetes yang tidak terkontrol\n• Perubahan hormonal',
'• Gusi merah dan bengkak\n• Gusi berdarah saat menyikat gigi\n• Bau mulut\n• Gusi sensitif\n• Perubahan bentuk gusi',
'• Periodontitis (radang gusi lanjut)\n• Kehilangan gigi\n• Penyakit jantung\n• Diabetes yang memburuk',
'• Sikat gigi dengan teknik yang benar\n• Gunakan benang gigi\n• Berkumur dengan obat kumur antibakteri\n• Berhenti merokok',
'• Pembersihan gigi profesional\n• Scaling dan root planing\n• Obat kumur antibakteri\n• Perbaikan kebersihan mulut'),

(1, 'Periodontitis', 
'Periodontitis adalah infeksi gusi yang merusak jaringan lunak dan tulang yang menyangga gigi.',
'• Gingivitis yang tidak diobati\n• Penumpukan plak dan karang gigi\n• Merokok\n• Diabetes\n• Faktor genetik',
'• Gusi merah, bengkak, dan berdarah\n• Gusi surut\n• Gigi goyang\n• Bau mulut persisten\n• Perubahan cara gigi menggigit',
'• Kehilangan gigi\n• Penyakit jantung dan stroke\n• Diabetes yang memburuk\n• Komplikasi kehamilan',
'• Kebersihan mulut yang baik\n• Pembersihan gigi rutin\n• Berhenti merokok\n• Kontrol diabetes',
'• Scaling dan root planing\n• Antibiotik topikal atau sistemik\n• Operasi periodontal\n• Perawatan regeneratif'),

(1, 'Kanker mulut', 
'Kanker mulut adalah pertumbuhan sel abnormal yang ganas di dalam mulut, termasuk bibir, lidah, gusi, dan bagian dalam pipi.',
'• Merokok dan mengunyah tembakau\n• Konsumsi alkohol berlebihan\n• Paparan sinar matahari berlebihan\n• Infeksi HPV\n• Diet rendah buah dan sayuran',
'• Luka di mulut yang tidak sembuh\n• Bercak putih atau merah di mulut\n• Nyeri mulut yang persisten\n• Kesulitan menelan\n• Perubahan suara',
'• Penyebaran ke organ lain\n• Kesulitan makan dan berbicara\n• Kematian jika tidak diobati\n• Komplikasi pengobatan',
'• Berhenti merokok dan minum alkohol\n• Lindungi bibir dari sinar matahari\n• Diet sehat dengan buah dan sayuran\n• Vaksinasi HPV',
'• Operasi pengangkatan tumor\n• Radioterapi\n• Kemoterapi\n• Terapi target dan imunoterapi');