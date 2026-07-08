<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title Pages</title>

    <!-- Bootstrap CSS -->


    <!-- <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/css.css"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/all.min.css">


    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/ionicons.min.css">


    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/tempusdominus-bootstrap-4.min.css">


    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/jqvmap.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/adminlte.min.css?v=3.2.0">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/OverlayScrollbars.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/daterangepicker.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/summernote-bs4.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/fonts.googleapis.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/adminlte.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/fontawesom.css">

    <link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/plugins/icheck-bootstrap.css">

    <!-- jquery -->
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery_2.2.4.min.js"></script>

    <!-- knockout -->
    <script src="<?php echo base_url() ?>assets/knockout/knockout-3.1.0.js"></script>
    <script src="<?php echo base_url() ?>assets/knockout/knockout.mapping-latest.js"></script>
    <script src="<?php echo base_url() ?>assets/knockout/knockout-file-bindings.js"></script>
    <link href="<?php echo base_url() ?>assets/knockout/knockout-file-bindings.css">

    <!-- token input -->
    <link href="<?= base_url(); ?>assets/token_input/token-input.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/token_input/token-input-facebook.css" rel="stylesheet">
    <script src="<?= base_url(); ?>assets/token_input/jquery.tokeninput.js"></script>

    <!-- alert -->
    <link href="<?= base_url(); ?>assets/AdminLTE3/alert/sweetalert.css" rel="stylesheet" type="text/css">
    <script src="<?= base_url(); ?>assets/AdminLTE3/alert/sweetalert.min.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/AdminLTE3/plugins/fontawesome-free/css/all.min.css">

    <script>
        var model = {
            Processing: ko.observable(true),
            idlevel:  ko.observable(<?php echo $this->session->userdata('role'); ?>),
        }
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center" data-bind='visible: model.Processing()==true'>
            <img class="animation__shake" src="<?= base_url(); ?>assets/admin/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>