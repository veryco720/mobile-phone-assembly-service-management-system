<script>
// ===================== MODEL UTAMA =====================
// Model data user dengan properti lengkap
// Dikembangkan: bisa tambahkan field seperti email, no_telepon, foto_profile, dll.
model.masterModel = {
    id_user: 0, 
    id_karyawan: "",
    username: "",
    password: "",
    role: "",
    status: "aktif"
}

// ===================== OBJEK MATERIAL (USER MANAGEMENT) =====================
// Objek utama untuk mengelola data user
// Dikembangkan: bisa tambahkan properti untuk pagination, export data, atau logging aktivitas
var material = {
    title: "Data User",
    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    DataFilter: ko.observableArray(['username']), // Opsi filter yang tersedia
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('username'), // Value filter yang dipilih
    
    // ===================== DATA KARYAWAN =====================
    // Untuk dropdown pilihan karyawan
    // Dikembangkan: bisa ditambah filter karyawan aktif/nonaktif
    SELECTKARYAWAN: ko.observableArray([]),
    
    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan status, role, atau tanggal
    SELECTFILTERVALUE:  [
        { name: 'Username', value: 'tb1.username' },
        { name: 'role', value: 'tb1.role' }
    ],
    
    // ===================== HAK AKSES =====================
    // Properti untuk mengontrol button berdasarkan role user login
    // Dikembangkan: bisa ditambah hak akses untuk export, print, atau approval
    canView   : ko.observable(false),
    canInsert : ko.observable(false),
    canUpdate : ko.observable(false),
    canDelete : ko.observable(false),
    
    // ===================== ROLE USER LOGIN =====================
    // Menyimpan role dari session untuk pengecekan akses
    // Dikembangkan: bisa ditambah multiple role atau user_id
    role : ko.observable("<?= $this->session->userdata('role');?>"),        
}

// ===================== FUNGSI FILTER DATA =====================
// Memuat ulang DataTable dengan filter yang diterapkan
// Dikembangkan: bisa tambahkan filter tanggal range atau filter multiple
material.filtermaterial = function() {
    material.grid.ajax.reload();
}

// ===================== FUNGSI RESET FILTER =====================
// Mereset filter dan menampilkan semua data
// Dikembangkan: bisa reset ke filter default atau filter terakhir
material.filterreset = function() {
    material.FilterText('');
    material.grid.ajax.reload(null, false);
}

// ===================== FUNGSI KEMBALI =====================
// Kembali ke tampilan list dan reset form
// @param tab: tab yang akan diaktifkan (0=form, 1=list)
// Dikembangkan: bisa tambahkan konfirmasi jika ada perubahan yang belum disimpan
material.back = function(tab) {
    material.Mode('');
    material.grid.ajax.reload(null, false); // Reload DataTable
    ko.mapping.fromJS(model.masterModel, material.Recordmaterial); // Reset form ke default
    model.activetab(tab); // Pindah ke tab yang ditentukan
}

// ===================== FUNGSI SELECT DATA =====================
// Mengambil data user berdasarkan ID untuk diedit
// @param id: ID user yang akan diedit
// Dikembangkan: bisa tambahkan loading animasi atau disabled form saat proses
material.selectdata = function(id) {
    model.Processing(true);
    ajaxPost("<?php echo site_url('pabrik/UserController/getDataSelect') ?>", {
        id_user: id
    }, function(res) {
        console.log(res[0]); // Debug: cek data yang diterima
        material.back(0); // Pindah ke tab form
        ko.mapping.fromJS(res[0], material.Recordmaterial); // Isi form dengan data
        material.Mode("Update"); // Ubah mode menjadi Update
        model.Processing(false);
    });
}

