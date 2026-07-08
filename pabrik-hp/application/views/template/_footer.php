<footer class="main-footer">
    <center>
        <strong>&copy; Copyright Kazuya Media Indonesia 2020 | All right reserved</strong>
    </center>
</footer>
<aside class="control-sidebar control-sidebar-dark">

</aside>

</div>
<!-- AdminLTE App -->
<script src="<?= base_url();?>assets/AdminLTE3/dist/js/adminlte.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url();?>assets/AdminLTE3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/bootstrap.bundle.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/Chart.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/sparkline.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/jquery.vmap.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/jquery.vmap.usa.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/jquery.knob.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/moment.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/daterangepicker.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/tempusdominus-bootstrap-4.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/summernote-bs4.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/jquery.overlayScrollbars.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/adminlte.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/demo.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>js/dashboard.js">

<link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/js/adminlte.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/js/bootstrap.bundle.min.js">
<link rel="stylesheet" href="<?= base_url('assets/'); ?>admin/js/jquery.min.js">

<!-- moment - for date formate -->
<script src="<?= base_url(); ?>assets/js/moment.min.js"></script>

<!-- This is data table -->
<script src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>

<!-- start - This is for export functionality only -->
<script src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>

<!-- Bootstrap -->
<script src="<?= base_url(); ?>assets/AdminLTE3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    model.activetab = function(index) {
        $("#tabnavform li>.nav-link").removeClass("active");
        $("#tabnavform li>.nav-link").attr({
            "aria-expanded": false
        });
        $("#tabnavform li>.nav-link").eq(index).addClass("active");
        $("#tabnavform li>.nav-link").eq(index).attr({
            "aria-expanded": true
        });
        $("#tabnavform-content div.tab-pane").removeClass("active");
        $("#tabnavform-content div.tab-pane").attr({
            "aria-expanded": false
        });
        $("#tabnavform-content div.tab-pane").eq(index).addClass("active");
        $("#tabnavform-content div.tab-pane").eq(index).attr({
            "aria-expanded": true
        });
    }

    function ajaxPost(url, data, callbackSuccess, callbackError, otherConfig) {
        var startReq = moment();
        var callbackScheduler = function(callback) {
            callback();
        };
        if (typeof callbackSuccess == "object") {
            otherConfig = callbackSuccess;
            callbackSuccess = function() {};
            callbackError = function() {};
        }
        if (typeof callbackError == "object") {
            otherConfig = callbackError;
            callbackError = function() {};
        }
        var config = {
            url: url,
            type: 'post',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: ko.mapping.toJSON(data),
            success: function(a) {
                callbackScheduler(function() {
                    if (callbackSuccess !== undefined) {
                        callbackSuccess(a);
                    }
                });
            },
            error: function(a, b, c) {
                callbackScheduler(function() {
                    if (callbackError !== undefined) {
                        callbackError(a, b, c);
                    }
                });
            }
        };
        if (data instanceof FormData) {
            delete config.config;
            config.data = data;
            config.async = false;
            config.cache = false;
            config.contentType = false;
            config.processData = false;
        }
        if (otherConfig != undefined) {
            config = $.extend(true, config, otherConfig);
        }
        return $.ajax(config);
    };


    ko.applyBindings(model);
    $(document).ready(function() {
        model.Processing(false);
    });
</script>

</body>

</html>