<script>
    model.masterModel = {
        id_karyawan: 0,
        nama_karyawan: "",
        jabatan: "",
        no_hp: "",
        alamat: "",
        status: "aktif"
        

    }
    var material = {
        title: "Data Satuan",
        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        DataFilter: ko.observableArray(['nama_karyawan']),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('nama_karyawan'),

        
         SELECTFILTERVALUE:  [
            { name: 'NAME', value: 'nama_karyawan'},
            { name: 'JABATAN', value: 'jabatan'},
            { name: 'NO HP', value: 'no_hp'},
            { name: 'ALAMAT', value: 'alamat'},    
            { name: 'STATUS', value: 'status'},
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
        ajaxPost("<?php echo site_url('pabrik/KaryawanController/getDataSelect') ?>", {
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
                if (material.Recordmaterial.nama_karyawan() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                    }, );
                } else {
                    // end else
                    if (showLoaderOnConfirm = true) {

                        var url = "<?php echo base_url('pabrik/KaryawanController/save') ?>";

                        if (material.Mode() === 'Update')
                            url = "<?php echo base_url('pabrik/KaryawanController/update') ?>";

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
    }
</script>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Karyawan</h1>
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
                                                <button class="btn btn-sm btn-warning" data-bind="click:function(){back(1);}, visible: Mode() == 'Update'" data-toggle="tooltip" data-placement="top" data-original-TITLE="Kembali"><i class="fa fa-arrow-left"></i> </button>

                                                <button class="btn btn-sm btn-info" data-bind="click:save" data-toggle="tooltip" data-placement="top" data-original-TITLE="simpan"> <span data-bind="data-original-TITLE:Mode"><i class="fa fa-save"></i> </span></button>

                                                <button class="btn btn-sm btn-danger" data-bind="click:function(){remove(Recordmaterial.id());}, visible: Mode() == 'Update'"></span><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        <div class="form-group">
                                            <label for="level">NAME</label>
                                            <input type="text" name="level" class="form-control" data-bind="value:nama_karyawan" id="idinstansi" placeholder="Masukkan NAME">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">JABATAN</label>
                                            <select class="form-control" data-bind="value: jabatan">
                                                <option value="">-- Pilih Jabatan --</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Supervisor">Supervisor</option>
                                                <option value="Operator">Operator</option>
                                                <option value="QC">QC</option>
                                                <option value="Admin">Admin</option>
                                            </select>                                        
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">NO HP</label>
                                            <input type="text" name="no_hp" class="form-control" data-bind="value:no_hp" id="no_hp" placeholder="Masukkan NO HP">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">ALAMAT</label>
                                            <textarea id="content" name="content" class="form-control" data-bind="value:alamat" placeholder="Masukkan ALAMAT">masukan alamat</textarea>
                                        </div>
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
                "url": "<?php echo base_url('pabrik/KaryawanController/getData') ?>",
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
                    "data": "id_karyawan"
                },
                {
                    "data": "nama_karyawan"
                },
                {
                    "data": "jabatan"
                },
                {
                    "data": "no_hp"
                },
                {
                    "data": "alamat"
                },
                {
                    "data": "status",
                    "render": function(data, type, full, meta) {
                        return data == "aktif" ? "<span class='badge bg-success'>Aktif</span>" : "<span class='badge bg-danger'>Nonaktif</span>";
                    }
                },
                {
                    "data": "id_karyawan",
                    "render": function(data, type, full, meta) {
                        return "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; <button  id='sa-warning' class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")' id='sa-warning' ><i class='fa fa-trash'></i></button>";
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