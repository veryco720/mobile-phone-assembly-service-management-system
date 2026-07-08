<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url(); ?>assets\img\faces\admin.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"> Daftar Item</span>
    </a>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

           

                <!-- SKILLS -->
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == 'KaryawanController'|| $this->uri->segment(2) == 'UserController' ) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == 'KaryawanController' || $this->uri->segment(2) == 'UserController' ) ? 'active' : ''; ?>">
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
                </li>



            </ul>
        </nav>
    </div>
</aside>