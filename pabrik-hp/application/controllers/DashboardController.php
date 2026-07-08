<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = "Dashboard Page";
        $data['content_header'] = "Dashboard";
        $this->template->load('main_template', 'admin/@dashboard', $data);
    }
}

/* End of file DashboardController.php and path \application\controllers\DashboardController.php */
