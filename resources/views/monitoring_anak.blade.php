<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitoring Measurement Anak</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f6f9;
}

.container{
    max-width:1200px;
    margin:30px auto;
    padding:20px;
}

.header{
    background:#4CAF50;
    color:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:25px;
}

.header h1{
    margin-bottom:5px;
}

.profile-card{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.profile-header{
    display:flex;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.profile-image img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #4CAF50;
}

.profile-info{
    flex:1;
}

.profile-info h2{
    color:#2e7d32;
    margin-bottom:10px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:12px;
}

.info-item{
    background:#f8f9fa;
    padding:10px;
    border-radius:8px;
}

.info-item strong{
    display:block;
    color:#555;
    margin-bottom:4px;
}

.monitoring-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
}

.measurement-card{
    background:white;
    border-radius:15px;
    padding:30px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.measurement-card h3{
    color:#666;
    margin-bottom:15px;
}

.value{
    font-size:55px;
    font-weight:bold;
    color:#4CAF50;
    margin-bottom:10px;
}

.unit{
    color:#888;
    font-size:18px;
}

.status-section{
    margin-top:25px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.status-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

.status-box{
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
}

.online{
    color:#2e7d32;
    font-weight:bold;
}

.btn-start{
    margin-top:20px;
    background:#4CAF50;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
}

.btn-start:hover{
    background:#388E3C;
}

.gizi-card{
    margin-top:25px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.status-gizi{
    background:#fff3cd;
    border-left:6px solid #ffc107;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

.status-gizi h3{
    color:#856404;
    margin-bottom:10px;
}

.rekomendasi{
    background:#f8f9fa;
    padding:20px;
    border-radius:10px;
}

.rekomendasi h3{
    color:#2e7d32;
    margin-bottom:15px;
}

.rekomendasi ul{
    padding-left:20px;
    line-height:2;
    color:#555;
}

.footer{
    text-align:center;
    margin-top:25px;
    color:#666;
}

@media(max-width:768px){

    .profile-header{
        flex-direction:column;
        text-align:center;
    }

    .value{
        font-size:40px;
    }

}
</style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>Monitoring Measurement Anak</h1>
        <p>Data Tinggi dan Berat Badan dari Sistem Posyandu Berbasis IoT</p>
    </div>

    <!-- Informasi Anak -->
    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-image">
                <img src="https://via.placeholder.com/120" alt="Foto Anak">
            </div>

            <div class="profile-info">

                <h2>Muhammad Fajar</h2>

                <div class="info-grid">

                    <div class="info-item">
                        <strong>NIK</strong>
                        321000000001
                    </div>

                    <div class="info-item">
                        <strong>Jenis Kelamin</strong>
                        Laki-Laki
                    </div>

                    <div class="info-item">
                        <strong>Tanggal Lahir</strong>
                        12 Januari 2023
                    </div>

                    <div class="info-item">
                        <strong>Usia</strong>
                        2 Tahun 4 Bulan
                    </div>

                    <div class="info-item">
                        <strong>Nama Ibu</strong>
                        Siti Aminah
                    </div>

                    <div class="info-item">
                        <strong>No Telepon</strong>
                        081234567890
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Hasil Measurement -->
    <div class="monitoring-grid">

        <div class="measurement-card">
            <h3>Tinggi Badan</h3>
            <div class="value">89</div>
            <div class="unit">cm</div>
        </div>

        <div class="measurement-card">
            <h3>Berat Badan</h3>
            <div class="value">12.5</div>
            <div class="unit">kg</div>
        </div>

    </div>

    <!-- Status Alat -->
    <div class="status-section">

        <h2 style="margin-bottom:20px;color:#2e7d32;">
            Status Measurement
        </h2>

        <div class="status-grid">

            <div class="status-box">
                <strong>Status Alat</strong>
                <p class="online">● Terhubung</p>
            </div>

            <div class="status-box">
                <strong>Measurement Terakhir</strong>
                <p>04 Juni 2026 - 10:35 WIB</p>
            </div>

            <div class="status-box">
                <strong>Status Data</strong>
                <p>Data berhasil diterima</p>
            </div>

        </div>

        <button class="btn-start">
            Mulai Measurement
        </button>

    </div>

    <!-- Status Gizi -->
    <div class="gizi-card">

        <div class="status-gizi">

            <h3>Status Gizi : Gizi Kurang</h3>

            <p style="line-height:1.8;color:#555;">
                Berdasarkan hasil measurement berat badan dan usia anak,
                status gizi anak saat ini termasuk dalam kategori
                <strong>Gizi Kurang</strong>. Diperlukan peningkatan
                asupan nutrisi agar pertumbuhan dan perkembangan anak
                dapat berlangsung secara optimal.
            </p>

        </div>

        <div class="rekomendasi">

            <h3>Rekomendasi Perbaikan Gizi</h3>

            <ul>
                <li>Perbanyak konsumsi protein hewani seperti telur, ikan, ayam, dan daging.</li>
                <li>Tambahkan makanan bergizi tinggi seperti susu, keju, kacang-kacangan, tempe, dan tahu.</li>
                <li>Berikan makanan utama 3 kali sehari dan camilan sehat 2–3 kali sehari.</li>
                <li>Perbanyak konsumsi buah dan sayuran yang kaya vitamin dan mineral.</li>
                <li>Pastikan anak mendapatkan waktu tidur yang cukup sesuai usianya.</li>
                <li>Lakukan pemantauan berat badan secara rutin setiap kunjungan Posyandu.</li>
                <li>Konsultasikan dengan petugas kesehatan apabila berat badan tidak mengalami peningkatan.</li>
            </ul>

        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        © 2026 Sistem Informasi Posyandu Berbasis IoT
    </div>

</div>

</body>
</html>