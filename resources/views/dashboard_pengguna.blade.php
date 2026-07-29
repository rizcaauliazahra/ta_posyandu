<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Posyandu - Profil Anak</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f4f7fc;
}

.container{
    width:90%;
    max-width:1200px;
    margin:30px auto;
}

.header{
    background:#4CAF50;
    color:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

.header h1{
    font-size:28px;
}

.profile-card{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    display:flex;
    gap:25px;
    flex-wrap:wrap;
}

.profile-image{
    flex:1;
    min-width:200px;
    text-align:center;
}

.profile-image img{
    width:180px;
    height:180px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid #4CAF50;
}

.profile-info{
    flex:3;
}

.profile-info h2{
    color:#333;
    margin-bottom:15px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

.info-item{
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
}

.info-item label{
    font-weight:bold;
    color:#555;
    display:block;
    margin-bottom:5px;
}

.info-item span{
    color:#222;
}

.section{
    margin-top:25px;
}

.section h3{
    margin-bottom:15px;
    color:#4CAF50;
}

.status-card{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:200px;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    text-align:center;
}

.card h4{
    color:#666;
    margin-bottom:10px;
}

.card p{
    font-size:24px;
    font-weight:bold;
    color:#4CAF50;
}

/* Grafik */
.chart-container{
    margin-top:25px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.chart-container h3{
    margin-bottom:20px;
    color:#4CAF50;
}

.chart-wrapper{
    position:relative;
    height:500px;
}

.footer{
    margin-top:20px;
    text-align:center;
    color:#777;
}

@media(max-width:768px){
    .profile-card{
        flex-direction:column;
        align-items:center;
    }

    .chart-wrapper{
        height:350px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Dashboard Posyandu</h1>
        <p>Informasi Profil Anak</p>
    </div>

    <div class="profile-card">

        <div class="profile-image">
            <img src="https://via.placeholder.com/180" alt="Foto Anak">
        </div>

        <div class="profile-info">

            <h2 id="namaAnak">Muhammad Fajar</h2>

            <div class="info-grid">

                <div class="info-item">
                    <label>NIK</label>
                    <span>321xxxxxxxxxxxxx</span>
                </div>

                <div class="info-item">
                    <label>Jenis Kelamin</label>
                    <span>Laki-Laki</span>
                </div>

                <div class="info-item">
                    <label>Tanggal Lahir</label>
                    <span>12 Januari 2023</span>
                </div>

                <div class="info-item">
                    <label>Usia</label>
                    <span>24 Bulan</span>
                </div>

                <div class="info-item">
                    <label>Nama Ibu</label>
                    <span>Siti Aminah</span>
                </div>

                <div class="info-item">
                    <label>Nama Ayah</label>
                    <span>Ahmad Fauzi</span>
                </div>

                <div class="info-item">
                    <label>Alamat</label>
                    <span>Dusun Mekar Jaya, RT 02/RW 03</span>
                </div>

                <div class="info-item">
                    <label>No. Telepon</label>
                    <span>0812-3456-7890</span>
                </div>

            </div>

        </div>

    </div>

    <div class="section">

        <h3>Status Pertumbuhan Terakhir</h3>

        <div class="status-card">

            <div class="card">
                <h4>Berat Badan</h4>
                <p>12.5 Kg</p>
            </div>

            <div class="card">
                <h4>Tinggi Badan</h4>
                <p>89 cm</p>
            </div>

            <div class="card">
                <h4>Status Gizi</h4>
                <p>Normal</p>
            </div>

            <div class="card">
                <h4>Status Stunting</h4>
                <p>Tidak</p>
            </div>

        </div>

    </div>

    <!-- Grafik Pertumbuhan -->
    <div class="chart-container">

        <h3>Grafik Riwayat Pertumbuhan Anak</h3>

        <div class="chart-wrapper">
            <canvas id="growthChart"></canvas>
        </div>

    </div>

    <div class="footer">
        © 2026 Sistem Informasi Posyandu
    </div>

</div>

<script>

// Data umur dalam bulan
const umur = [
    '0',
    '3',
    '6',
    '9',
    '12',
    '15',
    '18',
    '21',
    '24'
];

// Data berat badan (kg)
const beratBadan = [
    3.2,
    5.5,
    7.1,
    8.3,
    9.5,
    10.4,
    11.2,
    12.0,
    12.5
];

// Data tinggi badan (cm)
const tinggiBadan = [
    50,
    59,
    67,
    73,
    77,
    81,
    84,
    87,
    89
];

const ctx = document.getElementById('growthChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: umur,
        datasets: [
            {
                label: 'Berat Badan (Kg)',
                data: beratBadan,
                borderColor: '#4CAF50',
                backgroundColor: '#4CAF50',
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.3,
                yAxisID: 'y'
            },
            {
                label: 'Tinggi Badan (Cm)',
                data: tinggiBadan,
                borderColor: '#2196F3',
                backgroundColor: '#2196F3',
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.3,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },

        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },

        scales: {

            x: {
                title: {
                    display: true,
                    text: 'Umur Anak (Bulan)'
                }
            },

            y: {
                type: 'linear',
                position: 'left',
                title: {
                    display: true,
                    text: 'Berat Badan (Kg)'
                }
            },

            y1: {
                type: 'linear',
                position: 'right',
                grid: {
                    drawOnChartArea: false
                },
                title: {
                    display: true,
                    text: 'Tinggi Badan (Cm)'
                }
            }

        }
    }
});

</script>

</body>
</html>