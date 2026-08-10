<script>
// ===================== MODEL UTAMA =====================
// Model utama untuk menyimpan data global aplikasi
// Bisa dikembangkan untuk menambahkan properti lain seperti token, userData, dll.
model.masterModel = {
    username : "",
    password : ""
}

// ===================== OBJEK LOGIN =====================
// Objek yang menangani semua fungsionalitas login
// Bisa ditambahkan properti seperti rememberMe, captcha, dll.
var login = {
    title      : "Form Login",
    RecordLogin: ko.mapping.fromJS(model.masterModel) // Mapping model ke Knockout observable
}

// ===================== FUNGSI PROSES LOGIN =====================
// Fungsi utama untuk memproses autentikasi user
// Dikembangkan: bisa tambahkan validasi email, captcha, atau login dengan sosial media
login.prosesLogin = function(){

    // Mengaktifkan indikator loading (misal spinner)
    model.Processing(true);

    // VALIDASI 1: Cek username kosong
    // Dikembangkan: bisa tambahkan validasi format email atau minimum karakter
    if(login.RecordLogin.username() == ""){
        swal("Peringatan!", "Username harus diisi!", "warning");
        model.Processing(false);
        return;
    }

    // VALIDASI 2: Cek password kosong
    // Dikembangkan: bisa tambahkan validasi panjang password minimal 6 karakter
    if(login.RecordLogin.password() == ""){
        swal("Peringatan!", "Password harus diisi!", "warning");
        model.Processing(false);
        return;
    }   

    // URL endpoint untuk validasi login
    // Dikembangkan: bisa diubah menjadi endpoint API REST atau tambahkan parameter tambahan
    var url = "<?= base_url('login/LoginController/get_valid_login') ?>";

    // AJAX POST ke server untuk validasi login
    // Dikembangkan: bisa tambahkan header Authorization, timeout, atau handling error
    ajaxPost(url, login.RecordLogin, function(res){

        // LOGIN BERHASIL
        // Dikembangkan: bisa simpan token JWT, user data ke localStorage, atau redirect ke dashboard
        if(res.result == true){

            // Notifikasi sukses
            swal({
                title: "Berhasil",
                text: "Login Berhasil",
                icon: "success"
            });

            // Redirect ke halaman dashboard setelah 1 detik
            // Dikembangkan: bisa ditambah redirect ke halaman sebelumnya (intended page)
            setTimeout(function(){
                window.location.href = "<?= base_url('pabrik/DashboardController') ?>";
            }, 1000);

        } else {
            // LOGIN GAGAL
            // Menampilkan pesan error dari server
            // Dikembangkan: bisa tambahkan counter percobaan login gagal atau logging
            swal({
                title: "Login Gagal",
                text: res.message,
                icon: "error"
            });

            // Mematikan indikator loading
            model.Processing(false);
        }

    });
}
</script>

<!-- ===================== TAMPILAN FORM LOGIN ===================== -->
<!-- Layout HTML untuk halaman login -->
<!-- Dikembangkan: bisa ditambah background, animasi, atau tema yang lebih menarik -->
<div class="login-wrapper">

    <div class="login-card">

        <!-- ===================== HEADER LOGIN ===================== -->
        <!-- Bagian judul dan deskripsi halaman login -->
        <!-- Dikembangkan: bisa ditambah logo perusahaan atau slogan -->
        <div class="login-header">
            <h2>Login Sistem</h2>
            <p>Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- ===================== BODY LOGIN ===================== -->
        <!-- Bagian form input dan tombol login -->
        <div class="login-body">

            <!-- ===================== INPUT USERNAME ===================== -->
            <!-- Field untuk input username -->
            <!-- Dikembangkan: bisa ditambah autocomplete, placeholder dinamis, atau icon -->
            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Masukkan Username"
                    data-bind="value: login.RecordLogin.username">
            </div>

            <!-- ===================== INPUT PASSWORD ===================== -->
            <!-- Field untuk input password dengan toggle visibility -->
            <!-- Dikembangkan: bisa ditambah strength indicator, atau input OTP -->
            <div class="form-group">
                <label>Password</label>
            <div class="input-group-append">
                <input
                    id="password"
                    type="password"
                    class="form-control"
                    placeholder="Masukkan Password"
                    data-bind="value: login.RecordLogin.password">
                    
                    <!-- Tombol toggle show/hide password -->
                    <!-- Dikembangkan: bisa diganti icon animasi atau tooltip -->
                    <span class="input-group-text"
                        onclick="togglePassword()"
                        style="cursor:pointer;">
                        <i id="eyeIcon" class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

            <!-- ===================== TOMBOL LOGIN ===================== -->
            <!-- Button untuk submit form login -->
            <!-- Dikembangkan: bisa ditambah loading spinner di tombol, atau disabled saat proses -->
            <button
                type="button"
                class="btn-login"
                data-bind="click: login.prosesLogin">
                Login
            </button>

            <!-- ===================== LINK REGISTER (DINONAKTIFKAN) ===================== -->
            <!-- Link ke halaman register - dikomentari untuk sementara -->
            <!-- Dikembangkan: bisa diaktifkan kembali jika diperlukan -->
            <!-- <p class="mb-0 text-center" style="margin-top:15px;">
                <a href="<?= base_url('login/LoginController') ?>">Register a new membership</a>
            </p> -->

        </div>

    </div>

