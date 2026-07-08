<?php $this->load->view('template/_header'); 
      $this->load->view('template/_navbarMain'); 
      $this->load->view('template/_navbarSide'); 
?>


    <?= $contents; ?>

<?php 
    $this->load->view('template/_footer'); 
?>