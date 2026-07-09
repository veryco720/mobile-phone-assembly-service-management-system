<script>

model.masterModel = {
    username : "",
    password : ""
}

var login = {
    title      : "Form Login",
    RecordLogin: ko.mapping.fromJS(model.masterModel)
}

login.prosesLogin = function(){

    model.Processing(true);

    if(login.RecordLogin.username() == ""){
        swal("Peringatan!", "Username harus diisi!", "warning");
        model.Processing(false);
        return;
    }

    if(login.RecordLogin.password() == ""){
        swal("Peringatan!", "Password harus diisi!", "warning");
        model.Processing(false);
        return;
    }

    var url = "<?= base_url('login/LoginController/get_valid_login') ?>";

    ajaxPost(url, login.RecordLogin, function(res){

        if(res.result == true){


        swal({
            title: "Berhasil",
            text: "Login Berhasil",
            icon: "success"
        });

        setTimeout(function(){
            window.location.href = "<?= base_url('QualityController') ?>";
        }, 1000);

    } else {

        swal({
            title: "Login Gagal",
            text: res.message,
            icon: "error"
        });

        model.Processing(false);

        }

    });
}

</script>


<div class="login-wrapper">

    <div class="login-card">

        <div class="login-header">
            <h2>Login Sistem</h2>
            <p>Silakan masuk untuk melanjutkan</p>
        </div>

        <div class="login-body">

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Masukkan Username"
                    data-bind="value: login.RecordLogin.username">
            </div>

            <div class="form-group">
                <label>Password</label>
            <div class="input-group-append">
                <input
                    id="password"
                    type="password"
                    class="form-control"
                    placeholder="Masukkan Password"
                    data-bind="value: login.RecordLogin.password">
                    
                    
                    <span class="input-group-text"
                        onclick="togglePassword()"
                        style="cursor:pointer;">
                        <i id="eyeIcon" class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

            <button
                type="button"
                class="btn-login"
                data-bind="click: login.prosesLogin">
                Login
            </button>

            <!-- <p class="mb-0 text-center" style="margin-top:15px;">
                <a href="<?= base_url('login/LoginController') ?>">Register a new membership</a>
            </p> -->

        </div>

    </div>

</div>

<script>
function togglePassword() {
    var password = document.getElementById("password");
    var icon = document.getElementById("eyeIcon");

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

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:linear-gradient(-45deg,#07182f,#0b2e59,#154c8a,#1d4ed8);
    background-size:400% 400%;
    animation:gradientBG 12s ease infinite;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    position:relative;
    overflow:hidden;
}

/* =========================
   Glow Background
========================= */

body::before{
    content:"";
    position:absolute;
    width:420px;
    height:420px;
    border-radius:50%;
    background:rgba(59,130,246,.30);
    filter:blur(120px);
    top:-120px;
    left:-120px;
    animation:floatBlue 10s ease-in-out infinite;
    z-index:0;
}

body::after{
    content:"";
    position:absolute;
    width:350px;
    height:350px;
    border-radius:50%;
    background:rgba(37,99,235,.25);
    filter:blur(120px);
    bottom:-120px;
    right:-80px;
    animation:floatBlue2 12s ease-in-out infinite;
    z-index:0;
}

/* =========================
   Partikel Bintang
========================= */

.login-wrapper::before{
    content:"";
    position:fixed;
    inset:0;
    background-image:
    radial-gradient(circle,rgba(255,255,255,.7) 1px,transparent 1px);
    background-size:45px 45px;
    opacity:.15;
    animation:starsMove 30s linear infinite;
    pointer-events:none;
}

/* =========================
   Login Wrapper
========================= */

.login-wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    position:relative;
    z-index:1;
}

/* =========================
   Login Card
========================= */

.login-card{

    width:420px;

    margin-top:-90px;

    background:rgba(255,255,255,.94);

    border-radius:20px;

    overflow:hidden;

    border:1px solid rgba(255,255,255,.25);

    backdrop-filter:blur(15px);

    box-shadow:
    0 15px 35px rgba(0,0,0,.35),
    0 0 30px rgba(37,99,235,.30);

    position:relative;

    transition:.35s;

    animation:popup .8s ease;
}

/* Border Glow */

.login-card::before{

    content:"";

    position:absolute;

    inset:-2px;

    border-radius:22px;

    background:linear-gradient(
    45deg,
    #2563eb,
    #60a5fa,
    #2563eb,
    #1d4ed8);

    background-size:300% 300%;

    animation:borderGlow 6s linear infinite;

    z-index:-1;
}

.login-card:hover{

    transform:translateY(-6px);

    box-shadow:
    0 25px 45px rgba(0,0,0,.45),
    0 0 45px rgba(59,130,246,.45);
}

/* =========================
   Header
========================= */

.login-header{

    text-align:center;

    padding:35px 30px;

    background:linear-gradient(135deg,#0f3d91,#2563eb);

    color:#fff;
}

.login-header h2{

    color:#fff;

    font-size:32px;

    font-weight:700;
}

.login-header p{

    color:rgba(255,255,255,.85);

    margin-top:10px;
}

/* =========================
   Body
========================= */

.login-body{
    padding:30px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{

    display:block;

    margin-bottom:8px;

    color:#234;

    font-weight:600;
}

.form-control{

    width:100%;

    height:48px;

    border-radius:10px;

    border:1px solid #bfd4ff;

    padding:0 15px;

    font-size:15px;

    transition:.3s;
}

.form-control:focus{

    outline:none;

    border-color:#2563eb;

    box-shadow:0 0 18px rgba(37,99,235,.35);
}

/* =========================
   Button Login
========================= */

.btn-login{

    width:100%;

    height:50px;

    border:none;

    border-radius:10px;

    background:linear-gradient(135deg,#1d4ed8,#2563eb);

    color:#fff;

    font-size:16px;

    font-weight:600;

    cursor:pointer;

    overflow:hidden;

    position:relative;

    transition:.35s;

    box-shadow:0 8px 20px rgba(37,99,235,.35);
}

.btn-login::before{

    content:"";

    position:absolute;

    top:0;

    left:-120%;

    width:60%;

    height:100%;

    background:rgba(255,255,255,.35);

    transform:skewX(-25deg);

    transition:.8s;
}

.btn-login:hover::before{

    left:150%;
}

.btn-login:hover{

    transform:translateY(-3px);

    background:linear-gradient(135deg,#1e40af,#2563eb);

    box-shadow:0 15px 30px rgba(37,99,235,.45);
}

/* =========================
   Animasi
========================= */

@keyframes gradientBG{

    0%{background-position:0% 50%;}

    50%{background-position:100% 50%;}

    100%{background-position:0% 50%;}
}

@keyframes floatBlue{

    0%,100%{transform:translate(0,0);}

    50%{transform:translate(40px,30px);}
}

@keyframes floatBlue2{

    0%,100%{transform:translate(0,0);}

    50%{transform:translate(-40px,-30px);}
}

@keyframes starsMove{

    from{
        transform:translateY(0);
    }

    to{
        transform:translateY(-45px);
    }
}

@keyframes popup{

    0%{
        opacity:0;
        transform:translateY(60px) scale(.92);
    }

    100%{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}

@keyframes borderGlow{

    0%{
        background-position:0% 50%;
    }

    50%{
        background-position:100% 50%;
    }

    100%{
        background-position:0% 50%;
    }
}

</style>