</div>

<!-- ===================== FUNGSI TOGGLE PASSWORD ===================== -->
<!-- Fungsi untuk menampilkan/menyembunyikan password -->
<!-- Dikembangkan: bisa dipindahkan ke file JS terpisah atau dibuat lebih reusable -->
<script>
function togglePassword() {
    // Mengambil elemen input password dan icon
    var password = document.getElementById("password");
    var icon = document.getElementById("eyeIcon");

    // Toggle type input antara 'password' dan 'text'
    // Dikembangkan: bisa tambahkan animasi transisi atau sound effect
    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login · HP Factory</title>
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* ----- RESET & GLOBAL ----- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      overflow: hidden;
      position: relative;
      background: linear-gradient(-45deg, #080d1a, #0f172a, #16203a, #0b1224);
      background-size: 400% 400%;
      animation: gradientBG 16s ease infinite;
    }

    /* ----- GLOW ORBS (redup) ----- */
    body::before,
    body::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      filter: blur(140px);
      z-index: 0;
    }

    body::before {
      width: 500px;
      height: 500px;
      top: -180px;
      left: -180px;
      background: rgba(25, 70, 180, 0.18);
      animation: floatBlue 12s ease-in-out infinite alternate;
    }

    body::after {
      width: 420px;
      height: 420px;
      bottom: -150px;
      right: -120px;
      background: rgba(10, 40, 120, 0.22);
      animation: floatBlue2 15s ease-in-out infinite alternate;
    }

    /* ----- STARS ----- */
    .login-wrapper::before {
      content: "";
      position: fixed;
      inset: 0;
      pointer-events: none;
      opacity: 0.07;
      background-image:
        radial-gradient(circle, rgba(200, 220, 255, 0.8) 1.2px, transparent 1.2px);
      background-size: 50px 50px;
      animation: starsMove 48s linear infinite;
      z-index: 0;
    }

    /* ----- WRAPPER ----- */
    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      position: relative;
      z-index: 1;
      padding: 20px;
    }

    /* ----- CARD (kehitaman) ----- */
    .login-card {
      width: 440px;
      margin-top: -60px;
      position: relative;
      overflow: hidden;
      border-radius: 30px;
      background: rgba(8, 14, 30, 0.92);
      backdrop-filter: blur(22px) saturate(180%);
      border: 1px solid rgba(60, 120, 255, 0.15);
      box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.75),
        0 0 45px rgba(25, 70, 200, 0.15);
      transition: 0.4s cubic-bezier(0.2, 0.9, 0.4, 1);
      animation:
        popup 0.9s ease,
        floating 7s ease-in-out infinite;
    }

    .login-card::after {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: 30px;
      box-shadow:
        inset 0 1px 0 rgba(255, 255, 255, 0.04),
        inset 0 -1px 0 rgba(255, 255, 255, 0.02);
      pointer-events: none;
    }

    .login-card::before {
      content: "";
      position: absolute;
      inset: -2px;
      border-radius: 32px;
      background: linear-gradient(45deg, #142a5a, #2a5ac0, #1a3e8a, #0f2048);
      background-size: 300% 300%;
      animation: borderGlow 9s linear infinite;
      z-index: -1;
      opacity: 0.6;
    }

    .login-card:hover {
      transform: translateY(-6px) scale(1.005);
      box-shadow:
        0 35px 70px rgba(0, 0, 0, 0.8),
        0 0 55px rgba(30, 80, 230, 0.25);
      border-color: rgba(70, 140, 255, 0.2);
    }

    /* ----- HEADER (gelap) ----- */
    .login-header {
      position: relative;
      overflow: hidden;
      text-align: center;
      padding: 32px 25px 24px;
      color: #dce8ff;
      background: linear-gradient(145deg, #09142a, #112347);
      border-bottom: 1px solid rgba(60, 120, 255, 0.08);
    }

    .login-header::before {
      content: "";
      position: absolute;
      width: 280px;
      height: 280px;
      top: -140px;
      right: -80px;
      border-radius: 50%;
      background: rgba(40, 100, 255, 0.04);
      filter: blur(40px);
    }

    .login-header::after {
      content: "";
      position: absolute;
      top: 0;
      left: -60%;
      width: 40%;
      height: 100%;
      background: rgba(255, 255, 255, 0.03);
      transform: skewX(-25deg);
      animation: shineHeader 12s linear infinite;
    }

    /* ===== LOGO HP FACTORY (SESUAI GAMBAR) ===== */
    .login-header{
        position:relative;
    }

    .logo-right{
        position:absolute;
        top:18px;
        right:20px;
        width:70px;
        height:70px;
        object-fit:contain;
        z-index:100;
        filter:drop-shadow(0 0 15px rgba(59,130,246,.5));
    }
    .logo-factory {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 3;
      margin-bottom: 10px;
    }

    .logo-factory .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-factory .brand .hp-icon {
      font-size: 42px;
      color: #7aa9ff;
      filter: drop-shadow(0 0 18px rgba(50, 120, 255, 0.35));
    }

    .logo-factory .brand h1 {
      font-size: 42px;
      font-weight: 800;
      letter-spacing: 4px;
      background: linear-gradient(135deg, #d4e4ff, #6a9cf5);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 0 30px rgba(50, 120, 255, 0.12);
    }

    .logo-factory .brand h1 .factory {
      font-weight: 300;
      background: linear-gradient(135deg, #8ab4ff, #4a7ad8);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      letter-spacing: 6px;
      margin-left: 4px;
    }

    .logo-factory .tagline {
      font-size: 13px;
      letter-spacing: 3px;
      color: rgba(150, 190, 255, 0.5);
      font-weight: 300;
      margin-top: 4px;
      text-transform: uppercase;
    }

    .login-header h2 {
      font-size: 24px;
      font-weight: 600;
      margin-top: 14px;
      letter-spacing: 0.5px;
      color: #dce8ff;
      position: relative;
      z-index: 2;
    }

    .login-header p {
      margin-top: 4px;
      color: rgba(170, 200, 255, 0.5);
      font-weight: 300;
      font-size: 14px;
      letter-spacing: 0.5px;
      position: relative;
      z-index: 2;
    }

    /* ----- BODY (form) ----- */
    .login-body {
      padding: 30px 28px 34px;
    }

    .form-group {
      margin-bottom: 22px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #b8cef5;
      font-weight: 500;
      letter-spacing: 0.3px;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      height: 50px;
      padding: 0 18px;
      border-radius: 14px;
      border: 1px solid rgba(60, 130, 255, 0.12);
      background: rgba(6, 14, 34, 0.7);
      backdrop-filter: blur(6px);
      color: #e8f0ff;
      transition: 0.3s;
      font-size: 15px;
    }

    .form-control:hover {
      border-color: rgba(70, 150, 255, 0.25);
      background: rgba(10, 20, 48, 0.8);
    }

    .form-control:focus {
      outline: none;
      transform: translateY(-2px);
      border-color: #3a7aff;
      background: rgba(8, 18, 44, 0.9);
      box-shadow: 0 0 24px rgba(30, 80, 230, 0.15);
    }

    .form-control::placeholder {
      color: rgba(150, 180, 230, 0.25);
      font-weight: 300;
    }

    /* ----- BUTTON (gelap elegan) ----- */
    .btn-login {
      width: 100%;
      height: 54px;
      border: none;
      border-radius: 16px;
      cursor: pointer;
      overflow: hidden;
      position: relative;
      color: #fff;
      font-size: 16px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      background: linear-gradient(135deg, #0f276a, #1d4ed8);
      box-shadow: 0 8px 30px rgba(20, 60, 200, 0.25);
      transition: 0.35s;
      margin-top: 6px;
    }

    .btn-login::before,
    .btn-login::after {
      content: "";
      position: absolute;
      top: 0;
      height: 100%;
      transform: skewX(-25deg);
    }

    .btn-login::before {
      left: -120%;
      width: 50%;
      background: rgba(255, 255, 255, 0.05);
      transition: 0.8s;
    }

    .btn-login::after {
      inset: 0;
      width: 28%;
      background: linear-gradient(120deg,
          transparent,
          rgba(255, 255, 255, 0.06),
          transparent);
      animation: shineBtn 5.5s infinite;
    }

    .btn-login:hover::before {
      left: 150%;
    }

    .btn-login:hover {
      transform: translateY(-3px) scale(1.01);
      box-shadow: 0 16px 44px rgba(20, 60, 210, 0.35);
      background: linear-gradient(135deg, #16338a, #2563eb);
    }

    .btn-login:active {
      transform: scale(0.97);
    }

    /* ----- ANIMATIONS ----- */
    @keyframes gradientBG {
      0%,
      100% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
    }

    @keyframes floatBlue {
      0% {
        transform: translate(0, 0) scale(1);
      }
      100% {
        transform: translate(55px, 35px) scale(1.08);
      }
    }

    @keyframes floatBlue2 {
      0% {
        transform: translate(0, 0) scale(1);
      }
      100% {
        transform: translate(-45px, -40px) scale(1.12);
      }
    }

    @keyframes starsMove {
      from {
        transform: translateY(0);
      }
      to {
        transform: translateY(-50px);
      }
    }

    @keyframes popup {
      0% {
        opacity: 0;
        transform: translateY(70px) scale(0.92);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes floating {
      0%,
      100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    @keyframes borderGlow {
      0%,
      100% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
    }

    @keyframes shineHeader {
      from {
        left: -60%;
      }
      to {
        left: 170%;
      }
    }

    @keyframes shineBtn {
      0% {
        transform: translateX(-180%) skewX(-25deg);
      }
      35%,
      100% {
        transform: translateX(260%) skewX(-25deg);
      }
    }

    /* ----- RESPONSIVE ----- */
    @media (max-width: 500px) {
      .login-card {
        width: 96%;
        margin-top: -20px;
        border-radius: 24px;
      }
      .login-body {
        padding: 22px 18px 26px;
      }
      .login-header {
        padding: 24px 18px 18px;
      }
      .login-header h2 {
        font-size: 20px;
      }
      .logo-factory .brand h1 {
        font-size: 30px;
        letter-spacing: 2px;
      }
      .logo-factory .brand .hp-icon {
        font-size: 32px;
      }
      .logo-factory .tagline {
        font-size: 10px;
        letter-spacing: 2px;
      }
      .btn-login {
        height: 48px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">

      <!-- HEADER + LOGO HP FACTORY -->
      <div class="login-header">
        <div class="logo-factory">
          <div class="brand">
            <i class="fas fa-microchip hp-icon"></i>
            <h1>HP <span class="factory">FACTORY</span></h1>
          </div>
          <div class="tagline">SMART PRODUCTION · CONNECTING FUTURE</div>
        </div>
         <img src="<?= base_url('assets/img/logo_hp.png') ?>" class="logo-right">
        <h2>Login Sistem</h2>
        <p>Silakan masuk untuk melanjutkan</p>
      </div>

      <!-- BODY FORM -->
      <div class="login-body">
        <form>
          <div class="form-group">
            <label><i class="fas fa-user" style="margin-right:10px; opacity:0.6;"></i> Username</label>
            <input type="text" class="form-control" placeholder="Masukkan Username" value="admin">
          </div>
          <div class="form-group">
            <label><i class="fas fa-lock" style="margin-right:10px; opacity:0.6;"></i> Password</label>
            <input type="password" class="form-control" placeholder="Masukkan Password" value="12345678">
          </div>
          <button type="button" class="btn-login">
            <i class="fas fa-screwdriver" style="margin-right:12px;"></i> LOGIN
          </button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>