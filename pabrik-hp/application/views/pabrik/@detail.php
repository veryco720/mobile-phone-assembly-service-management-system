<script>
    model.masterModel = {
        id_detail: 0, 
        id_produksi: "",
        id_komponen: "",
        jumlah: "",
    }

    var material = {
        TITLE: "Data Detail",

        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('id_produksi'),

        SELECTPRODUKSI: ko.observableArray([]),
        SELECTKOMPONEN: ko.observableArray([]),

        SELECTFILTERVALUE: [
            { name: 'id_produksi', value: 'id_produksi' },
            { name: 'id_komponen', value: 'id_komponen' },
        ],
    }

    material.filtermaterial = function() {
        material.grid.ajax.reload();
    }

    material.filterreset = function() {
        material.FilterText('');
        material.grid.ajax.reload(null, false);
    }

    material.back = function() {
        material.Mode('');
        material.grid.ajax.reload(null, false);
        ko.mapping.fromJS(model.masterModel, material.Recordmaterial);
        $('#tabnavform a[href="#tablist"]').tab('show');
    }

    material.tambah = function() {
        ko.mapping.fromJS(model.masterModel, material.Recordmaterial);
        material.Mode('');
        $('#tabnavform a[href="#tabform"]').tab('show');
    }

    material.selectdata = function(id) {
        model.Processing(true);
        ajaxPost("<?php echo site_url('pabrik/DetailController/getDataSelect') ?>", {
            id: id
        }, function(res) {
            ko.mapping.fromJS(res[0], material.Recordmaterial);
            material.Mode("Update");
            $('#tabnavform a[href="#tabform"]').tab('show');
            model.Processing(false);
        });
    }

    material.save = function() {
        if (material.Recordmaterial.id_produksi() == "" || material.Recordmaterial.id_komponen() == "") {
            swal("Peringatan!", "ID Produksi dan ID Komponen harap diisi dengan benar!", "warning");
            return;
        }

        swal({
            title: "Perhatian",
            text: "Anda akan simpan data ini?",
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

                var url = "<?php echo base_url('pabrik/DetailController/save') ?>";
                if (material.Mode() === 'Update')
                    url = "<?php echo base_url('pabrik/DetailController/update') ?>";

                ajaxPost(url, material.Recordmaterial, function(res) {
                    model.Processing(false);
                    if (res.result == true || material.Mode() == "Update") {
                        var pesan = material.Mode() == "Update" ? "Data Berhasil di ubah!" : "Data Berhasil di input!";
                        swal("Berhasil!", pesan, "success");
                        material.back();
                    } else {
                        swal("Gagal!", "Data gagal disimpan.", "error");
                    }
                });
            }
        });
    }

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
                ajaxPost("<?php echo base_url('pabrik/DetailController/delete') ?>", {
                    id: id
                }, function(res) {
                    model.Processing(false);
                    if (res.result) {
                        material.grid.ajax.reload(null, false);
                        swal("Deleted!", "Data has been deleted successfully.", "success");
                    } else {
                        swal("Failed!", res.message, "warning");
                    }
                });
            }
        });
    }

    material.loadProduksi = function() {
        $.ajax({
            url: "<?php echo base_url('pabrik/DetailController/getProduksi') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {
                material.SELECTPRODUKSI(res);
            },
            error: function(xhr, status, error) {
                console.error("Error loading produksi data:", error);
            }
        });
       
    }

    material.loadKomponen = function() {
        $.ajax({
            url: "<?php echo base_url('pabrik/DetailController/getKomponen') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {
                material.SELECTKOMPONEN(res);
            },
            error: function(xhr, status, error) {
                console.error("Error loading komponen data:", error);
            }
        });
    }
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>detail produksi</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">

                    <!-- Nav Tab -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabform" data-toggle="tab">Form</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tablist" data-toggle="tab">List</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="tabnavform-content">

                        <!-- ======== TAB FORM ======== -->
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn">
                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group">
                                                <button class="btn btn-sm btn-warning"
                                                    data-bind="click: function(){ back(1); }, visible: Mode() == 'Update'"
                                                    data-toggle="tooltip" title="Kembali">
                                                    <i class="fa fa-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info"
                                                    data-bind="click: save"
                                                    data-toggle="tooltip" title="Simpan">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    data-bind="click: function(){ remove(Recordmaterial.id()); }, visible: Mode() == 'Update'">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                                <div class="form-group" data-bind="with: Recordmaterial">
                                                    <label for="alamat">id_produksi</label>
                                                        <fieldset class="form-group">
                                                            <select data-bind="
                                                                options: material.SELECTPRODUKSI,
                                                                optionsText: 'name',
                                                                optionsValue: 'value',
                                                                value:id_produksi,"
                                                                class="form-control" id="basicSelect">
                                                            </select>
                                                    </fieldset>
                                                </div>
                                                <div class="form-group" data-bind="with: Recordmaterial">
                                                    <label for="alamat">id_komponen</label>
                                                        <fieldset class="form-group">
                                                            <select data-bind="
                                                                options: material.SELECTKOMPONEN,
                                                                optionsText: 'name',
                                                                optionsValue: 'value',
                                                                value:id_komponen"
                                                                class="form-control" id="basicSelect">
                                                            </select>
                                                    </fieldset>
                                                </div>
                                            <div class="form-group" data-bind="with: Recordmaterial">
                                                <label for="inputjumlah">jumlah</label>
                                                <input type="text" id="inputjumlah" name="jumlah"
                                                    class="form-control"
                                                    data-bind="value: jumlah"
                                                    placeholder="Masukkan Jumlah">
                                            </div>
                                       
                                 
                                </div>
                            </div>
                        </div>
                        <!-- ======== END TAB FORM ======== -->

                        <!-- ======== TAB LIST ======== -->
                        <div class="tab-pane fade" id="tablist">
                            <div class="card mt-3">
                                <div class="card-header">
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
                                            <button class="btn btn-md btn-danger" data-bind="click:filterreset"><span class="fa fa-retweet"></span></button>
                                            <button class="btn btn-md btn-primary" data-bind="click:filtermaterial"><span class="fa fa-search"></span></button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="myTable" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>id_detail</th>
                                                <th>id_produksi</th>
                                                <th>id_komponen</th>
                                                <th>jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ======== END TAB LIST ======== -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        model.Processing(true);
        material.loadProduksi();
        material.loadKomponen();

        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/DetailController/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext']  = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    json.recordsTotal    = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    return json.Data ? json.Data : [];
                },
            },
            "searching": false,
            "columns": [
                { "data": "id_detail" },
                { "data": "id_produksi" },
                { "data": "nama_komponen" },
                {"data": "jumlah" },
                {
                    "data": "id_detail",
                    "render": function(data) {
                        return "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp;" +
                               "<button class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")'><i class='fa fa-trash'></i></button>";
                    }
                }
            ],
        });

        model.Processing(false);
    });
</script>