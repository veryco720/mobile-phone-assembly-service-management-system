<div class="content-wrapper">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Dashboard</h3>
            </div>
        </div>
    </div>
</section>

<section class="content">



<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total_produk ?></h3>
                <p>Total Produk</p>
            </div>
            <div class="icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $total_karyawan ?></h3>
                <p>Total Karyawan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $total_supplier ?></h3>
                <p>Total Supplier</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $total_komponen ?></h3>
                <p>Total Komponen</p>
            </div>
            <div class="icon">
                <i class="fas fa-microchip"></i>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= $produksi ?></h3>
                <p>Produksi Berjalan</p>
            </div>
            <div class="icon">
                <i class="fas fa-cogs"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?= $qc ?></h3>
                <p>Quality Control</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $selesai ?></h3>
                <p>Produksi Selesai</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3><?= $stok ?></h3>
                <p>Total Stok Gudang</p>
            </div>
            <div class="icon">
                <i class="fas fa-warehouse"></i>
            </div>
        </div>
    </div>

</div>




<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-industry"></i>
            Produksi Terbaru
        </h3>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="bg-light">

                <tr>
                    <th width="50">No</th>
                    <th>Produk</th>
                    <th>Operator</th>
                    <th>Tanggal</th>
                    <th>Target</th>
                    <th>Selesai</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            <?php if(!empty($produksi_terbaru)){ ?>

                <?php $no=1; foreach($produksi_terbaru as $row){ ?>

                <tr>

                    <td><?= $no++ ?></td>

                    <td><?= $row->nama_produk ?></td>

                    <td><?= $row->nama_karyawan ?></td>

                    <td><?= date('d-m-Y',strtotime($row->tanggal_produksi)); ?></td>

                    <td><?= $row->target ?></td>

                    <td><?= $row->jumlah_selesai ?></td>

                    <td>

                        <?php

                        if($row->status=="Perakitan"){
                            echo '<span class="badge badge-primary">Perakitan</span>';
                        }
                        elseif($row->status=="QC"){
                            echo '<span class="badge badge-warning">QC</span>';
                        }
                        else{
                            echo '<span class="badge badge-success">Selesai</span>';
                        }

                        ?>

                    </td>

                </tr>

                <?php } ?>

            <?php }else{ ?>

                <tr>

                    <td colspan="7" class="text-center">
                        Tidak ada data produksi.
                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</section>
</div>