// ===================== FUNGSI SAVE =====================
// Menyimpan data baru atau update data existing
// Dikembangkan: bisa tambahkan validasi di sisi client (email, password strength, dll.)
material.save = function() {
    model.Processing(true);
    var val = material.Recordmaterial;
    
    // Konfirmasi sebelum menyimpan
    // Dikembangkan: bisa tambahkan validasi field wajib diisi
    swal({
        title: "Perhatian",
        text: "Anda akan simpan data ini?",
        type: "info",
        className: 'animate_animated animate_fadeInUp',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    }, function(isConfirm) {
        if (isConfirm) {
            // Validasi username tidak boleh kosong
            // Dikembangkan: tambahkan validasi untuk field lain
            if (material.Recordmaterial.username() == "") {
                setTimeout(function() {
                    swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                }, );
            } else {
                // Proses simpan/update
                if (showLoaderOnConfirm = true) {
                    // Tentukan URL berdasarkan mode (Insert atau Update)
                    // Dikembangkan: bisa tambahkan endpoint untuk soft delete atau archive
                    var url = "<?php echo base_url('pabrik/UserController/save') ?>";
                    if (material.Mode() === 'Update')
                        url = "<?php echo base_url('pabrik/UserController/update') ?>";
                    
                    // Kirim data ke server
                    ajaxPost(url, material.Recordmaterial, function(res) {
                        console.log(res.result);
                        if (res.result == true || material.Mode() == "Update") {
                            // Notifikasi sukses sesuai mode
                            if (res.result == true) {
                                setTimeout(function() {
                                    swal({
                                        title: "Good job!",
                                        text: "Data Berhasil di input!",
                                        icon: "success",
                                    });
                                }, 2000);
                            }
                            if (material.Mode() == "Update") {
                                setTimeout(function() {
                                    swal({
                                        title: "Good job!",
                                        text: "Data Berhasil di ubah!",
                                        icon: "success",
                                    });
                                }, 2000);
                            }
                            material.back(1); // Kembali ke list setelah sukses
                        }
                    });
                }
            }
        }
        model.Processing(false);
    }); // END isconfirm swal
    model.Processing(false);
}

// ===================== FUNGSI DELETE =====================
// Menghapus data user berdasarkan ID
// @param id: ID user yang akan dihapus
// Dikembangkan: bisa tambahkan konfirmasi dengan alasan atau soft delete
material.remove = function(id) {
    // Konfirmasi sebelum menghapus
    // Dikembangkan: bisa tambahkan validasi relasi (misal: user sudah punya transaksi)
    swal({
        title: "Are you sure?",
        text: "Delete this data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        closeOnConfirm: false,
    }, function(isConfirm) {
        if (isConfirm) {
            ajaxPost("<?php echo base_url('pabrik/UserController/delete') ?>", {
                id: id // Pastikan ini adalah ID yang benar
            }, function(res) {
                if (res.result) {
                    // Jika berhasil dihapus
                    material.back(1); // Refresh list
                    swal("Deleted!", "Data has been deleted successfully.", "success");
                } else {
                    // Jika gagal dihapus karena ada relasi
                    swal("Failed!", res.message, "warning");
                }
            });
        }
    }); 
}

// ===================== FUNGSI LOAD KARYAWAN =====================
// Mengambil data karyawan dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter karyawan aktif
material.loadKaryawan = function () {
    $.ajax({
        url:  `<?php echo site_url('pabrik/UserController/getKaryawan') ?>`,
        type: "GET",
        dataType: "json",
        success: function (res) {
            console.log(res) // Debug: cek data karyawan
            material.SELECTKARYAWAN(res); // Isi dropdown karyawan
        },
        error: function (err) {
            console.log("Gagal load karyawan", err);
        }
    }); 
};

// ===================== FUNGSI CHECK ROLE =====================
// Mengecek hak akses user berdasarkan role
// Dikembangkan: bisa ditambah cache atau multiple modul
material.checkRole = function(){
    ajaxPost("<?php echo site_url('pabrik/AksesController/getAkses')?>",
        {
            role : material.role() // Role dari session
        },
        function(rows){
            // Cari akses untuk modul User (id_modul = 2)
            // Dikembangkan: bisa dibuat dinamis berdasarkan modul yang aktif
            var akses = rows.find(function(item){
                return item.id_modul == 2;
            });
            if(akses){
                // Set hak akses berdasarkan data dari database
                material.canView(Number(akses.can_view)===1);
                material.canInsert(Number(akses.can_insert)===1);
                material.canUpdate(Number(akses.can_update)===1);
                material.canDelete(Number(akses.can_delete)===1);
            }
            // Debug: cek hak akses yang didapat
            console.log("canInsert", material.canInsert());
            console.log("canUpdate", material.canUpdate());
            console.log("canDelete", material.canDelete());
        }
    );
};
</script>

