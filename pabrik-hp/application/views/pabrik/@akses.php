<script>
// ===================== MODEL UTAMA =====================
// Model untuk data akses/role
// Dikembangkan: bisa tambahkan properti seperti deskripsi role, created_by, created_date, dll.
model.masterModel = {
    role : ""
}

// ===================== OBJEK MATERIAL (MANAJEMEN AKSES) =====================
// Objek utama untuk mengelola hak akses berdasarkan role
// Dikembangkan: bisa tambahkan properti untuk logging aktivitas atau history perubahan
var material = {
    title: "Data Satuan",
    Recordmaterial: ko.mapping.fromJS(model.masterModel), // Model untuk form input
    Listmaterial: ko.observableArray([]), // Untuk menyimpan list data (belum digunakan)
    Mode: ko.observable(''), // Mode: '' = tambah, 'Update' = edit
    DataFilter: ko.observableArray(['role']), // Opsi filter yang tersedia
    FilterText: ko.observable(''), // Teks pencarian
    FilterValue: ko.observable('role'), // Value filter yang dipilih
    
    // ===================== LIST AKSES =====================
    // Menyimpan data akses per modul untuk role tertentu
    // Dikembangkan: bisa ditambah fitur untuk copy akses dari role lain
    ListAkses: ko.observableArray([]),
    
    // ===================== OPSI FILTER =====================
    // Daftar kolom yang bisa difilter
    // Dikembangkan: bisa tambahkan filter berdasarkan modul atau jumlah user
    SELECTFILTERVALUE:  [
        { name: 'ROLE', value: 'role'},
    ],
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
    ko.mapping.fromJS(model.masterModel, material.Recordmaterial); // Reset form
    model.activetab(tab); // Pindah ke tab yang ditentukan
}

// ===================== FUNGSI SELECT DATA =====================
// Mengambil data akses berdasarkan role untuk diedit
// @param role: role yang akan diedit aksesnya
// Dikembangkan: bisa tambahkan loading animasi atau preview akses
material.selectdata = function(role) {
    model.Processing(true);
    // Ambil data role dari server
    // Dikembangkan: bisa tambahkan cache untuk mempercepat loading
    ajaxPost("<?php echo site_url('pabrik/AksesController/getDataSelect') ?>", {
        role: role
    }, function(res) {
        console.log(res[0]); // Debug: cek data yang diterima
        material.back(0); // Pindah ke tab form
        ko.mapping.fromJS(res[0], material.Recordmaterial); // Isi form dengan data
        material.loadAkses(material.Recordmaterial.role()); // Load akses untuk role tersebut
        material.Mode("Update"); // Ubah mode menjadi Update
        model.Processing(false);
    });
}

