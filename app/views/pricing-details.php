
<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?= APPURL . "/assets/" ?>" data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

            <title>Bảng giá chi tiết | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="<?= APPURL . "/assets/img/favicon/favicon.ico" ?>" />

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

                <?php require_once(APPPATH . '/views/fragments/pricing-details.fragment.php'); ?>
                <!-- / Content -->

                <!-- Footer -->
                <?php require_once(APPPATH . '/views/components/footer.component.php'); ?>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
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
        <script>
            $(function() {
                function get_detail_price(card, server_id) {
                    addLoading(card);
                    $.ajax({
                    url: "/pricing-details",
                    type: 'POST',
                    dataType: "jsonp",
                    data: {
                        action: 'get',
                        server_id: server_id,
                        group: "facebook",
                    },
                    error: function() {
                        removeLoading(card);
                        Swal.fire({
                            title: "Error",
                            text: "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-primary waves-effect waves-light"
                            },
                            buttonsStyling: !1
                        });
                    },
                    success: function(resp) {
                        removeLoading(card);
                        if (resp.result) {
                        var roles = resp.roles;
                        var services = resp.services;
                        var base_url = resp.base_url;
                        var html = `
                        <div class="col-xxl-4 col-md-6 col-sm-12">
                            <div class="mb-4">
                            <div class="fw-bold border-bottom pb-2 mt-1 mb-2"><i class="ti ti-folder me-2 text-primary"></i> Member</div>
                            <div class="list-group list-group-sm list-group-borderless">
                            ${services.map(item => {
                                return `<a href="${base_url}/${item.idname}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center rounded">
                                    <i class="${item.icon} me-2"></i> ${item.title} <strong> ( ${item?.price && item.price.default ? item.price.default.format() : 0}đ ) ${ item.speed ? `- Tốc độ: ${item.speed}` : ''}</strong>
                                    ${item?.options?.maintain ? '<span class="font-size-12 badge bg-secondary ms-auto">Bảo trì</span>' : !item?.options?.public ? '<span class="font-size-12 badge bg-danger ms-auto">Đóng</span>' : '<span class="font-size-12 badge bg-success ms-auto">Hoạt động</span>'}
                                </a>`
                            }).join("\n")}
                            </div>
                            </div>
                        </div>`;

                        for (let i = 0; i < roles.length; i++) {
                            const element = roles[i];
                            html += `<div class="col-xxl-4 col-md-6 col-sm-12">
                                <div class="mb-4">
                                    <div class="fw-bold border-bottom pb-2 mt-1 mb-2"><i class=" ti ti-folder me-2 text-${element.color}"></i> ${element.title}  - Nạp Tối Thiểu : ${element.amount ? parseInt(element.amount).format() : 0}đ </div>
                                    <div class="list-group list-group-sm list-group-borderless">
                                    ${services.map(item => {
                                    return `<a href="${base_url}/${item.idname}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center rounded">
                                        <i class="${item.icon} me-2"></i> ${item.title} <strong> ( ${item?.price && item.price[element.idname] ? item.price[element.idname].format() : 0}đ ) ${ item.speed ? `- Tốc độ: ${item.speed}` : ''}</strong>
                                        ${item?.options?.maintain ? '<span class="font-size-12 badge bg-secondary ms-auto">Bảo trì</span>' : !item?.options?.public ? '<span class="font-size-12 badge bg-danger ms-auto">Đóng</span>' : '<span class="font-size-12 badge bg-success ms-auto">Hoạt động</span>'}
                                        </a>`
                                    }).join("\n")}
                                    </div>
                                </div>
                            </div>`;
                        }
                        card.find(".card-body").html(`<div class="row">${html}</div>`);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: resp.msg,
                                icon: "error",
                                customClass: {
                                    confirmButton: "btn btn-primary waves-effect waves-light"
                                },
                                buttonsStyling: !1
                            });
                        }
                    },
                    });
                }

                get_detail_price($('#server-tab-1'), 1);

                $('.select').on('change', function(e) {
                    $href_active = $('a[class="nav-link active"][role="tab"]');
                    get_detail_price($($href_active.attr("href")), $href_active.data("id"));
                });

                $('.nav-pills a').click(function() {
                    $this = $(this);
                    var cardId = $this.attr("href");
                    var card = $(cardId);
                    $this.tab('show');
                    get_detail_price(card, $this.data("id"));
                })
            })
        </script>
    </body>
</html>