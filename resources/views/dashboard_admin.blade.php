<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin Posyandu</title>

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
    margin:15px 0;
}

.sidebar ul li a{
    color:white;
    text-decoration:none;
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
    padding:20px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.header h1{
    color:#2e7d32;
}

/* Form */
.form-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.form-card h3{
    margin-bottom:20px;
    color:#2e7d32;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:6px;
    font-weight:600;
}

.form-group input,
.form-group select,
.form-group textarea{
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
}

textarea{
    resize:none;
}

.btn{
    margin-top:20px;
    background:#2e7d32;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}

.btn:hover{
    background:#1b5e20;
}

/* Table */
.table-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.table-card h3{
    margin-bottom:20px;
    color:#2e7d32;
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
    background:#1976d2;
    color:white;
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.btn-delete{
    background:#d32f2f;
    color:white;
    padding:6px 12px;
    border:none;
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
            <li><a href="#">Data Orang Tua</a></li>
            <li><a href="#">Penimbangan</a></li>
            <li><a href="#">Imunisasi</a></li>
            <li><a href="#">Laporan</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="header">
            <h1>Dashboard Admin Posyandu</h1>
            <p>Kelola Data Anak Posyandu</p>
        </div>

        <!-- Form Tambah Data -->
        <div class="form-card">

            <h3>Tambah Data Anak</h3>

            <form>

                <div class="form-grid">

                    <div class="form-group">
                        <label>NIK Anak</label>
                        <input type="text">
                    </div>

                    <div class="form-group">
                        <label>Nama Anak</label>
                        <input type="text">
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select>
                            <option>Laki-Laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date">
                    </div>

                    <div class="form-group">
                        <label>Nama Ibu</label>
                        <input type="text">
                    </div>

                    <div class="form-group">
                        <label>Nama Ayah</label>
                        <input type="text">
                    </div>

                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text">
                    </div>

                    <div class="form-group">
                        <label>Foto Anak</label>
                        <input type="file">
                    </div>

                </div>

                <div class="form-group" style="margin-top:15px;">
                    <label>Alamat</label>
                    <textarea rows="4"></textarea>
                </div>

                <button type="submit" class="btn">
                    Simpan Data
                </button>

            </form>

        </div>

        <!-- Tabel Data Anak -->
        <div class="table-card">

            <h3>Daftar Data Anak</h3>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Anak</th>
                        <th>JK</th>
                        <th>Tanggal Lahir</th>
                        <th>Nama Ibu</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>321000000001</td>
                        <td>Ahmad Fajar</td>
                        <td>L</td>
                        <td>12-01-2023</td>
                        <td>Siti Aminah</td>
                        <td>081234567890</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>321000000002</td>
                        <td>Nabila Putri</td>
                        <td>P</td>
                        <td>15-05-2022</td>
                        <td>Dewi Lestari</td>
                        <td>081298765432</td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-delete">Hapus</button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>321000000003</td>
                        <td>Rizky Maulana</td>
                        <td>L</td>
                        <td>08-10-2021</td>
                        <td>Nurhayati</td>
                        <td>081345678912</td>
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