// ===================== FUNGSI SAVE =====================
// Menyimpan data akses untuk role tertentu
// Dikembangkan: bisa tambahkan validasi agar tidak ada role yang kehilangan akses
material.save = function() {
    model.Processing(true);
    
    // Siapkan data yang akan disimpan (role + daftar akses)
    // Dikembangkan: bisa tambahkan data tambahan seperti alasan perubahan
    var val = {
        role : material.Recordmaterial.role(),
        akses : ko.toJS(material.ListAkses) // Konversi observable ke JS object
    };
    
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
            // Validasi role tidak boleh kosong
            if (material.Recordmaterial.role() == "") {
                setTimeout(function() {
                    swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                }, );
            } else {
                // Proses simpan
                if (showLoaderOnConfirm = true) {
                    // URL untuk update (karena ini hanya update akses)
                    // Dikembangkan: bisa ditambah endpoint untuk insert role baru
                    var url = "<?php echo base_url('pabrik/AksesController/update') ?>";
                    
                    // Kirim data ke server
                    ajaxPost(url, val, function(res) {
                        console.log(res.result);
                        if (res.result == true || material.Mode() == "Update") {
                            // Notifikasi sukses
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
                            material.back(1); // Kembali ke list
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
// Menghapus role dan semua aksesnya
// @param role: role yang akan dihapus
// Dikembangkan: bisa tambahkan validasi jika role masih digunakan oleh user
material.remove = function(role) {
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
            ajaxPost("<?php echo base_url('pabrik/AksesController/delete') ?>", {
                role: role
            }, function(res) {
                if (res.result) {
                    // Jika berhasil dihapus
                    material.back(1);
                    swal("Deleted!", "Data has been deleted successfully.", "success");
                } else {
                    // Jika gagal dihapus karena ada relasi atau error lain
                    swal("Failed!", res.message, "warning");
                }
            });
        }
    });
}

// ===================== FUNGSI LOAD AKSES =====================
// Mengambil data akses untuk role tertentu dan menampilkannya di tabel
// @param role: role yang akan dimuat aksesnya
// Dikembangkan: bisa tambahkan fitur untuk copy akses dari role lain
material.loadAkses = function(role){
    // Ambil data akses dari server
    // Dikembangkan: bisa tambahkan caching untuk mempercepat loading
    ajaxPost(
        "<?php echo site_url('pabrik/AksesController/getAkses')?>",
        {
            role : role
        },
        function(res){
            // Reset ListAkses
            material.ListAkses([]);
            
            // Loop data dan push ke ListAkses dengan observable
            // Dikembangkan: bisa tambahkan sorting atau grouping modul
            ko.utils.arrayForEach(res,function(item){
                material.ListAkses.push({
                    id_modul : item.id_modul,
                    nama_modul : item.nama_modul,
                    // Konversi boolean ke observable untuk two-way binding
                    can_view : ko.observable(item.can_view == 1),
                    can_insert : ko.observable(item.can_insert == 1),
                    can_update : ko.observable(item.can_update == 1),
                    can_delete : ko.observable(item.can_delete == 1)
                });
            });
        }
    );
}
</script>

<!-- ===================== TAMPILAN HTML ===================== -->
<!-- Layout untuk halaman manajemen akses/role -->
<!-- Dikembangkan: bisa ditambah breadcrumb, statistik role, atau grafik -->
<div class="content-wrapper">

    <!-- ===================== HEADER HALAMAN ===================== -->
    <!-- Bagian title dan breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Akses</h1>
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
                    <!-- Tab navigasi (hanya list yang aktif) -->
                    <!-- Dikembangkan: bisa tambahkan tab untuk import/export akses -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tablist" data-toggle="tab">
                                List
                            </a>
                        </li>
                    </ul>
                    
                    <!-- ===================== KONTEN TAB ===================== -->
                    <div class="content tab-content" id="tabnavform-content">
                        
                        <!-- ===================== TAB FORM ===================== -->
                        <!-- Form untuk edit akses role -->
                        <!-- Dikembangkan: bisa ditambah fitur preview atau validasi role -->
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">
                                    
                                    <!-- ===================== TOMBOL AKSI FORM ===================== -->
                                    <!-- Button untuk back dan save -->
                                    <!-- Dikembangkan: bisa tambahkan tombol reset akses ke default -->
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
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ===================== TABEL AKSES ===================== -->
                                    <!-- Tabel untuk mengatur hak akses per modul -->
                                    <!-- Dikembangkan: bisa tambahkan grouping modul atau filter modul -->
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Modul</th>
                                                        <th class="text-center">View</th>
                                                        <th class="text-center">Add</th>
                                                        <th class="text-center">Edit</th>
                                                        <th class="text-center">Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody data-bind="foreach: ListAkses">
                                                    <tr>
                                                        <!-- Nama Modul -->
                                                        <td data-bind="text:nama_modul"></td>
                                                        
                                                        <!-- Checkbox View -->
                                                        <td class="text-center">
                                                            <input type="checkbox" data-bind="checked: can_view">
                                                        </td>
                                                        
                                                        <!-- Checkbox Add/Insert -->
                                                        <td class="text-center">
                                                            <input type="checkbox" data-bind="checked: can_insert">
                                                        </td>
                                                        
                                                        <!-- Checkbox Edit/Update -->
                                                        <td class="text-center">
                                                            <input type="checkbox" data-bind="checked: can_update">
                                                        </td>
                                                        
                                                        <!-- Checkbox Delete -->
                                                        <td class="text-center">
                                                            <input type="checkbox" data-bind="checked: can_delete">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end tabform -->
                        
                        <!-- ===================== TAB LIST ===================== -->
                        <!-- Tabel untuk menampilkan semua role -->
                        <!-- Dikembangkan: bisa tambahkan export Excel, print, atau column visibility -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23 ">
                                    <!-- ===================== FILTER DATA ===================== -->
                                    <!-- Panel filter untuk pencarian data -->
                                    <!-- Dikembangkan: bisa tambahkan filter berdasarkan jumlah user -->
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
                                    
                                    <!-- ===================== TABEL DATA ROLE ===================== -->
                                    <!-- DataTable untuk menampilkan list role -->
                                    <!-- Dikembangkan: bisa tambahkan sorting, pagination, atau row selection -->
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                                <thead>
                                                    <tr>
                                                        <th>ROLE</th>
                                                        <th>JUMLAH USER</th>
                                                        <th>ACTION</th>
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
        
        // ===================== INISIALISASI DATATABLE =====================
        // Konfigurasi DataTable untuk server-side processing
        // Dikembangkan: bisa ditambah opsi export, responsive, atau scroll
        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/AksesController/getData') ?>",
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
                { "data": "role" }, // Kolom role
                { "data": "jumlah_user" }, // Kolom jumlah user dengan role tersebut
                {
                    "data": "role",
                    "render": function(data, type, full, meta) {
                        // Tombol Edit untuk mengatur akses
                        // Dikembangkan: bisa tambahkan tombol delete atau copy akses
                        return "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp;</i></button>";
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