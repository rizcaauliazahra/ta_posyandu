<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Pengguna Posyandu</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#4CAF50,#81C784);
}

.login-container{
    width:100%;
    max-width:420px;
    padding:20px;
}

.login-card{
    background:#fff;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.logo{
    text-align:center;
    margin-bottom:25px;
}

.logo img{
    width:80px;
    margin-bottom:10px;
}

.logo h2{
    color:#2e7d32;
}

.logo p{
    color:#777;
    font-size:14px;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#444;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    outline:none;
    transition:0.3s;
}

.form-group input:focus{
    border-color:#4CAF50;
}

.btn-login{
    width:100%;
    padding:13px;
    border:none;
    border-radius:10px;
    background:#4CAF50;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.btn-login:hover{
    background:#388E3C;
}

.extra-link{
    text-align:center;
    margin-top:15px;
}

.extra-link a{
    text-decoration:none;
    color:#4CAF50;
    font-size:14px;
}

.extra-link a:hover{
    text-decoration:underline;
}

.footer{
    text-align:center;
    margin-top:20px;
    color:white;
    font-size:14px;
}

@media(max-width:480px){
    .login-card{
        padding:25px;
    }
}
</style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/2966/2966486.png" alt="Logo Posyandu">
            <h2>POSYANDU</h2>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        <form>

            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Masukkan email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

        </form>

        <div class="extra-link">
            <a href="#">Lupa Password?</a>
        </div>

        <div class="extra-link">
            Belum punya akun?
            <a href="#">Daftar Sekarang</a>
        </div>

    </div>

    <div class="footer">
        © 2026 Sistem Informasi Posyandu
    </div>

</div>

</body>
</html>