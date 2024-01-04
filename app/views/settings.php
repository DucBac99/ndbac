
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="wingo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, wingo admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="pixelstrap">
        <link rel="icon" href="../assets/images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
        <title>Cài đặt | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>
        <!-- Google font-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
        <!-- Font Awesome-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/font-awesome.css?v=" . VERSION ?>">
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/icofont.css?v=" . VERSION ?>">
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/themify.css?v=" . VERSION ?>">
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/flag-icon.css?v=" . VERSION ?>">
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/feather-icon.css?v=" . VERSION ?>">
        <!-- Plugins css start-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/animate.css?v=" . VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/chartist.css?v=" . VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/prism.css?v=" . VERSION ?>">
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/bootstrap.css?v=" . VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/select2.css?v=" . VERSION ?>">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/style.css?v=" . VERSION ?>">
        <link id="color" rel="stylesheet" href="<?= APPURL . "/assets/css/color-1.css?v=" . VERSION ?>" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/responsive.css?v=" . VERSION ?>">

        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/sweetalert2.css?v=" . VERSION ?>">
    </head>
    <body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
        <div class="main-loader">
            <div class="bar-0"></div>
            <div class="bar-1"></div>
            <div class="bar-2"></div>
            <div class="bar-3"></div>
            <div class="bar-4"></div>
        </div>
        <div class="loading">Loading...    </div>
        </div>
        <!-- Loader ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <?php require_once(APPPATH . '/views/components/topbar.component.php'); ?>
        <!-- Page Header Ends                              -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper sidebar-icon">
            <!-- Page Sidebar Start-->
            <?php
            $Nav = new stdClass;
            $Nav->activeMenu = "dashboard";
            require_once(APPPATH . '/views/components/navigation.component.php');
            ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
            <?php require_once(APPPATH . '/views/settings/' . $page . '.setting.php'); ?>
            <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php require_once(APPPATH . '/views/components/footer.component.php'); ?>
            <!-- tap on top starts-->
            <div class="tap-top"><i class="icon-control-eject"></i></div>
            <!-- tap on tap ends-->
        </div>
        </div>
        <!-- latest jquery-->
        <script src="<?= APPURL . "/assets/js/jquery-3.5.1.min.js?v=" . VERSION ?>"></script>
        <!-- feather icon js-->
        <script src="<?= APPURL . "/assets/js/icons/feather-icon/feather.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/icons/feather-icon/feather-icon.js?v=" . VERSION ?>"></script>
        <!-- Sidebar jquery-->
        <script src="<?= APPURL . "/assets/js/sidebar-menu.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/config.js?v=" . VERSION ?>">   </script>
        <!-- Bootstrap js-->
        <script src="<?= APPURL . "/assets/js/bootstrap/popper.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/bootstrap/bootstrap.min.js?v=" . VERSION ?>"></script>
        <!-- Plugins JS start-->
        <script src="<?= APPURL . "/assets/js/chart/chartjs/chart.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/chartist/chartist.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/morris-chart/raphael.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/morris-chart/morris.js?v=" . VERSION ?>"> </script>
        <script src="<?= APPURL . "/assets/js/chart/morris-chart/prettify.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/knob/knob.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/apex-chart/apex-chart.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/chart/apex-chart/stock-prices.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/prism/prism.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/clipboard/clipboard.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/counter/jquery.waypoints.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/counter/jquery.counterup.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/counter/counter-custom.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/custom-card/custom-card.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/notify/bootstrap-notify.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/dashboard/default.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/notify/index.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/greeting.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/select2/select2.full.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/select2/select2-custom.js?v=" . VERSION ?>"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->

        <script src="<?= APPURL . "/assets/js/sweet-alert/sweetalert.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/sweet-alert/app.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/js/theme-customizer/customizer.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/script.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/custom.js?v=" . VERSION ?>"></script>
        <?php if ($page == "logotype") : ?>
            <script src="<?= APPURL . "/assets/ckfinder/ckfinder.js?v=" . VERSION ?>"></script>
        <?php endif ?>
        <!-- login js-->
        <!-- Plugin used-->
        <script>
            $(function() {
            Sub99.Settings();

            <?php if ($page == "logotype") : ?>
                Sub99.LogoType();
            <?php endif ?>
            });
        </script>
    </body>
</html>