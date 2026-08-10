<script>
// ===================== MODEL PROFILE =====================
// Model untuk data profile user dan perubahan password
// Dikembangkan: bisa tambahkan field seperti email, foto_profile, no_telepon, dll.
model.profileModel = {
    username: "<?= $this->session->userdata('username') ?>", // Username dari session
    password_lama: "",
    password_baru: "",
    konfirmasi_password: ""
}

// ===================== OBJEK PROFILE =====================
// Objek utama untuk mengelola profile user
// Dikembangkan: bisa tambahkan properti untuk edit profile (email, foto, dll.)
var profile = {
    RecordProfile: ko.mapping.fromJS(model.profileModel), // Model untuk form input
    Mode: ko.observable('View') // Mode: 'View' atau 'Edit' (untuk pengembangan kedepan)
}

// ===================== FUNGSI UBAH PASSWORD =====================
// Fungsi untuk mengubah password user
// Dikembangkan: bisa tambahkan validasi password harus mengandung huruf besar, angka, dan simbol
profile.ubahPassword = function() {
    
    // ===================== VALIDASI PASSWORD LAMA =====================
    // Validasi: Password lama harus diisi
    // Dikembangkan: bisa tambahkan validasi dengan regex
    if (profile.RecordProfile.password_lama() == "") {
        swal(
            "Peringatan",
            "Password lama wajib diisi",
            "warning"
        );
        return;
    }

    // ===================== VALIDASI PASSWORD BARU =====================
    // Validasi: Password baru minimal 6 karakter
    // Dikembangkan: bisa tambahkan validasi password harus mengandung huruf dan angka
    if (profile.RecordProfile.password_baru().length < 6) {
        swal(
            "Peringatan",
            "Password minimal 6 karakter",
            "warning"
        );
        return;
    }

    // ===================== VALIDASI KONFIRMASI PASSWORD =====================
    // Validasi: Password baru dan konfirmasi harus sama
    // Dikembangkan: bisa tambahkan validasi password baru tidak boleh sama dengan password lama
    if (profile.RecordProfile.password_baru() != profile.RecordProfile.konfirmasi_password()) {
        swal(
            "Peringatan",
            "Konfirmasi password tidak sesuai",
            "warning"
        );
        return;
    }

    // ===================== PROSES UBAH PASSWORD =====================
    // URL untuk request ke server
    // Dikembangkan: bisa tambahkan endpoint untuk logging aktivitas
    var url = "<?= base_url('pabrik/ProfileController/ubahPassword') ?>";

    // Kirim request AJAX ke server
    // Dikembangkan: bisa tambahkan loading indicator
    ajaxPost(
        url,
        profile.RecordProfile,
        function(res) {
            if (res.result == true) {
                // ===== SUKSES =====
                // Notifikasi sukses dan redirect ke logout
                // Dikembangkan: bisa direct ke halaman login dengan pesan sukses
                swal(
                    "Berhasil",
                    res.message,
                    "success"
                );

                // Redirect ke logout setelah 1.5 detik
                setTimeout(function() {
                    window.location.href = "<?= base_url('login/LoginController/logout') ?>";
                }, 1500);
            } else {
                // ===== GAGAL =====
                // Notifikasi gagal dengan pesan dari server
                swal(
                    "Gagal",
                    res.message,
                    "error"
                );
            }
        }
    );
};

// Register profile ke model global
// Dikembangkan: bisa ditambah untuk akses dari komponen lain
model.profile = profile;
</script>

