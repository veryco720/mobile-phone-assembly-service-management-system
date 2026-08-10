<script>
// ===================== MODEL UTAMA =====================
// Model untuk data karyawan
// Dikembangkan: bisa tambahkan field seperti email, tanggal_lahir, foto, nik, dll.
model.masterModel = {
    id_karyawan: 0,
    nama_karyawan: "",
    jabatan: "",
    no_hp: "",
    alamat: "",
    status: "aktif"
}

// ===================== OBJEK MATERIAL (MANAJEMEN KARYAWAN) =====================
// Objek utama untuk mengelola data karyawan
// Dikembangkan: bisa tambahkan properti untuk filter berdasarkan departemen atau cabang
var material = {
    title: "Data Satuan",
    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    DataFilter: ko.observableArray(['nama_karyawan']), // Opsi filter yang tersedia
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('nama_karyawan'), // Value filter yang dipilih

    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan rentang tanggal atau status
    SELECTFILTERVALUE:  [
        { name: 'NAME', value: 'nama_karyawan'},
        { name: 'JABATAN', value: 'jabatan'},
        { name: 'NO HP', value: 'no_hp'},
        { name: 'ALAMAT', value: 'alamat'},    
        { name: 'STATUS', value: 'status'},
    ],

    // ===================== HAK AKSES =====================
    // Properti untuk mengontrol button berdasarkan role user login
    // Dikembangkan: bisa ditambah hak akses untuk export, print, atau import
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
// Dikembangkan: bisa tambahkan filter multiple atau filter custom
material.filtermaterial = function() {
    material.grid.ajax.reload();
}

// ===================== FUNGSI RESET FILTER =====================
// Mereset filter dan menampilkan semua data
// Dikembangkan: bisa reset ke filter default
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
// Mengambil data karyawan berdasarkan ID untuk diedit
// @param id: ID karyawan yang akan diedit
// Dikembangkan: bisa tambahkan loading animasi atau disabled form saat proses
material.selectdata = function(id) {
    model.Processing(true);
    ajaxPost("<?php echo site_url('pabrik/KaryawanController/getDataSelect') ?>", {
        id: id
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
// Dikembangkan: bisa tambahkan validasi format email, no HP, atau NIK
material.save = function() {
    model.Processing(true);
    var val = material.Recordmaterial;
    
    // Konfirmasi sebelum menyimpan
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
            // Validasi: Nama karyawan harus diisi
            // Dikembangkan: tambahkan validasi untuk field lain (no HP, alamat, dll)
            if (material.Recordmaterial.nama_karyawan() == "") {
                setTimeout(function() {
                    swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                }, );
            } else {
                // Proses simpan
                if (showLoaderOnConfirm = true) {
                    // Tentukan URL berdasarkan mode (Insert atau Update)
                    // Dikembangkan: bisa tambahkan endpoint untuk soft delete
                    var url = "<?php echo base_url('pabrik/KaryawanController/save') ?>";
                    if (material.Mode() === 'Update')
                        url = "<?php echo base_url('pabrik/KaryawanController/update') ?>";

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
// Menghapus data karyawan berdasarkan ID
// @param id: ID karyawan yang akan dihapus
// Dikembangkan: bisa tambahkan validasi jika karyawan memiliki relasi (user, transaksi, dll)
material.remove = function(id) {
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
            ajaxPost("<?php echo base_url('pabrik/KaryawanController/delete') ?>", {
                id: id // Pastikan ini adalah ID yang benar
            }, function(res) {
                if (res.result) {
                    // Jika berhasil dihapus
                    material.back(1);
                    swal("Deleted!", "Data has been deleted successfully.", "success");
                } else {
                    // Jika gagal dihapus karena ada relasi
                    swal("Failed!", res.message, "warning");
                }
            });
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
            // Cari akses untuk modul Karyawan (id_modul = 3)
            // Dikembangkan: bisa dibuat dinamis berdasarkan modul yang aktif
            var akses = rows.find(function(item){
                return item.id_modul == 3;
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
<!-- Layout untuk halaman manajemen karyawan -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik karyawan, atau grafik -->
<div class="content-wrapper">

    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Karyawan</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== MAIN CONTENT ===================== -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">
                    
                    <!-- ===================== NAVIGASI TAB ===================== -->
                    <!-- Tab navigasi antara Form dan List Data -->
                    <!-- Dikembangkan: bisa tambahkan tab untuk import/export data karyawan -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link" href="#tabform" data-toggle="tab" data-bind="visible: canInsert()">
                                Form
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#tablist" data-toggle="tab">
                                List
                            </a>
                        </li>
                    </ul>
                    
                    <!-- ===================== KONTEN TAB ===================== -->
                    <div class="content tab-content" id="tabnavform-content">
                        
                        <!-- ===================== TAB FORM ===================== -->
                        <!-- Form untuk tambah/edit data karyawan -->
                        <!-- Dikembangkan: bisa tambahkan input untuk foto, dokumen, atau data tambahan -->
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">
                                    
                                    <!-- ===================== TOMBOL AKSI FORM ===================== -->
                                    <!-- Button untuk save, back, dan delete -->
                                    <!-- Dikembangkan: bisa tambahkan tombol reset atau duplicate -->
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
                                                        data-bind="click:function(){remove(Recordmaterial.id_karyawan());}, visible: Mode() == 'Update' && canDelete()">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ===================== FIELD FORM ===================== -->
                                    <!-- Dikembangkan: bisa tambahkan validasi inline atau tooltip help -->
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        
                                        <!-- Field: NAMA KARYAWAN -->
                                        <!-- Dikembangkan: bisa tambahkan validasi minimal panjang karakter -->
                                        <div class="form-group">
                                            <label for="level">NAME</label>
                                            <input type="text" name="level" class="form-control" 
                                                   data-bind="value:nama_karyawan" id="idinstansi" 
                                                   placeholder="Masukkan NAME">
                                        </div>
                                        
                                        <!-- Field: JABATAN (Dropdown) -->
                                        <!-- Dikembangkan: bisa mengambil dari database untuk dinamis -->
                                        <div class="form-group">
                                            <label for="alamat">JABATAN</label>
                                            <select class="form-control" data-bind="value: jabatan">
                                                <option value="">-- Pilih Jabatan --</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Operator">Operator</option>
                                                <option value="QC">QC</option>
                                                <option value="Admin">Admin</option>
                                            </select>                                        
                                        </div>
                                        
                                        <!-- Field: NO HP -->
                                        <!-- Dikembangkan: bisa tambahkan validasi format nomor HP -->
                                        <div class="form-group">
                                            <label for="alamat">NO HP</label>
                                            <input type="text" name="no_hp" class="form-control" 
                                                   data-bind="value:no_hp" id="no_hp" 
                                                   placeholder="Masukkan NO HP">
                                        </div>
                                        
                                        <!-- Field: ALAMAT -->
                                        <!-- Dikembangkan: bisa tambahkan autocomplete alamat atau validasi -->
                                        <div class="form-group">
                                            <label for="alamat">ALAMAT</label>
                                            <textarea id="content" name="content" class="form-control" 
                                                      data-bind="value:alamat" placeholder="Masukkan ALAMAT">
                                                masukan alamat
                                            </textarea>
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
                                </div>
                            </div>
                        </div> <!-- end tabform -->
                        
                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua data karyawan -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23 ">
                                    
                                    <!-- ===================== FILTER DATA ===================== -->
                                    <!-- Panel filter untuk pencarian data -->
                                    <!-- Dikembangkan: bisa tambahkan filter berdasarkan jabatan atau status -->
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
                                                        if (event.key === 'Enter') material.filtermaterial();
                                                    }}" id="" placeholder="Filter by data" class="form-control">
                                            <p>
                                                <small class="text-muted">Contoh: ketik <i>andi rudiansyah</i> lalu <b>Enter</b></small>
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
                                    <!-- ./filter -->
                                    
                                    <!-- ===================== TABEL DATA KARYAWAN ===================== -->
                                    <!-- DataTable untuk menampilkan list karyawan -->
                                    <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NAME</th>
                                                        <th>JABATAN</th>
                                                        <th>NO HP</th>
                                                        <th>ALAMAT</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end tablist -->
                        
                    </div> <!-- end tab-content -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container-fluid -->
    </section>
</div> <!-- end content-wrapper -->

<!-- ===================== INITIALIZATION ===================== -->
<!-- Script untuk inisialisasi DataTable dan loading data awal -->
<!-- Dikembangkan: bisa ditambah error handling, atau reload otomatis -->
<script>
    $(document).ready(function() {
        model.Processing(true);
        
        // Set tab default
        model.activetab(1);
        
        // Inisialisasi: Cek hak akses user
        material.checkRole();
        
        // ===================== INISIALISASI DATATABLE =====================
        // Konfigurasi DataTable untuk server-side processing
        // Dikembangkan: bisa ditambah opsi export, responsive, atau scroll
        material.grid = $("#myTable").DataTable({ 
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/KaryawanController/getData') ?>",
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
                { "data": "id_karyawan" }, // Kolom ID
                { "data": "nama_karyawan" }, // Kolom Nama Karyawan
                { "data": "jabatan" }, // Kolom Jabatan
                { "data": "no_hp" }, // Kolom No HP
                { "data": "alamat" }, // Kolom Alamat
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
                    "data": "id_karyawan",
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

<!-- ===================== CSS UNTUK SWITCH TOGGLE ===================== -->
<!-- Dikembangkan: bisa dipindahkan ke file CSS terpisah -->
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