<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url(); ?>assets\img\faces\admin.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"> Daftar Item</span>
    </a>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

           

                <!-- SKILLS -->
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == 'KaryawanController'|| $this->uri->segment(2) == 'UserController' || $this->uri->segment(2) == 'DetailController' || $this->uri->segment(2) == 'ProduksiController' || $this->uri->segment(2) == 'GudangController' || $this->uri->segment(2) == 'ProdukController' || $this->uri->segment(2) == 'SupplierController' || $this->uri->segment(2) == 'QualityController' || $this->uri->segment(2) == 'KomponenController') ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == 'data-karyawan' || $this->uri->segment(2) == 'data-user' || $this->uri->segment(2) == 'data-detail' || $this->uri->segment(2) == 'data-produksi' || $this->uri->segment(2) == 'data-gudang' || $this->uri->segment(2) == 'data-produk' || $this->uri->segment(2) == 'data-supplier' || $this->uri->segment(2) == 'data-quality' || $this->uri->segment(2) == 'data-komponen') ? 'active' : ''; ?>">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Master 
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('data-karyawan'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'KaryawanController') ? 'active' : ''; ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Karyawan</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-user') ?>" class="nav-link <?= ($this->uri->segment(2) == 'UserController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>Data User</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-detail') ?>" class="nav-link <?= ($this->uri->segment(2) == 'DetailController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-list"></i>
                                    <p>Data Detail</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-produksi') ?>" class="nav-link <?= ($this->uri->segment(2) == 'ProduksiController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-cogs"></i>
                                    <p>Data Produksi</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-gudang') ?>" class="nav-link <?= ($this->uri->segment(2) == 'GudangController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-warehouse"></i>
                                    <p>Data Gudang</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-produk') ?>" class="nav-link <?= ($this->uri->segment(2) == 'ProdukController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-box"></i>
                                    <p>Data Produk</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('data-supplier') ?>" class="nav-link <?= ($this->uri->segment(2) == 'SupplierController') ? 'active' : ''; ?>">
                                    <i class="nav-icon fa fa-truck"></i>
                                    <p>Data Supplier</p>
                                </a>
                            </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('data-quality'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'QualityController') ? 'active' : ''; ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Modul Quality</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('data-komponen'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'KomponenController') ? 'active' : ''; ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Modul Komponen</p>
                            </a>
                        </li>
                    </ul>
                </li>
    
 

            </ul>
        </nav>
    </div>
</aside>