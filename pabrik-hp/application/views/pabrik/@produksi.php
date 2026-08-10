<script>
// ===================== MODEL UTAMA =====================
// Model untuk data produksi (proses pembuatan produk)
// Dikembangkan: bisa tambahkan field seperti shift, mesin, biaya_produksi, catatan, dll.
model.masterModel = {
    id_produksi: 0,
    id_produk: "",
    id_karyawan: "",
    tanggal_produksi: "",
    target: "",
    jumlah_selesai: "",
    status: "",
}

// ===================== OBJEK MATERIAL (MANAJEMEN PRODUKSI) =====================
// Objek utama untuk mengelola data produksi
// Dikembangkan: bisa tambahkan properti untuk filter berdasarkan tanggal atau status
var material = {
    TITLE: "Data Produksi",

    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('id_produksi'), // Value filter yang dipilih

    // ===================== DROPDOWN DATA =====================
    // Data untuk dropdown produk dan karyawan
    // Dikembangkan: bisa tambahkan filter untuk produk yang aktif atau karyawan yang tersedia
    SELECTPRODUK: ko.observableArray([]),
    SELECTKARYAWAN: ko.observableArray([]),

    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan range tanggal atau target
    SELECTFILTERVALUE: [
        { name: 'produk', value: 'p.id_produk' },
        { name: 'karyawan', value: 'p.id_karyawan' },
        { name: 'tanggal produksi', value: 'p.tanggal_produksi' },
        { name: 'target', value: 'p.target' },
        { name: 'jumlah selesai', value: 'p.jumlah_selesai' },
        { name: 'status', value: 'p.status' },
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
// Dikembangkan: bisa tambahkan konfirmasi jika ada perubahan yang belum disimpan
material.back = function() {
    material.Mode('');
    material.grid.ajax.reload(null, false); // Reload DataTable
    ko.mapping.fromJS(model.masterModel, material.Recordmaterial); // Reset form ke default
    $('#tabnavform a[href="#tablist"]').tab('show'); // Pindah ke tab list
}

// ===================== FUNGSI TAMBAH DATA =====================
// Menyiapkan form untuk menambah data baru
// Dikembangkan: bisa tambahkan default value seperti tanggal hari ini
material.tambah = function() {

    // Reset form
    ko.mapping.fromJS(model.masterModel, material.Recordmaterial);

    // ==============================
    // SET USER YANG SEDANG LOGIN
    // ==============================
    material.Recordmaterial.id_karyawan(
        "<?= $this->session->userdata('id_karyawan'); ?>"
    );

    // ==============================
    // SET TANGGAL LOGIN
    // ==============================
    material.Recordmaterial.tanggal_produksi(
        "<?= date('Y-m-d', strtotime($this->session->userdata('login_at'))); ?>"
    );

    // Default jumlah selesai
    material.Recordmaterial.jumlah_selesai(0);

    // Default status
    material.Recordmaterial.status('Perakitan');

    material.Mode('');

    $('#tabnavform a[href="#tabform"]').tab('show');
}
// ===================== FUNGSI SELECT DATA =====================
// Mengambil data produksi berdasarkan ID untuk diedit
// @param id: ID produksi yang akan diedit
// Dikembangkan: bisa tambahkan loading animasi atau disabled form saat proses
material.selectdata = function(id) {
    model.Processing(true);
    ajaxPost("<?php echo site_url('pabrik/ProduksiController/getDataSelect') ?>", {
        id: id
    }, function(res) {
        ko.mapping.fromJS(res[0], material.Recordmaterial); // Isi form dengan data
        material.Mode("Update"); // Ubah mode menjadi Update
        $('#tabnavform a[href="#tabform"]').tab('show'); // Pindah ke tab form
        model.Processing(false);
    });
}

// ===================== FUNGSI SAVE =====================
// Menyimpan data baru atau update data existing
// Dikembangkan: bisa tambahkan validasi target dan jumlah selesai harus angka
material.save = function() {

    if (material.Recordmaterial.id_produk() == "") {
        swal(
            "Peringatan!",
            "Produk harus dipilih!",
            "warning"
        );
        return;
    }

    if (material.Recordmaterial.target() == "") {
        swal(
            "Peringatan!",
            "Target produksi harus diisi!",
            "warning"
        );
        return;
    }

    swal({
        title: "Perhatian",
        text: "Anda akan simpan data produksi ini?",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,

    }, function(isConfirm) {

        if (isConfirm) {

            model.Processing(true);

            var url =
                "<?php echo base_url('pabrik/ProduksiController/save') ?>";

            if (material.Mode() === 'Update') {
                url =
                    "<?php echo base_url('pabrik/ProduksiController/update') ?>";
            }

            ajaxPost(
                url,
                material.Recordmaterial,
                function(res) {

                    model.Processing(false);

                    if (res.result == true || material.Mode() == "Update") {

                        var pesan =
                            material.Mode() == "Update"
                            ? "Data Berhasil diubah!"
                            : "Data Berhasil diinput!";

                        swal(
                            "Berhasil!",
                            pesan,
                            "success"
                        );

                        material.back();

                    } else {

                        swal(
                            "Gagal!",
                            "Data gagal disimpan.",
                            "error"
                        );
                    }
                }
            );
        }
    });
}
// ===================== FUNGSI DELETE =====================
// Menghapus data produksi berdasarkan ID
// @param id: ID produksi yang akan dihapus
// Dikembangkan: bisa tambahkan validasi jika produksi sudah memiliki detail
material.remove = function(id) {
    swal({
        title: "Are you sure?",
        text: "Delete this data?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
    }, function(isConfirm) {
        if (isConfirm) {
            model.Processing(true);
            ajaxPost("<?php echo base_url('pabrik/ProduksiController/delete') ?>", {
                id: id
            }, function(res) {
                model.Processing(false);
                if (res.result) {
                    // Jika berhasil dihapus
                    material.grid.ajax.reload(null, false); // Reload DataTable
                    swal("Deleted!", "Data has been deleted successfully.", "success");
                } else {
                    // Jika gagal dihapus karena ada relasi
                    swal("Failed!", res.message, "warning");
                }
            });
        }
    });
};

// ===================== FUNGSI LOAD PRODUK =====================
// Mengambil data produk dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter produk aktif
material.loadProduk = function() {
    $.ajax({
        url: "<?php echo base_url('pabrik/ProduksiController/getProduk') ?>",
        type: "GET",
        dataType: "json",
        success: function(res) {
            material.SELECTPRODUK(res); // Isi dropdown produk
        },
        error: function(xhr, status, error) {
            console.error("Error loading produk data:", error);
        }
    });
};

// ===================== FUNGSI LOAD KARYAWAN =====================
// Mengambil data karyawan dari server untuk dropdown
// Dikembangkan: bisa tambahkan caching atau filter karyawan berdasarkan role
material.loadKaryawan = function() {
    $.ajax({
        url: "<?php echo base_url('pabrik/ProduksiController/getKaryawan') ?>",
        type: "GET",
        dataType: "json",
        success: function(res) {
            material.SELECTKARYAWAN(res); // Isi dropdown karyawan
        },
        error: function(xhr, status, error) {
            console.error("Error loading karyawan data:", error);
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
            // Cari akses untuk modul Produksi (id_modul = 7)
            // Dikembangkan: bisa dibuat dinamis berdasarkan modul yang aktif
            var akses = rows.find(function(item){
                return item.id_modul == 7;
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
<!-- Layout untuk halaman manajemen produksi -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik produksi, atau grafik -->
<div class="content-wrapper">
    
    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>data produksi</h1>
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
                    <!-- Dikembangkan: bisa tambahkan tab untuk monitoring atau dashboard -->
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
                    <div class="tab-content" id="tabnavform-content">

                        <!-- ===================== TAB FORM ===================== -->
                        <!-- Form untuk tambah/edit data produksi -->
                        <!-- Dikembangkan: bisa tambahkan input untuk shift, mesin, atau biaya -->
                        <div class="tab-pane fade" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn">
                                    
                                    <!-- ===================== TOMBOL AKSI FORM ===================== -->
                                    <!-- Button untuk save, back, dan delete -->
                                    <!-- Dikembangkan: bisa tambahkan tombol reset atau duplicate -->
                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group">
                                                <!-- Tombol Kembali (hanya saat mode Update) -->
                                                <button class="btn btn-sm btn-warning"
                                                    data-bind="click: function(){ back(1); }, visible: Mode() == 'Update'"
                                                    data-toggle="tooltip" title="Kembali">
                                                    <i class="fa fa-arrow-left"></i>
                                                </button>
                                                
                                                <!-- Tombol Simpan -->
                                                <button class="btn btn-sm btn-info"
                                                    data-bind="click: save"
                                                    data-toggle="tooltip" title="Simpan">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                                
                                                <!-- Tombol Hapus (hanya saat mode Update) -->
                                                <button class="btn btn-sm btn-danger"
                                                    data-bind="click: function(){ remove(Recordmaterial.id_produksi()); }, visible: Mode() == 'Update'">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ===================== FIELD FORM ===================== -->
                                    <!-- Dikembangkan: bisa tambahkan validasi inline atau tooltip help -->
                                    
                                    <!-- Field: PRODUK (Dropdown) -->
                                    <!-- Dikembangkan: bisa tambahkan autocomplete atau search di dropdown -->
                                    <div class="form-group" data-bind="with: Recordmaterial">
                                        <label for="selectproduk">id_produk</label>
                                        <fieldset class="form-group">
                                            <select data-bind="
                                                options: material.SELECTPRODUK,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value: id_produk"
                                                class="form-control" id="selectproduk">
                                                <option value="">-- Pilih Produk --</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    
                                    <!-- Field: KARYAWAN (Dropdown) -->
                                    <!-- Dikembangkan: bisa tambahkan filter karyawan berdasarkan divisi -->
                                    <!-- ===================== KARYAWAN OTOMATIS ===================== -->
                                    <div class="form-group">
                                        <label for="inputkaryawan">Karyawan</label>

                                        <input type="text"
                                            id="inputkaryawan"
                                            class="form-control"
                                            value="<?= $this->session->userdata('nama_karyawan'); ?> (<?= $this->session->userdata('jabatan'); ?>)"
                                            readonly>

                                        <!-- ID karyawan disimpan di Knockout -->
                                        <input type="hidden"
                                            data-bind="value: id_karyawan">
                                    </div>
                                    
                                    <!-- Field: TANGGAL PRODUKSI -->
                                    <!-- Dikembangkan: bisa default dengan tanggal hari ini -->
                                    <!-- ===================== TANGGAL PRODUKSI OTOMATIS ===================== -->
                                    <div class="form-group">
                                        <label for="inputtanggal">Tanggal Produksi</label>

                                        <input type="date"
                                            id="inputtanggal"
                                            class="form-control"
                                            value="<?= date('Y-m-d', strtotime($this->session->userdata('login_at'))); ?>"
                                            disabled>

                                        <!-- Nilai tanggal untuk Knockout -->
                                        <input type="hidden"
                                            data-bind="value: tanggal_produksi">
                                    </div>
                                    
                                    <!-- Field: TARGET -->
                                    <!-- Dikembangkan: bisa tambahkan validasi angka minimal 1 -->
                                    <div class="form-group" data-bind="with: Recordmaterial">
                                        <label for="inputtarget">target</label>
                                        <input type="text" id="inputtarget" name="target"
                                            class="form-control"
                                            data-bind="value: target"
                                            placeholder="Masukkan Target">
                                    </div>
                                    
                                    <!-- Field: JUMLAH SELESAI -->
                                    <!-- Dikembangkan: bisa tambahkan validasi tidak lebih dari target -->
                                    <div class="form-group" data-bind="with: Recordmaterial">
                                        <label for="inputjumlahselesai">jumlah_selesai</label>
                                        <input type="text" id="inputjumlahselesai" name="jumlah_selesai"
                                            class="form-control"
                                            data-bind="value: jumlah_selesai"
                                            placeholder="Masukkan Jumlah Selesai">
                                    </div>

                                    <!-- Field: STATUS (Radio Button) -->
                                    <!-- Dikembangkan: bisa diubah menjadi dropdown atau badge -->
                                    <div class="form-group" data-bind="with: Recordmaterial">
                                        <label>STATUS</label>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="Perakitan" name="customRadio" checked="">
                                            <label for="Perakitan" class="custom-control-label">Perakitan</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="QC" name="customRadio" disabled="">
                                            <label for="QC" class="custom-control-label">QC</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="Selesai" name="customRadio" disabled="">
                                            <label for="Selesai" class="custom-control-label">Selesai</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- ===================== END TAB FORM ===================== -->

                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua data produksi -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane active" id="tablist">
                            <div class="card mt-3">
                                <div class="card-header">
                                    
                                    <!-- ===================== FILTER DATA ===================== -->
                                    <!-- Panel filter untuk pencarian data -->
                                    <!-- Dikembangkan: bisa tambahkan filter berdasarkan range tanggal atau status -->
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <select class="form-control form-control-sm" data-bind="
                                                options: SELECTFILTERVALUE,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value: FilterValue">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-sm" placeholder="Cari..."
                                                data-bind="value: FilterText, event: { keyup: function(data, event) {
                                                    if (event.key === 'Enter') material.filtermaterial();
                                                }}">
                                        </div>
                                        <div class="col-sm-2 col-md-5 margFilter">
                                            <div class="form-group">
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
                                    </div>
                                </div>
                                <div class="card-body">
                                    
                                    <!-- ===================== TABEL DATA PRODUKSI ===================== -->
                                    <!-- DataTable untuk menampilkan list produksi -->
                                    <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                    <table id="myTable" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>id_produksi</th>
                                                <th>id_produk</th>
                                                <th>id_karyawan</th>
                                                <th>tanggal_produksi</th>
                                                <th>target</th>
                                                <th>jumlah_selesai</th>
                                                <th>status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ===================== END TAB LIST ===================== -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ===================== INITIALIZATION ===================== -->
<!-- Script untuk inisialisasi DataTable dan loading data awal -->
<!-- Dikembangkan: bisa ditambah error handling, atau reload otomatis -->
<script>
    $(document).ready(function() {
        model.Processing(true);
        
        // Inisialisasi: Load data untuk dropdown
        // Dikembangkan: bisa ditambah caching untuk optimasi performance
        material.loadProduk(); // Load data produk
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
                "url": "<?php echo base_url('pabrik/ProduksiController/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    // Kirim data filter ke server
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext']  = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    // Mapping response dari server ke DataTable format
                    json.recordsTotal    = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    return json.Data ? json.Data : [];
                },
            },
            "searching": false, // Nonaktifkan search bawaan DataTable
            "columns": [
                { "data": "id_produksi" }, // Kolom ID Produksi
                { "data": "nama_produk" }, // Kolom Nama Produk (dari relasi)
                { "data": "nama_karyawan" }, // Kolom Nama Karyawan (dari relasi)
                { "data": "tanggal_produksi" }, // Kolom Tanggal Produksi
                { "data": "target" }, // Kolom Target
                { "data": "jumlah_selesai" }, // Kolom Jumlah Selesai
                { 
                    "data": "status",
                    "render": function(data, type, row) {
                        // Render status dengan badge warna
                        if (data === "Perakitan") {
                            return '<span class="badge badge-primary">Perakitan</span>';
                        } else if (data === "QC") {
                            return '<span class="badge badge-warning">QC</span>';
                        } else if (data === "Selesai") {
                            return '<span class="badge badge-success">Selesai</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    "data": "id_produksi",
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