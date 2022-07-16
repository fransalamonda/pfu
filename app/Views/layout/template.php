<!DOCTYPE html>

<head>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="WBI | Patrol Follow Up">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?php base_url("") ?>/pfu/public/assets/images/icon_smp.png">
        <link rel="shortcut icon">

        <title><?= $tag_title ?></title>
        <!-- Plugins css-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/multiselect/multi-select.css" rel="stylesheet" type="text/css" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/switchery/switchery.min.css" rel="stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-datepicker/bootstrap-datepicker.css" rel="stylesheet">
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/dropzone/dropzone.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="<?php base_url(); ?>/pfu/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php base_url(); ?>/pfu/public/assets/css/app.min.css?v<?= ini_set('date.timezone', "Asia/Jakarta") ?>" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/libs/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php base_url(); ?>/pfu/public/assets/css/patrol.css?v<?= ini_set('date.timezone', "Asia/Jakarta") ?>" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="<?php base_url(); ?>/pfu/public/assets/css/userdefined.css?v=<?= ini_set('date.timezone', "Asia/Jakarta") ?>" rel="stylesheet" type="text/css" />


    </head>

<body data-layout="horizontal">
    <div class="se-pre-con"></div>
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
    <input type="hidden" id="csrf" value="<?= csrf_hash() ?>" />
    <div id="wrapper">
        <?= $this->include('layout/header'); ?>

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!--Widget-4 -->
                    <div id="content_wrapper">

                        <!-- end row -->
                        <?= $this->renderSection('content'); ?>
                        <!-- end row -->
                    </div>

                </div>
            </div>


        </div>
    </div>

    <script>
        var resizefunc = [];
    </script>

    <!-- Vendor js -->
    <script src="<?php echo base_url(); ?>/public/assets/js/vendor.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/moment/moment.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/js/pages/sweetalert2@11.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/jquery-validation/jquery.validate.min.js"></script>


    <!-- third party js -->
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="<?php base_url("") ?>/pfu/public/assets/libs/jszip/jszip.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.fixedheader.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/datatables/dataTables.scroller.min.js"></script>


    <!-- Plugins Js -->
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/switchery/switchery.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/multiselect/jquery.multi-select.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/jquery-quicksearch/jquery.quicksearch.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/select2/select2.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/jquery-mask-plugin/jquery.mask.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/moment/moment.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="<?php base_url(); ?>/pfu/public/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/dropzone/dropzone.min.js"></script>

    <!-- Datatables init -->
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/js/pages/datatables.init.js"></script>


    <!-- Responsive Table js -->
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/rwd-table/rwd-table.min.js"></script>

    <!-- Init js-->
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/dropzone/dropzone.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/js/pages/form-advanced.init.js"></script>
    <!-- Toastr js -->
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/toastr/toastr.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/js/pages/toastr.init.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/libs/bootbox/bootbox.all.min.js"></script>



    <!-- App js -->
    <script src="<?php base_url(); ?>/pfu/public/assets/js/app.min.js"></script>
    <script src="<?php base_url("") ?>/pfu/public/assets/js/universal.js?v=<?= date("His") ?>"></script>

    <script class="js_path" src="<?php echo $js_path ?>"></script>
    <script type="text/javascript">
        <?php
        if (isset($js_initial))
            echo $js_initial;
        ?>
    </script>
    <script type="text/javascript">
        $(window).on('load', function() {
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
</body>

</html>