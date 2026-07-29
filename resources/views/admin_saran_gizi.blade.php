<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Saran Status Gizi</title>

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
    display:flex;
    min-height:100vh;
}

/* Sidebar */
.sidebar{
    width:250px;
    background:#2e7d32;
    color:white;
    padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:10px 0;
}

.sidebar ul li a{
    text-decoration:none;
    color:white;
    display:block;
    padding:12px;
    border-radius:8px;
    transition:0.3s;
}

.sidebar ul li a:hover{
    background:#43a047;
}

/* Main Content */
.main-content{
    flex:1;
    padding:30px;
}

.header{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.header h1{
    color:#2e7d32;
    margin-bottom:8px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.card h3{
    color:#2e7d32;
    margin-bottom:20px;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
    margin-bottom:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:8px;
    font-weight:600;
    color:#444;
}

.form-group select,
.form-group input,
.form-group textarea{
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:14px;
}

textarea{
    resize:vertical;
    min-height:180px;
}

.btn{
    background:#2e7d32;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    margin-top:10px;
}

.btn:hover{
    background:#1b5e20;
}

/* Preview */
.preview-card{
    margin-top:25px;
    background:#f8f9fa;
    border-left:5px solid #4CAF50;
    padding:20px;
    border-radius:10px;
}

.preview-card h4{
    color:#2e7d32;
    margin-bottom:10px;
}

.preview-card p{
    line-height:1.8;
    color:#555;
}

/* Tabel */
.table-card{
    margin-top:25px;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.table-card h3{
    color:#2e7d32;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2e7d32;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

.btn-edit{
    background:#2196F3;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:5px;
    cursor:pointer;
}

.btn-delete{
    background:#f44336;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:5px;
    cursor:pointer;
}

@media(max-width:768px){

    .container{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
    }

    table{
        display:block;
        overflow-x:auto;
    }

}
</style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">

        <h2>POSYANDU</h2>

        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Data Anak</a></li>
            <li><a href="#">Data Measurement</a></li>
            <li><a href="#">Status Gizi</a></li>
            <li><a href="#">Kelola Saran Gizi</a></li>
            <li><a href="#">Laporan</a></li>
            <li><a href="#">Logout</a></li>
        </ul>

    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="header">
            <h1>Kelola Saran Status Gizi</h1>
            <p>Admin dapat menambahkan saran berdasarkan status gizi dan kelompok usia anak.</p>
        </div>

        <!-- Form -->
        <div class="card">

            <h3>Tambah Saran Gizi</h3>

            <div class="form-grid">

                <div class="form-group">
                    <label>Status Gizi</label>
                    <select>
                        <option>Normal</option>
                        <option>Gizi Kurang</option>
                        <option>Gizi Lebih</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelompok Usia</label>
                    <select>
                        <option>0 - 6 Bulan</option>
                        <option>7 - 12 Bulan</option>
                        <option>13 - 24 Bulan</option>
                        <option>25 - 36 Bulan</option>
                        <option>37 - 60 Bulan</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label>Judul Saran</label>
                <input type="text" placeholder="Contoh: Rekomendasi Nutrisi Anak Usia 13-24 Bulan">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Isi Saran</label>
                <textarea placeholder="Masukkan saran yang akan ditampilkan kepada orang tua..."></textarea>
            </div>

            <button class="btn">Simpan Saran</button>

            <!-- Preview -->
            <div class="preview-card">

                <h4>Preview Saran</h4>

                <p>
                    Anak usia 13–24 bulan dengan status gizi kurang disarankan
                    mengonsumsi makanan kaya protein seperti telur, ikan,
                    ayam, tempe, dan tahu. Berikan makanan utama 3 kali sehari,
                    camilan sehat 2 kali sehari, serta lakukan pemantauan berat
                    badan secara rutin di Posyandu.
                </p>

            </div>

        </div>

        <!-- Daftar Saran -->
        <div class="table-card">

            <h3>Daftar Saran Gizi</h3>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status Gizi</th>
                        <th>Kelompok Usia</th>
                        <th>Judul Saran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>Normal</td>
                        <td>13 - 24 Bulan</td>
                        <td>Pertahankan Pola Makan Seimbang</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Gizi Kurang</td>
                        <td>13 - 24 Bulan</td>
                        <td>Peningkatan Asupan Protein</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Gizi Lebih</td>
                        <td>25 - 36 Bulan</td>
                        <td>Pengaturan Pola Makan Sehat</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>