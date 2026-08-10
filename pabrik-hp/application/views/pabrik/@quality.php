<script>
// ===================== MODEL UTAMA =====================
// Model untuk data Quality Control (QC) / pemeriksaan kualitas
// Dikembangkan: bisa tambahkan field seperti foto_bukti, metode_qc, standar_kualitas, dll.
model.masterModel = {
    id_qc: 0,
    id_produksi: "",
    id_karyawan: "",
    hasil_qc: "",
    catatan: "",
    tanggal_qc: "",
}

// ===================== OBJEK MATERIAL (MANAJEMEN QUALITY CONTROL) =====================
// Objek utama untuk mengelola data Quality Control
// Dikembangkan: bisa tambahkan properti untuk filter berdasarkan hasil QC atau tanggal
var material = {
    title: "Data Quality Control",
    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    DataFilter: ko.observableArray(['id_produksi']), // Opsi filter yang tersedia
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('id_produksi'), // Value filter yang dipilih

    // ===================== DROPDOWN DATA =====================
    // Data untuk dropdown produksi dan karyawan
    // Dikembangkan: bisa tambahkan filter untuk produksi yang belum di-QC
    SELECTPRODUKSI: ko.observableArray([]),
    SELECTKARYAWAN: ko.observableArray([]),

    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan hasil QC (Lulus/Tidak Lulus)
    SELECTFILTERVALUE:  [
        { name: 'id_produksi', value: 'id_produksi'},
        { name: 'id_karyawan', value: 'id_karyawan'},
        { name: 'hasil_qc', value: 'hasil_qc'},
        { name: 'catatan', value: 'catatan'},
        { name: 'tanggal_qc', value: 'tanggal_qc'},
    ],

    // ===================== HAK AKSES =====================
    // Properti untuk mengontrol button berdasarkan role user login
    // Dikembangkan: bisa ditambah hak akses untuk approve hasil QC
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
// Mengambil data QC berdasarkan ID untuk diedit
// @param id: ID QC yang akan diedit
// Dikembangkan: bisa tambahkan loading animasi atau disabled form saat proses
material.selectdata = function(id) {
    model.Processing(true);
    ajaxPost("<?php echo site_url('pabrik/QualityController/getDataSelect') ?>", {
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
// Dikembangkan: bisa tambahkan validasi hasil QC harus dipilih (Lulus/Tidak Lulus)
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
            // Validasi: ID Produksi harus diisi
            // Dikembangkan: tambahkan validasi untuk field lain (hasil QC, catatan, dll.)
            if (material.Recordmaterial.id_produksi() == "") {
                setTimeout(function() {
                    swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                }, );
            } else {
                // Proses simpan
                if (showLoaderOnConfirm = true) {
                    // Tentukan URL berdasarkan mode (Insert atau Update)
                    // Dikembangkan: bisa tambahkan endpoint untuk bulk insert
                    var url = "<?php echo base_url('pabrik/QualityController/save') ?>";
                    if (material.Mode() === 'Update')
                        url = "<?php echo base_url('pabrik/QualityController/update') ?>";

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
// Menghapus data QC berdasarkan ID
// @param id: ID QC yang akan dihapus
// Dikembangkan: bisa tambahkan validasi jika QC sudah digunakan untuk keputusan produksi
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
            ajaxPost("<?php echo base_url('pabrik/QualityController/delete') ?>", {
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

// ===================== FUNGSI LOAD PRODUKSI =====================
// Mengambil data produksi dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter produksi yang sudah selesai
material.loadProduksi = function () {
    $.ajax({
        url:  "<?php echo site_url('pabrik/QualityController/getProduksi') ?>",
        type: "GET",
        dataType: "json",
        success: function (res) {
            console.log(res); // Debug: cek data produksi
            material.SELECTPRODUKSI(res); // Isi dropdown produksi
        },
        error: function (err) {
            console.log("Gagal load produksi", err);
        }
    });
};

// ===================== FUNGSI LOAD KARYAWAN =====================
// Mengambil data karyawan dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter karyawan QC
material.loadKaryawan = function () {
    $.ajax({
        url:  "<?php echo site_url('pabrik/QualityController/getKaryawan') ?>",
        type: "GET",
        dataType: "json",
        success: function (res) {
            console.log(res); // Debug: cek data karyawan
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
            // Cari akses untuk modul Quality Control (id_modul = 9)
            // Dikembangkan: bisa dibuat dinamis berdasarkan modul yang aktif
            var akses = rows.find(function(item){
                return item.id_modul == 9;
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
<!-- Layout untuk halaman manajemen Quality Control -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik QC, atau grafik -->
<div class="content-wrapper">

    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Quality Control</h1>
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
                    <!-- Dikembangkan: bisa tambahkan tab untuk dashboard QC atau grafik -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link" href="#tabform" data-toggle="tab" data-bind="visible: canInsert">
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
                        <!-- Form untuk tambah/edit data QC -->
                        <!-- Dikembangkan: bisa tambahkan input untuk upload bukti atau foto -->
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
                                                        data-bind="click:function(){remove(Recordmaterial.id_qc());}, visible: Mode() == 'Update' && canDelete()" 
                                                        data-toggle="tooltip" data-placement="top" data-original-title="hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ===================== FIELD FORM ===================== -->
                                    <!-- Dikembangkan: bisa tambahkan validasi inline atau tooltip help -->
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        
                                        <!-- Field: PRODUKSI (Dropdown) -->
                                        <!-- Dikembangkan: bisa tambahkan autocomplete atau search di dropdown -->
                                        <div class="form-group">
                                            <label for="level">id_produksi</label>
                                            <fieldset class="form-group">
                                                <select data-bind="
                                                    options: material.SELECTPRODUKSI,
                                                    optionsText: 'name',
                                                    optionsValue: 'value',
                                                    value:id_produksi"
                                                    class="form-control" id="basicSelect">
                                                    <option value="">-- Pilih Produksi --</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        
                                        <!-- Field: KARYAWAN (Dropdown) -->
                                        <!-- Dikembangkan: bisa tambahkan filter karyawan QC -->
                                        <div class="form-group">
                                            <label for="alamat">id_karyawan</label>
                                            <fieldset class="form-group">
                                                <select data-bind="
                                                    options: material.SELECTKARYAWAN,
                                                    optionsText: 'name',
                                                    optionsValue: 'value',
                                                    value:id_karyawan"
                                                    class="form-control" id="basicSelect">
                                                    <option value="">-- Pilih Karyawan --</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        
                                        <!-- Field: HASIL QC -->
                                        <!-- Dikembangkan: bisa diubah menjadi dropdown atau radio button -->
                                        <div class="form-group">
                                            <label for="hasil_qc">hasil_qc</label>
                                            <textarea id="hasil_qc" name="hasil_qc" class="form-control" 
                                                      data-bind="value:hasil_qc" 
                                                      placeholder="Masukkan HASIL QC (Lulus/Tidak Lulus/Perlu Perbaikan)">
                                                I am a comment
                                            </textarea>
                                        </div>
                                        
                                        <!-- Field: CATATAN -->
                                        <!-- Dikembangkan: bisa tambahkan rich text editor -->
                                        <div class="form-group">
                                            <label for="catatan">catatan</label>
                                            <textarea id="catatan" name="catatan" class="form-control" 
                                                      data-bind="value:catatan" 
                                                      placeholder="Masukkan CATATAN">
                                                I am a comment
                                            </textarea>
                                        </div>
                                        
                                        <!-- Field: TANGGAL QC -->
                                        <!-- Dikembangkan: bisa default dengan tanggal hari ini -->
                                        <div class="form-group">
                                            <label for="tanggal_qc">tanggal_qc</label>
                                            <input type="date" name="tanggal_qc" class="form-control" 
                                                   data-bind="value:tanggal_qc" id="tanggal_qc" 
                                                   placeholder="Masukkan TANGGAL QC">
                                        </div>
                                    </div> <!-- end card-body Recordmaterial -->
                                </div>
                            </div>
                        </div> <!-- end tabform -->
                        
                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua data QC -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23 ">
                                    
                                    <!-- ===================== FILTER DATA ===================== -->
                                    <!-- Panel filter untuk pencarian data -->
                                    <!-- Dikembangkan: bisa tambahkan filter berdasarkan hasil QC (Lulus/Tidak Lulus) -->
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
                                    
                                    <!-- ===================== TABEL DATA QC ===================== -->
                                    <!-- DataTable untuk menampilkan list QC -->
                                    <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                                <thead>
                                                    <tr>
                                                        <th>id_qc</th>
                                                        <th>id_produksi</th>
                                                        <th>id_karyawan</th>
                                                        <th>hasil_qc</th>
                                                        <th>catatan</th>
                                                        <th>tanggal_qc</th>
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
        
        // Inisialisasi: Load data untuk dropdown
        // Dikembangkan: bisa ditambah caching untuk optimasi performance
        material.loadProduksi(); // Load data produksi
        material.loadKaryawan(); // Load data karyawan
        
        // Inisialisasi: Cek hak akses user
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
                "url": "<?php echo base_url('pabrik/QualityController/getData') ?>",
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
                { "data": "id_qc" }, // Kolom ID QC
                { "data": "id_produksi" }, // Kolom ID Produksi (dari relasi)
                { "data": "nama_karyawan" }, // Kolom Nama Karyawan (dari relasi)
                {
                    "data": "hasil_qc",
                    "render": function(data, type, row) {
                        // Render hasil QC dengan badge warna
                        if (data === "Tidak Lulus") {
                            return '<span class="badge badge-danger">Tidak Lulus</span>';
                        } else if (data === "Perlu Perbaikan") {
                            return '<span class="badge badge-warning">Perlu Perbaikan</span>';
                        } else if (data === "Lulus") {
                            return '<span class="badge badge-success">Lulus</span>';
                        } else {
                            return data;
                        }
                    }
                },
                { "data": "catatan" }, // Kolom Catatan
                { "data": "tanggal_qc" }, // Kolom Tanggal QC
                {
                    "data": "id_qc",
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