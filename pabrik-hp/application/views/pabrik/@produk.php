<script>
    model.masterModel = {
        id_produk: 0,
        nama_produk: "",
        tipe: "",
        warna: "",
        kapasitas_ram: "",
        kapasitas_rom: ""
    }
    var material = {
        title: "Data Satuan",
        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        DataFilter: ko.observableArray(['nama_produk']),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('nama_produk'),

        SELECTFILTERVALUE:  [
            { name: 'nama_produk', value: 'nama_produk'},
            { name: 'tipe', value: 'tipe'},
            { name: 'warna', value: 'warna'},
            { name: 'kapasitas_ram', value: 'kapasitas_ram'},
            { name: 'kapasitas_rom', value: 'kapasitas_rom'},
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
        ajaxPost("<?php echo site_url('pabrik/ProdukController/getDataSelect') ?>", {
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
                if (material.Recordmaterial.nama_produk() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                    }, );
                } else {
                    // end else
                    if (showLoaderOnConfirm = true) {

                        var url = "<?php echo base_url('pabrik/ProdukController/save') ?>";

                        if (material.Mode() === 'Update')
                            url = "<?php echo base_url('pabrik/ProdukController/update') ?>";

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
                ajaxPost("<?php echo base_url('pabrik/ProdukController/delete') ?>", {
                    id_produk: id // Pastikan ini adalah ID yang benar
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
</script>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Produk</h1>
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

                                                <button class="btn btn-sm btn-danger" data-bind="click:function(){remove(Recordmaterial.id_produk());}, visible: Mode() == 'Update'"></span><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        <div class="form-group">
                                            <label for="level">nama_produk</label>
                                            <input type="text" name="level" class="form-control" data-bind="value:nama_produk" id="idinstansi" placeholder="Masukkan nama produk">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">tipe</label>
                                            <input type="text" name="tipe" class="form-control" data-bind="value:tipe" id="tipe" placeholder="Masukkan tipe">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">warna</label>
                                            <input type="text" name="warna" class="form-control" data-bind="value:warna" id="warna" placeholder="Masukkan warna">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">kapasitas_ram</label>
                                            <input type="text" name="kapasitas_ram" class="form-control" data-bind="value:kapasitas_ram" id="kapasitas_ram" placeholder="Masukkan kapasitas RAM">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">kapasitas_rom</label>
                                            <input type="text" name="kapasitas_rom" class="form-control" data-bind="value:kapasitas_rom" id="kapasitas_rom" placeholder="Masukkan kapasitas ROM">
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
                                                    <small class="text-muted">ketik <i>yang anda mau cari</i> lalu <b>Enter</b></small>
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
                                                        <th>id_produk</th>
                                                        <th>nama_produk</th>
                                                        <th>tipe</th>
                                                        <th>warna</th>
                                                        <th>kapasitas_ram</th>
                                                        <th>kapasitas_rom</th>
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

        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('pabrik/ProdukController/getData') ?>",
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
            "columns": [{
                    "data": "id_produk"
                },
                {
                    "data": "nama_produk"
                },
                {
                    "data": "tipe"
                },
                {
                    "data": "warna"
                },
                {
                    "data": "kapasitas_ram"
                },
                {
                    "data": "kapasitas_rom"
                },
                {
                    "data": "id_produk",
                    "render": function(data, type, full, meta) {
                        return "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; <button  id='sa-warning' class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")' id='sa-warning' ><i class='fa fa-trash'></i></button>";
                    }
                }
            ],
        });
        model.Processing(false);
    });
</script>