<script>
// ===================== MODEL UTAMA =====================
// Model untuk data detail produksi
// Dikembangkan: bisa tambahkan field seperti satuan, harga_satuan, total_harga, dll.
model.masterModel = {
    id_detail: 0, 
    id_produksi: "",
    id_komponen: "",
    jumlah: "",
}

// ===================== OBJEK MATERIAL (DETAIL PRODUKSI) =====================
// Objek utama untuk mengelola detail produksi
// Dikembangkan: bisa tambahkan properti untuk subtotal, status, atau catatan
var material = {
    TITLE: "Data Detail",

    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('id_produksi'), // Value filter yang dipilih

    // ===================== DROPDOWN DATA =====================
    // Data untuk dropdown produksi dan komponen
    // Dikembangkan: bisa tambahkan filter untuk produksi yang sudah selesai
    SELECTPRODUKSI: ko.observableArray([]),
    SELECTKOMPONEN: ko.observableArray([]),

    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan jumlah atau tanggal
    SELECTFILTERVALUE: [
        { name: 'ID Produksi', value: 'id_produksi' },
        { name: 'Nama Komponen', value: 'nama_komponen' },
    ],

    // ===================== HAK AKSES =====================
    // Properti untuk mengontrol button berdasarkan role user login
    // Dikembangkan: bisa ditambah hak akses untuk export atau print
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

// ===================== FUNGSI TAMBAH DATA =====================
// Menyiapkan form untuk menambah data baru
// Dikembangkan: bisa tambahkan default value untuk field tertentu
material.tambah = function() {
    ko.mapping.fromJS(model.masterModel, material.Recordmaterial); // Reset form
    material.Mode(''); // Set mode tambah
    model.activetab(0); // Pindah ke tab form
}

// ===================== FUNGSI SELECT DATA =====================
// Mengambil data detail berdasarkan ID untuk diedit
// @param id: ID detail yang akan diedit
// Dikembangkan: bisa tambahkan loading animasi atau disabled form saat proses
material.selectdata = function(id) {
    model.Processing(true);
    ajaxPost("<?php echo site_url('pabrik/DetailController/getDataSelect') ?>", {
        id: id
    }, function(res) {
        material.back(0); // Pindah ke tab form
        ko.mapping.fromJS(res[0], material.Recordmaterial); // Isi form dengan data
        material.Mode("Update"); // Ubah mode menjadi Update
        model.Processing(false);
    });
}

// ===================== FUNGSI SAVE =====================
// Menyimpan data baru atau update data existing
// Dikembangkan: bisa tambahkan validasi stok komponen atau validasi duplikasi
material.save = function() {
    // Validasi: ID Produksi dan ID Komponen harus diisi
    // Dikembangkan: tambahkan validasi jumlah harus angka dan lebih dari 0
    if (material.Recordmaterial.id_produksi() == "" || material.Recordmaterial.id_komponen() == "") {
        swal("Peringatan!", "ID Produksi dan ID Komponen harap diisi dengan benar!", "warning");
        return;
    }

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
            model.Processing(true);

            // Tentukan URL berdasarkan mode (Insert atau Update)
            // Dikembangkan: bisa tambahkan endpoint untuk bulk insert
            var url = "<?php echo base_url('pabrik/DetailController/save') ?>";
            if (material.Mode() === 'Update')
                url = "<?php echo base_url('pabrik/DetailController/update') ?>";

            // Kirim data ke server
            ajaxPost(url, material.Recordmaterial, function(res) {
                model.Processing(false);
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
                } else {
                    // Notifikasi gagal
                    swal("Gagal!", "Data gagal disimpan.", "error");
                }
            });
        }
    });
}