<!-- ===================== TAMPILAN HTML ===================== -->
<!-- Layout untuk halaman manajemen user -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik user, atau grafik -->
<div class="content-wrapper">
    
    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Users</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== MAIN CONTENT ===================== -->
    <section class="content" data-bind="with: material">
        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">
                    
                    <!-- ===================== NAVIGASI TAB ===================== -->
                    <!-- Tab navigasi antara Form Tambah dan List Data -->
                    <!-- Dikembangkan: bisa tambahkan tab untuk import/export data -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link" href="#tabform" data-toggle="tab" data-bind="visible: canInsert()">
                                Tambah User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#tablist" data-toggle="tab">
                                List Users
                            </a>
                        </li>
                    </ul>
                    
                    <!-- ===================== KONTEN TAB ===================== -->
                    <div class="content tab-content" id="tabnavform-content">
                        
                        <!-- ===================== TAB FORM ===================== -->
                        <!-- Form untuk tambah/edit data user -->
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">
                                    
                                    <!-- ===================== TOMBOL AKSI FORM ===================== -->
                                    <!-- Button untuk save, back, dan delete -->
                                    <!-- Dikembangkan: bisa tambahkan tombol reset, copy data, atau duplicate -->
                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group ">
                                                <!-- Tombol Kembali (hanya saat mode Update) -->
                                                <button class="btn btn-sm btn-warning" 
                                                        data-bind="click:function(){back(1);}, visible: Mode() == 'Update'" 
                                                        data-toggle="tooltip" data-placement="top" data-original-title="Kembali">
                                                    <i class="fa fa-arrow-left"></i> 
                                                </button>
                                                
                                                <!-- Tombol Simpan -->
                                                <button class="btn btn-sm btn-info" 
                                                        data-bind="click:save" 
                                                        data-toggle="tooltip" data-placement="top" data-original-title="simpan">
                                                    <span data-bind="data-original-title:Mode">
                                                        <i class="fa fa-save"></i> 
                                                    </span>
                                                </button>
                                                
                                                <!-- Tombol Hapus (hanya saat mode Update dan punya hak akses) -->
                                                <button class="btn btn-sm btn-danger" 
                                                        data-bind="click:function(){remove(Recordmaterial.id_user());}, visible: Mode() == 'Update' && canDelete()">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- ===================== FIELD FORM ===================== -->
                                <!-- Dikembangkan: bisa tambahkan validasi inline atau tooltip help -->
                                <div class="card-body" data-bind="with: Recordmaterial">
                                    
                                    <!-- Field: KARYAWAN (Dropdown) -->
                                    <!-- Dikembangkan: bisa tambahkan autocomplete atau search di dropdown -->
                                    <div class="form-group">
                                        <label>KARYAWAN</label>
                                        <fieldset class="form-group">
                                            <select class="form-control"
                                                    data-bind="
                                                        options: material.SELECTKARYAWAN,
                                                        optionsText: 'name',
                                                        optionsValue: 'value',
                                                        value: id_karyawan"
                                                    class="form-control" id="basicSelect">
                                            </select>
                                        </fieldset>
                                    </div>
                                    
                                    <!-- Field: USERNAME -->
                                    <!-- Dikembangkan: bisa tambahkan validasi unique username secara realtime -->
                                    <div class="form-group">
                                        <label>USERNAME</label>
                                        <input type="text"
                                               class="form-control"
                                               data-bind="value: username"
                                               placeholder="Masukkan Username">
                                    </div>
                                    
                                    <!-- Field: PASSWORD dengan toggle visibility -->
                                    <!-- Dikembangkan: bisa tambahkan strength meter atau generate password -->
                                    <div class="form-group">
                                        <label>PASSWORD</label>
                                        <div class="input-group-append">
                                            <input id="password"
                                                   type="password"
                                                   class="form-control"
                                                   data-bind="value: password"
                                                   placeholder="Masukkan Password">
                                            <span class="input-group-text"
                                                  onclick="togglePassword()"
                                                  style="cursor:pointer;">
                                                <i id="eyeIcon" class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Field: ROLE (Dropdown) -->
                                    <!-- Dikembangkan: bisa diambil dari database untuk dinamis -->
                                    <div class="form-group">
                                        <label for="alamat">ROLE</label>
                                        <select class="form-control" data-bind="value: role">
                                            <option value="">-- Pilih ROLE --</option>
                                            <option value="manager">Manager</option>
                                            <option value="supervisor">Supervisor</option>
                                            <option value="operator">Operator</option>
                                            <option value="qc">QC</option>
                                            <option value="admin">Admin</option>
                                        </select>                                        
                                    </div>
                                    
                                    <!-- Field: STATUS (Switch/Toggle) -->
                                    <!-- Dikembangkan: bisa tambahkan animasi pada toggle -->
                                    <div class="form-group">
                                        <label>
                                            STATUS
                                            <span class="badge bg-gray" data-bind="text: status() ? 'aktif' : 'nonaktif'"></span>
                                        </label><br>
                                        <label class="switch">
                                            <input type="checkbox" data-bind="checked: status">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    
                                </div> <!-- end card-body Recordmaterial -->
                            </div> <!-- end card -->
                        </div> <!-- end tabform -->
                        
                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua data user -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                
                                <!-- ===================== FILTER DATA ===================== -->
                                <!-- Panel filter untuk pencarian data -->
                                <!-- Dikembangkan: bisa tambahkan filter tanggal atau filter multiple -->
                                <div class="row p-t-23 ">
                                    <div class="col-sm-4 col-md-2">
                                        <fieldset class="form-group">
                                            <select name="" data-bind="
                                                options: SELECTFILTERVALUE,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value:FilterValue"
                                                class="form-control" id="basicSelect">
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-2 col-md-3">
                                        <div class="form-group ">
                                            <input data-bind="value:FilterText, event: { keyup: function(data, event) {
                                                        if (event.key === 'Enter') material.filtermaterial(); }}" 
                                                   id="" placeholder="Filter by data" class="form-control">
                                            <p>
                                                <small class="text-muted">Contoh: ketik <i> sabun </i> lalu <b>Enter</b></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-5 margFilter">
                                        <div class="form-group ">
                                            <!-- Tombol Reset Filter -->
                                            <button class="btn btn-md btn-danger" data-bind="click:filterreset">
                                                <span class="fa fa-retweet"></span>
                                            </button>
                                            <!-- Tombol Apply Filter -->
                                            <button class="btn btn-md btn-primary" data-bind="click:filtermaterial">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!-- end filter -->
                                
                                <!-- ===================== TABEL DATA USER ===================== -->
                                <!-- DataTable untuk menampilkan list user -->
                                <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-40 animated fadeIn">
                                        <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nama Karyawan</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end tablist -->
                        
                    </div> <!-- end tab-content -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container-fluid -->
    </section>