<!-- ===================== TAMPILAN HTML ===================== -->
<!-- Layout untuk halaman profile user -->
<!-- Dikembangkan: bisa ditambah breadcrumb, foto profil, atau informasi tambahan -->
<div class="content-wrapper">
    
    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== MAIN CONTENT ===================== -->
    <section class="content" data-bind="with: profile">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    
                    <!-- ===================== NAVIGASI TAB ===================== -->
                    <!-- Tab navigasi (hanya profile yang aktif) -->
                    <!-- Dikembangkan: bisa tambahkan tab untuk edit profile atau aktivitas -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabprofile" data-toggle="tab">
                                Profile
                            </a>
                        </li>
                    </ul>
                    
                    <!-- ===================== KONTEN TAB ===================== -->
                    <div class="content tab-content" id="tabnavform-content">
                        <div class="tab-pane active" id="tabprofile">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Profile</h3>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            
                                            <!-- ===================== AVATAR / ICON USER ===================== -->
                                            <!-- Dikembangkan: bisa diganti dengan foto profil user -->
                                            <br>
                                            <i class="fa fa-user-circle fa-5x text-primary"></i>
                                            <br><br>

                                            <!-- ===================== SELAMAT DATANG ===================== -->
                                            <!-- Dikembangkan: bisa ditambah nama lengkap atau greeting dengan waktu -->
                                            <h2 style="font-size: 28px; font-weight: 600;">Selamat Datang</h2>
                                            
                                            <!-- ===================== USERNAME ===================== -->
                                            <!-- Menampilkan username dari session -->
                                            <h2 style="font-size: 32px; font-weight: 700; color: #007bff;" 
                                                data-bind="text: RecordProfile.username">
                                            </h2>
                                            <br>

                                            <!-- ===================== ROLE ===================== -->
                                            <!-- Menampilkan role user -->
                                            <!-- Dikembangkan: bisa ditambah badge atau icon untuk role -->
                                            <div style="display: inline-block; padding: 10px 40px; background: #f8f9fc; border-radius: 8px; border: 1px solid #e3e6f0;">
                                                <span style="font-size: 20px; font-weight: 600; color: #4e73df;">Role</span>
                                                <span style="font-size: 24px; font-weight: 700; color: #2c3e50; margin-left: 15px;">
                                                    <?php echo $role; ?>
                                                </span>
                                            </div>

                                            <br><br>
                                            
                                            <!-- ===================== STATUS LOGIN ===================== -->
                                            <!-- Dikembangkan: bisa ditambah informasi last login atau IP -->
                                            <p style="font-size: 18px; color: #6c757d;">Anda berhasil login ke sistem.</p>
                                            <br>
                                            <hr>

                                            <!-- ===================== FORM UBAH PASSWORD ===================== -->
                                            <!-- Card untuk form ubah password -->
                                            <!-- Dikembangkan: bisa ditambah form untuk edit profile (email, no hp, dll.) -->
                                            <div class="card" style="max-width: 600px; margin: 0 auto;">
                                                <div class="card-header">
                                                    <h4 class="mb-0">Ubah Password</h4>
                                                </div>
                                                <div class="card-body">
                                                    
                                                    <!-- ===================== PASSWORD LAMA ===================== -->
                                                    <!-- Field: Password Lama dengan toggle visibility -->
                                                    <!-- Dikembangkan: bisa tambahkan validasi inline -->
                                                    <div class="form-group">
                                                        <label for="password_lama" style="font-weight: 600;">Password Lama</label>
                                                        <div class="input-group">
                                                            <input 
                                                                type="password" 
                                                                id="password_lama"
                                                                class="form-control" 
                                                                placeholder="Masukkan password lama"
                                                                data-bind="value:RecordProfile.password_lama"
                                                            >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"
                                                                    onclick="togglePassword('password_lama', 'eyeIconLama')"
                                                                    style="cursor:pointer;">
                                                                    <i id="eyeIconLama" class="fa fa-eye"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ===================== PASSWORD BARU ===================== -->
                                                    <!-- Field: Password Baru dengan toggle visibility -->
                                                    <!-- Dikembangkan: bisa tambahkan strength indicator -->
                                                    <div class="form-group">
                                                        <label for="password_baru" style="font-weight: 600;">Password Baru</label>
                                                        <div class="input-group">
                                                            <input 
                                                                type="password" 
                                                                id="password_baru"
                                                                class="form-control" 
                                                                placeholder="Masukkan password baru (min. 6 karakter)"
                                                                data-bind="value:RecordProfile.password_baru"
                                                            >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"
                                                                    onclick="togglePassword('password_baru', 'eyeIconBaru')"
                                                                    style="cursor:pointer;">
                                                                    <i id="eyeIconBaru" class="fa fa-eye"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!-- Dikembangkan: bisa tambahkan progress bar strength -->
                                                        <!-- <div class="progress mt-1" style="height: 5px;">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                                        </div> -->
                                                    </div>

                                                    <!-- ===================== KONFIRMASI PASSWORD ===================== -->
                                                    <!-- Field: Konfirmasi Password dengan toggle visibility -->
                                                    <!-- Dikembangkan: bisa tambahkan validasi langsung (live validation) -->
                                                    <div class="form-group">
                                                        <label for="konfirmasi_password" style="font-weight: 600;">Konfirmasi Password</label>
                                                        <div class="input-group">
                                                            <input 
                                                                type="password" 
                                                                id="konfirmasi_password"
                                                                class="form-control" 
                                                                placeholder="Konfirmasi password baru"
                                                                data-bind="value:RecordProfile.konfirmasi_password"
                                                            >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"
                                                                    onclick="togglePassword('konfirmasi_password', 'eyeIconKonfirmasi')"
                                                                    style="cursor:pointer;">
                                                                    <i id="eyeIconKonfirmasi" class="fa fa-eye"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ===================== TOMBOL UBAH PASSWORD ===================== -->
                                                    <!-- Dikembangkan: bisa tambahkan loading spinner saat proses -->
                                                    <button 
                                                        class="btn btn-primary btn-block" 
                                                        data-bind="click:ubahPassword"
                                                        style="font-weight: 600; padding: 10px;"
                                                    >
                                                        <i class="fa fa-save"></i> Ubah Password
                                                    </button>
                                                </div>
                                            </div>

                                            <br>
                                            <!-- ===================== TOMBOL LOGOUT ===================== -->
                                            <!-- Dikembangkan: bisa tambahkan konfirmasi sebelum logout -->
                                            <a href="<?= base_url('login/LoginController/logout') ?>" class="btn btn-danger btn-lg" style="padding: 12px 40px; font-weight: 600;">
                                                <i class="fa fa-sign-out"></i> Logout
                                            </a>
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ===================== FUNGSI TOGGLE PASSWORD ===================== -->
<!-- Fungsi untuk toggle visibility password -->
// Dikembangkan: bisa dipindahkan ke file JS terpisah
// Dikembangkan: bisa dibuat lebih reusable dengan multiple instance
<script>
function togglePassword(inputId, iconId) {
    var password = document.getElementById(inputId);
    var icon = document.getElementById(iconId);

    // Toggle type input antara 'password' dan 'text'
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

<!-- ===================== STYLE TAMBAHAN ===================== -->
<!-- Dikembangkan: bisa dipindahkan ke file CSS terpisah -->
<style>
.card {
    border-radius: 10px;
}

.card-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.nav-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
}

.input-group-append {
    cursor: pointer;
}

/* Animasi hover untuk tombol */
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    transition: all 0.3s ease;
}
</style>