// ===================== FUNGSI DELETE =====================
// Menghapus data detail berdasarkan ID
// @param id: ID detail yang akan dihapus
// Dikembangkan: bisa tambahkan validasi relasi atau soft delete
material.remove = function(id) {
    swal({
        title: "Are you sure?",
        text: "Delete this data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes!",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
    }, function(isConfirm) {
        if (isConfirm) {
            model.Processing(true);
            ajaxPost("<?php echo base_url('pabrik/DetailController/delete') ?>", {
                id: id
            }, function(res) {
                model.Processing(false);
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
}

// ===================== FUNGSI CHECK ROLE =====================
// Mengecek hak akses user berdasarkan role
// Dikembangkan: bisa ditambah cache atau multiple modul
material.checkRole = function(){
    ajaxPost("<?php echo site_url('pabrik/AksesController/getAkses')?>",
        {
            role : material.role() // Role dari session
        },
        function(rows){
            // Cari akses untuk modul Detail Produksi (id_modul = 8)
            // Dikembangkan: bisa dibuat dinamis berdasarkan modul yang aktif
            var akses = rows.find(function(item){
                return item.id_modul == 8;
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

// ===================== FUNGSI LOAD PRODUKSI =====================
// Mengambil data produksi dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter produksi aktif
material.loadProduksi = function() {
    $.ajax({
        url: "<?php echo base_url('pabrik/DetailController/getProduksi') ?>",
        type: "GET",
        dataType: "json",
        success: function(res) {
            material.SELECTPRODUKSI(res); // Isi dropdown produksi
        },
        error: function(xhr, status, error) {
            console.error("Error loading produksi data:", error);
        }
    });
};

// ===================== FUNGSI LOAD KOMPONEN =====================
// Mengambil data komponen dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter komponen berdasarkan produksi
material.loadKomponen = function() {
    $.ajax({
        url: "<?php echo base_url('pabrik/DetailController/getKomponen') ?>",
        type: "GET",
        dataType: "json",
        success: function(res) {
            material.SELECTKOMPONEN(res); // Isi dropdown komponen
        },
        error: function(xhr, status, error) {
            console.error("Error loading komponen data:", error);
        }
    });
};
</script>

<!-- ===================== TAMPILAN HTML ===================== -->
<!-- Layout untuk halaman manajemen detail produksi -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik, atau grafik -->
<div class="content-wrapper">

    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Detail Produksi</h1>
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
                    <!-- Dikembangkan: bisa tambahkan tab untuk import/export data -->
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
                        <!-- Form untuk tambah/edit detail produksi -->
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
                                                        data-bind="click:function(){remove(Recordmaterial.id_detail());}, visible: Mode() == 'Update' && canDelete()">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ===================== FIELD FORM ===================== -->
                                    <!-- Dikembangkan: bisa tambahkan validasi inline atau tooltip help -->
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        
                                        <!-- Field: ID PRODUKSI (Dropdown) -->
                                        <!-- Dikembangkan: bisa tambahkan autocomplete atau search di dropdown -->
                                        <div class="form-group">
                                            <label for="id_produksi">ID PRODUKSI</label>
                                            <select data-bind="
                                                options: $parent.SELECTPRODUKSI,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value: id_produksi"
                                                class="form-control" id="id_produksi">
                                                <option value="">-- Pilih Produksi --</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Field: ID KOMPONEN (Dropdown) -->
                                        <!-- Dikembangkan: bisa tambahkan filter komponen berdasarkan produksi -->
                                        <div class="form-group">
                                            <label for="id_komponen">ID KOMPONEN</label>
                                            <select data-bind="
                                                options: $parent.SELECTKOMPONEN,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value: id_komponen"
                                                class="form-control" id="id_komponen">
                                                <option value="">-- Pilih Komponen --</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Field: JUMLAH -->
                                        <!-- Dikembangkan: bisa tambahkan validasi angka minimal 1 -->
                                        <div class="form-group">
                                            <label for="inputjumlah">JUMLAH</label>
                                            <input type="text" id="inputjumlah" name="jumlah"
                                                class="form-control"
                                                data-bind="value: jumlah"
                                                placeholder="Masukkan Jumlah">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end tabform -->
                        
                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua data detail produksi -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23 ">
                                    
                                    <!-- ===================== FILTER DATA ===================== -->
                                    <!-- Panel filter untuk pencarian data -->
                                    <!-- Dikembangkan: bisa tambahkan filter berdasarkan produksi atau komponen -->
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
                                                <small class="text-muted">Contoh: ketik <i>produksi1</i> lalu <b>Enter</b></small>
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
                                    
                                    <!-- ===================== TABEL DATA DETAIL ===================== -->
                                    <!-- DataTable untuk menampilkan list detail produksi -->
                                    <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                                <thead>
                                                    <tr>
                                                        <th>ID Detail</th>
                                                        <th>ID Produksi</th>
                                                        <th>Nama Komponen</th>
                                                        <th>Jumlah</th>
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
        material.loadKomponen(); // Load data komponen
        
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
                "url": "<?php echo base_url('pabrik/DetailController/getData') ?>",
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
                { "data": "id_detail" }, // Kolom ID Detail
                { "data": "id_produksi" }, // Kolom ID Produksi
                { "data": "nama_komponen" }, // Kolom Nama Komponen
                { "data": "jumlah" }, // Kolom Jumlah
                {
                    "data": "id_detail",
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