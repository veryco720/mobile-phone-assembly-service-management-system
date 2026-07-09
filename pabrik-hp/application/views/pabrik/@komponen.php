<script>
    model.masterModel = {
        id_komponen: 0,
        id_supplier: "",
        nama_komponen: "",
        kategori: "",
        stok: "",
        satuan: "",
        harga: "",
    }
    var material = {
        title: "Data Quality Control",
        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        DataFilter: ko.observableArray(['id_supplier']),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('id_supplier'),

        SELECTSUPPLIER: ko.observableArray([]),

        SELECTFILTERVALUE:  [
         { name: 'id_supplier', value: 'k.id_supplier'},
         { name: 'nama_komponen', value: 'k.nama_komponen'},
         { name: 'kategori', value: 'k.kategori'},
         { name: 'stok', value: 'k.stok'},
         { name: 'satuan', value: 'k.satuan'},
         { name: 'harga', value: 'k.harga'},
         ],
    }
    material.filtermaterial = function() {
      material.grid.ajax.reload();
    }
    material.filterreset = function() {
      material.FilterText('');
      material.grid.ajax.reload(null, false);
    }
    material.back = function(tab) {
        material.Mode('');
        material.grid.ajax.reload(null, false);
        // $("input[name=txtkategoriId]").attr("disabled", false);
        // $("input[name=txtJUDUL").attr("disabled", false);
        ko.mapping.fromJS(model.masterModel, material.Recordmaterial);
        model.activetab(tab);
    }

    material.selectdata = function(id) {
        model.Processing(true);
        ajaxPost("<?php echo site_url('contoh/KomponenController/getDataSelect') ?>", {
            id: id
        }, function(res) {
            console.log(res[0]);
            material.back(0);
            // $("input[name=txtJUDUL").attr("disabled", true);
            ko.mapping.fromJS(res[0], material.Recordmaterial);
            material.Mode("Update");
            model.Processing(false);
        });
    }

    material.save = function() {
        model.Processing(true);
        var val = material.Recordmaterial;
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
            // timer: 1000,
        }, function(isConfirm) {
            if (isConfirm) {
                if (material.Recordmaterial.id_supplier() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                    }, );
                } else {
                    // end else
                    if (showLoaderOnConfirm = true) {

                        var url = "<?php echo base_url('pabrik/KomponenController/save') ?>";

                        if (material.Mode() === 'Update')
                            url = "<?php echo base_url('pabrik/KomponenController/update') ?>";

                        ajaxPost(url, material.Recordmaterial,
                            function(res) {
                                console.log(res.result);
                                if (res.result == true || material.Mode() == "Update") {
                                    if (res.result == true) {

                                        setTimeout(function() {
                                            swal({
                                                title: "Good job!",
                                                text: "Data Berhasil di input!",
                                                icon: "success",
                                                /* sukses simpan / update */
                                            });
                                        }, 2000);
                                    }
                                    if (material.Mode() == "Update") {

                                        setTimeout(function() {
                                            swal({
                                                title: "Good job!",
                                                text: "Data Berhasil di ubah!",
                                                icon: "success",
                                                /* sukses simpan / update */
                                            });
                                        }, 2000);
                                    }
                                    material.back(1);
                                }
                            });
                    }
                }
            }
            model.Processing(false);
        }); // END isconfirm swal
        model.Processing(false);
    }
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
                ajaxPost("<?php echo base_url('pabrik/KomponenController/delete') ?>", {
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
    }

    material.loadSupplier = function () {
        $.ajax({
            url:  "<?php echo site_url('pabrik/KomponenController/getSupplier') ?>", // sesuaikan nama controller
            type: "GET",
            dataType: "json",
            success: function (res) {
                console.log(res)
                material.SELECTSUPPLIER(res);
            },
            error: function (err) {
                console.log("Gagal load supplier", err);
            }
        });
    };

    material.loadKaryawan = function () {
        $.ajax({
            url:  "<?php echo site_url('pabrik/KomponenController/getKaryawan') ?>", // sesuaikan nama controller
            type: "GET",
            dataType: "json",
            success: function (res) {
                console.log(res)
                material.SELECTKARYAWAN(res);
            },
            error: function (err) {
                console.log("Gagal load karyawan", err);
            }
        });
    };

</script>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Komponen</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">
                    <!-- Nav tab -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item"><a class="nav-link active" href="#tabform" data-toggle="tab">Form</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tablist" data-toggle="tab">List</a></li>
                    </ul>
                    <!-- end Nav Tab -->

                    <div class="content tab-content" id="tabnavform-content">
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">
                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group ">
                                                <button class="btn btn-sm btn-warning" data-bind="click:function(){back(1);}, visible: Mode() == 'Update'" data-toggle="tooltip" data-placement="top" data-original-title="Kembali"><i class="fa fa-arrow-left"></i> </button>

                                                <button class="btn btn-sm btn-info" data-bind="click:save" data-toggle="tooltip" data-placement="top" data-original-title="simpan"> <span data-bind="data-original-title:Mode"><i class="fa fa-save"></i> </span></button>

                                                <button class="btn btn-sm btn-danger" data-bind="click:function(){remove(Recordmaterial.id());}, visible: Mode() == 'Update'"></span><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        <div class="form-group" >
                                            <label for="level">id_supplier</label>
                                                <fieldset class="form-group">
                                                    <select data-bind="
                                                        options: material.SELECTSUPPLIER,
                                                        optionsText: 'name',
                                                        optionsValue: 'value',
                                                        value:id_supplier"
                                                        class="form-control" id="basicSelect">
                                                    </select>
                                            </fieldset>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_komponen">nama_komponen</label>
                                            <input type="text" class="form-control" id="nama_komponen" data-bind="value:nama_komponen" placeholder="Masukkan NAMA KOMPONEN">
                                        </div>
                                        <div class="form-group">
                                            <label for="kategori">kategori</label>
                                            <textarea id="kategori" name="kategori" class="form-control" data-bind="value:kategori" placeholder="Masukkan KATEGORI">I am a comment</textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="stok">stok</label>
                                            <textarea id="stok" name="stok" class="form-control" data-bind="value:stok" placeholder="Masukkan STOK">I am a comment</textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="satuan">satuan</label>
                                            <textarea id="satuan" name="satuan" class="form-control" data-bind="value:satuan" placeholder="Masukkan SATUAN">I am a comment</textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="harga">harga</label>
                                            <input type="number" name="harga" class="form-control" data-bind="value:harga" id="harga" placeholder="Masukkan HARGA">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23 ">
                                    <!-- filter -->
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
                                                <button class="btn btn-md btn-danger" data-bind="click:filterreset"><span class="fa fa-retweet"></span></button>
                                                <button class="btn btn-md btn-primary" data-bind="click:filtermaterial"><span class="fa fa-search"></span></button>
                                            </div>
                                        </div>
                                        <!-- ./filter -->

                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped ">
                                                <thead>
                                                    <tr>
                                                        <th>id_komponen</th>
                                                        <th>id_supplier</th>
                                                        <th>nama_komponen</th>
                                                        <th>kategori</th>
                                                        <th>stok</th>
                                                        <th>satuan</th>
                                                        <th>harga</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                </div> <!-- end content form -->
                <!-- end table -->
            </div>
        </div>
    </section>
    <!-- end content -->

</div>
<!-- end wrapper -->



<script>
    $(document).ready(function() {
        model.Processing(true);
        material.loadSupplier();

        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/KomponenController/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext'] = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    // json.draw = 1;
                    json.recordsTotal = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;

                    if (json.Data)
                        return json.Data;
                    else
                        return [];
                },
            },
            "searching": false,
            "columns": [
                {
                    "data": "id_komponen"
                },
                {
                    "data": "id_supplier"
                },
                {
                    "data": "nama_komponen"
                },
                {
                    "data": "kategori"
                },
                {
                    "data": "stok"
                },
                {
                    "data": "satuan"
                },
                {
                    "data": "harga"
                },
                {
                    "data": "id_komponen",
                    "render": function(data, type, full, meta) {
                        return "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; <button  id='sa-warning' class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")' id='sa-warning' ><i class='fa fa-trash'></i></button>";
                    }
                }
            ],
        });
        model.Processing(false);
    });
</script>