</div> <!-- end content-wrapper -->

<!-- ===================== SCRIPT TOGGLE PASSWORD ===================== -->
<!-- Fungsi untuk toggle visibility password di form -->
<!-- Dikembangkan: bisa dipindah ke file terpisah atau dibuat lebih reusable -->
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

<!-- ===================== INITIALIZATION ===================== -->
<!-- Script untuk inisialisasi DataTable dan loading data awal -->
<!-- Dikembangkan: bisa ditambah error handling, atau reload otomatis -->
<script>
    $(document).ready(function() {
        model.Processing(true);
        
        // Inisialisasi: Load data karyawan untuk dropdown
        // Dikembangkan: bisa ditambah caching untuk optimasi performance
        material.loadKaryawan(); 
        
        // Inisialisasi: Cek hak akses user
        // Dikembangkan: bisa ditambah listener untuk perubahan role
        material.checkRole(); 
        
        // Set tab default
        model.activetab(1);
        
        // ===================== INISIALISASI DATATABLE =====================
        // Konfigurasi DataTable untuk server-side processing
        // Dikembangkan: bisa ditambah opsi export, responsive, atau scroll
        material.grid = $("#myTable").DataTable({ 
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/UserController/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    // Kirim data filter ke server
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext'] = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    // Mapping response dari server ke DataTable format
                    json.recordsTotal = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    
                    if (json.Data)
                        return json.Data;
                    else
                        return [];
                },
            },
            "searching": false, // Nonaktifkan search bawaan DataTable
            "columns": [
                { "data": "id_user" },
                { "data": "nama_karyawan" },
                { "data": "username" },
                { 
                    "data": "password",
                    "render": function(data, type, full, meta) {
                        return "********"; // Menyembunyikan password di tabel
                    }
                },
                { "data": "role" },
                {
                    "data": "status",
                    "render": function(data, type, full, meta) {
                        // Render status dengan badge warna
                        return data == "aktif" ? 
                            "<span class='badge bg-success'>Aktif</span>" : 
                            "<span class='badge bg-danger'>Nonaktif</span>";
                    }
                },
                {
                    "data": "id_user",
                    "render": function(data) {
                        // Render tombol aksi berdasarkan hak akses
                        var tombol = "";
                        
                        // Tombol Edit (jika punya akses update)
                        if(material.canUpdate()){
                            tombol += "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> ";
                        }
                        
                        // Tombol Delete (jika punya akses delete)
                        if(material.canDelete()){
                            tombol += "<button class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")'><i class='fa fa-trash'></i></button>";
                        }
                        
                        // Kalau tidak punya hak apa pun
                        if(tombol == ""){
                            return "-";
                        }
                        return tombol;
                    }
                }
            ],
        });
        model.Processing(false);
    });
</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}
.switch input { display: none; }

.slider {
  position: absolute;
  cursor: pointer;
  background-color: #ccc;
  transition: .4s;
  top: 0; left: 0; right: 0; bottom: 0;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px; width: 18px;
  left: 3px; bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #28a745;
}

input:checked + .slider:before {
  transform: translateX(26px);
}
</style> 