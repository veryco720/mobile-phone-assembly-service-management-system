<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a class="brand-link" href="<?= base_url('pabrik/DashboardController') ?>">
        <i class="fas fa-boxes brand-image elevation-3"
        style="font-size:30px;color:#4f46e5;"></i>

        <span class="brand-text font-weight-light">
            Daftar Item
        </span>
    </a>
    <a class="brand-link" href="<?= base_url('profil') ?>">
        <img src="<?= base_url(); ?>assets\img\faces\admin.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Profil Pengguna</span>
    </a>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

           

                <!-- SKILLS -->
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == 'KaryawanController'|| $this->uri->segment(2) == 'UserController' || $this->uri->segment(2) == 'DetailController' || $this->uri->segment(2) == 'ProduksiController' || $this->uri->segment(2) == 'GudangController' || $this->uri->segment(2) == 'ProdukController' || $this->uri->segment(2) == 'SupplierController' || $this->uri->segment(2) == 'QualityController' || $this->uri->segment(2) == 'KomponenController' || $this->uri->segment(2) == 'AksesController') ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == 'data-karyawan' || $this->uri->segment(2) == 'data-user' || $this->uri->segment(2) == 'data-detail' || $this->uri->segment(2) == 'data-produksi' || $this->uri->segment(2) == 'data-gudang' || $this->uri->segment(2) == 'data-produk' || $this->uri->segment(2) == 'data-supplier' || $this->uri->segment(2) == 'data-quality' || $this->uri->segment(2) == 'data-komponen' || $this->uri->segment(2) == 'data-akses') ? 'active' : ''; ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Master 
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(3,'can_view')): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('data-karyawan'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'KaryawanController') ? 'active' : ''; ?>">
                                <i class="nav-icon fa fa-users"></i>
                                <p>Karyawan</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(2,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-user') ?>" class="nav-link <?= ($this->uri->segment(2) == 'UserController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>Data User</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(8,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-detail') ?>" class="nav-link <?= ($this->uri->segment(2) == 'DetailController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-list"></i>
                                    <p>Data Detail</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(7,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-produksi') ?>" class="nav-link <?= ($this->uri->segment(2) == 'ProduksiController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-cogs"></i>
                                    <p>Data Produksi</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(10,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-gudang') ?>" class="nav-link <?= ($this->uri->segment(2) == 'GudangController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-warehouse"></i>
                                    <p>Data Gudang</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(6,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-produk') ?>" class="nav-link <?= ($this->uri->segment(2) == 'ProdukController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-box"></i>
                                    <p>Data Produk</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(4,'can_view')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('data-supplier') ?>" class="nav-link <?= ($this->uri->segment(2) == 'SupplierController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-truck"></i>
                                    <p>Data Supplier</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(9,'can_view')): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('data-quality'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'QualityController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-check-circle"></i>
                                <p>Modul Quality</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if(cekAkses(5,'can_view')): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('data-komponen'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'KomponenController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-microchip"></i>
                                <p>Modul Komponen</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="nav nav-treeview">
                        <?php if($this->session->userdata('role') == "admin"): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('data-akses'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'AksesController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Modul Akses</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
    
 

            </ul>
        </nav>
    </div>
</aside>