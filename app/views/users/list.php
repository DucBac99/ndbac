
<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

            <title>Người dùng | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
            rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/fonts/fontawesome.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/fonts/tabler-icons.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/fonts/flag-icons.css?v=" . VERSION ?>" />
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css"> -->


        <!-- Core CSS -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/rtl/core.css?v=" . VERSION ?>" class="template-customizer-core-css" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/rtl/theme-default.css?v=" . VERSION ?>" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/css/demo.css?v=" . VERSION ?>" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/node-waves/node-waves.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/typeahead-js/typeahead.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/apex-charts/apex-charts.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/sweetalert2/sweetalert2.css?v=" . VERSION ?>" />

        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css?v=" . VERSION ?>">
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css?v=" . VERSION ?>">
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css?v=" . VERSION ?>">


        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/select2/select2.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/bootstrap-select/bootstrap-select.css?v=" . VERSION ?>" />

        <!-- Page CSS -->

        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/pages/app-logistics-dashboard.css?v=" . VERSION ?>" />

        <!-- Helpers -->
        <script src="<?= APPURL . "/assets/vendor/js/helpers.js?v=" . VERSION ?>"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="<?= APPURL . "/assets/vendor/js/template-customizer.js?v=" . VERSION ?>"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="<?= APPURL . "/assets/js/config.js?v=" . VERSION ?>"></script>
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
            <!-- Menu -->

            <?php require_once(APPPATH . '/views/components/navigation.component.php'); ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php require_once(APPPATH . '/views/components/topbar.component.php'); ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                <!-- Content -->

                <?php require_once(APPPATH . '/views/users/fragments/users.fragment.php'); ?>
                <!-- / Content -->

                <!-- Footer -->
                <?php require_once(APPPATH . '/views/components/footer.component.php'); ?>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
                </div>

                <div id="modal_edit_balance" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content card">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title text-white">Primary header</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form class="js-ajax-form" action="<?= APPURL . "/users" ?>" method="POST">
                            <div class="modal-body">
                            <input type="hidden" name="action" value="change_balance">
                            <input type="hidden" name="user_id" value="0">

                            <div class="mb-3">
                                <p class="fw-semibold">Loại</label>
                                <select class="form-control select" name="type_change" data-search-enabled="false">
                                    <option value="+">Cộng tiền</option>
                                    <option value="-">Trừ tiền</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <p class="fw-semibold">Số tiền</label>
                                <input class="form-control" name="money" value="" type="number">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold form-label">Reference ID</label>
                                <input type="text" name="reference_id" value="" class="form-control" placeholder="Nhập Reference ID" required>
                                <div class="form-text">Đây là mã bill thanh toán của vcb. Hãy nhập chính xác tránh cộng đúp tiền</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="change_deposit" name="change_deposit" value="1">
                                <span class="form-check-label">Thay đổi luôn tổng nạp</span>
                                </label>
                                <div class="form-text">Khi tích thì sẽ tổng tiền nạp sẽ thay đổi theo</div>
                            </div>

                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                <div id="modal_edit_analytics" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content card">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title text-white">Cấu hình tương tác</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form class="js-ajax-form" action="<?= APPURL . "/users" ?>" method="POST">
                            <div class="modal-body">
                            <input type="hidden" name="action" value="change_analytics">

                            <div class="mb-3">
                                <label class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="has-analytics" name="has-analytics" value="1">
                                <span class="form-check-label">Cho phép xem thống kê tương tác</span>
                                </label>
                            </div>

                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary js-change-analytics" data-table="#users_table" data-url="<?= APPURL . "/users" ?>">Save changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                <div id="modal_gen_qr" class="modal fade" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content card">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title text-white">QR Code đăng nhập</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">
                            <h6 class="fw-semibold" id="title_email">Text in a modal</h6>
                            <img src="" title="Login By QR Code" />
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->

        <script src="<?= APPURL . "/assets/vendor/libs/jquery/jquery.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/popper/popper.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/js/bootstrap.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/node-waves/node-waves.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/hammer/hammer.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/i18n/i18n.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/typeahead-js/typeahead.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/js/menu.js?v=" . VERSION ?>"></script>

        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="<?= APPURL . "/assets/vendor/libs/apex-charts/apexcharts.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/vendor/libs/sweetalert2/sweetalert2.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/extended-ui-sweetalert2.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js?v=" . VERSION ?>"></script>
        <!-- <script src="<?= APPURL . "/assets/js/tables-datatables-basic.js?v=" . VERSION ?>"></script> -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

        <!-- <script src="<?= APPURL . "/assets/js/forms-selects.js?v=" . VERSION ?>"></script> -->
        <script src="<?= APPURL . "/assets/vendor/libs/select2/select2.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/bootstrap-select/bootstrap-select.js?v=" . VERSION ?>"></script>

        <!-- Main JS -->
        <script src="<?= APPURL . "/assets/js/main.js?v=" . VERSION ?>"></script>

        <!-- Page JS -->
        <script src="<?= APPURL . "/assets/js/app-logistics-dashboard.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/js/custom.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/pages/users.js?v=" . VERSION ?>"></script>
    </body>
</html>