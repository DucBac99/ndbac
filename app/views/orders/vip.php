
<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?= APPURL . "/assets/" ?>" data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

            <title><?= $Service->get("title") ?> | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="<?= APPURL . "/assets/img/favicon/favicon.ico" ?>"/>

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

                <?php require_once(APPPATH . '/views/orders/' . $page . '.order.php'); ?>
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
        <script src="<?= APPURL . "/assets/js/pages/orders.js?v=" . VERSION ?>"></script>
        <script type="text/javascript">
            $(function() {
            Sub99.editOrder();
            var is_admin = <?= $AuthUser->isAdmin() ? "true" : "false" ?>;

            $('.select2').on('change', function(e) {
                table_data.ajax.reload();
            });

            $table = $("#orders_table");
            $card = $table.parent(".card:first");
            table_data = $table.on("preXhr.dt", function(t, a, e) {
                addLoading($table);
            }).DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                url: $table.data("url"),
                data: function(d) {
                    d.search = d.search.value;
                    d.order = {
                    column: d.columns[d.order[0].column].data,
                    dir: d.order[0].dir
                    }
                    d.status = $("select[name=status] option:selected").val();
                    d.site_id = $("select[name=site_id] option:selected").val();
                    d.server_id = $("select[name=server_id] option:selected").val();
                    delete d.columns;
                },
                dataFilter: function(d) {
                    var json = JSON.parse(d);
                    var data = {};
                    if (json.result) {
                    for (const i in json.data) {
                        json.data[i].DT_RowAttr = {
                        "data-id": json.data[i].id,
                        }
                        json.data[i].month = parseInt(json.data[i].month, 10);
                        json.data[i].id = parseInt(json.data[i].id, 10);
                        json.data[i].order_amount = parseInt(json.data[i].order_amount, 10);
                        json.data[i].expired_at = moment(json.data[i].expired_at).format('YYYY-MM-DD HH:mm:ss');
                        json.data[i].created_at = moment(json.data[i].created_at).format('YYYY-MM-DD HH:mm:ss');
                    }
                    data.data = json.data;
                    data.recordsTotal = json.summary.total_count;
                    data.recordsFiltered = json.summary.total_count;
                    } else {
                    data.data = [];
                    data.recordsTotal = 0;
                    data.recordsFiltered = 0;
                    Swal.fire('Oops...', json.msg, 'error')
                    }
                    return JSON.stringify(data);
                }
                },
                order: [
                [1, "desc"]
                ],
                pagingType: "simple",
                pageLength: 20,
                lengthMenu: [
                [20, 50, 100, 200],
                [20, 50, 100, 200]
                ],
                columnDefs: [{
                orderable: false,
                data: "id",
                render: function(t, a, e) {
                    return `  <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="customCheck" id="data_id_${e.id}" value="${e.id}">
                                            <span class="form-check-label">&nbsp;</span>
                                        </label>`
                },
                }, {
                data: "id",
                render: function(t, a, e) {
                    return `#${e.id}`
                },
                }, {
                orderable: false,
                data: "seeding_uid",
                render: function(t, a, e) {
                    return `<a target="_blank" href="https://www.facebook.com/${t}">${t}</a>`;
                },
                }, {
                data: "month",
                render: function(t, a, e) {
                    return `${t.format()}`;
                },
                }, {
                data: "order_amount",
                render: function(t, a, e) {
                    return `${t.format()}`;
                },
                }, {
                data: "expired_at",
                render: function(t, a, e) {
                    return `${t}`;
                },
                }, {
                orderable: false,
                data: "status",
                render: function(t, a, e) {
                    var color = "success";
                    switch (t) {
                    case 'COMPLETED':
                        color = 'success';
                        break;
                    case 'PENDING':
                        color = 'info';
                        break;
                    case 'PENDING_REFUND':
                        color = 'info';
                        break;
                    case 'CHECKING_COMPLETED':
                        color = 'purple';
                        break;
                    case 'REFUND':
                        color = 'danger';
                        break;
                    }
                    return `<span class="badge bg-${color} bg-opacity-10 text-${color}">${t}</span>`;

                },
                }, {
                orderable: false,
                data: "note",
                }, {
                orderable: false,
                data: "note_extra",
                }, {
                data: "created_at",
                }, {
                data: "email",
                }, {
                orderable: false,
                data: "id",
                render: function(t, a, e) {
                    return `<div class="d-flex gap-2">
                                <div class="price">
                                    <button data-url="${$table.data("url")}/${t}" data-id="${t}" class="btn btn-icon btn-sm btn-sm waves-effect waves-light btn-outline-indigo btn-refund-order" data-bs-popup="tooltip" title="Hoàn"><i class="ph-arrow-u-down-left"></i></button>
                                </div>
                                <?php if ($AuthUser->isAdmin()) : ?>
                                <div class="edit">
                                    <button data-url="${$table.data("url")}" data-id="${t}" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-primary edit-order-btn" data-bs-popup="tooltip" title="Sửa"><i class="bx bx-edit font-size-16 align-middle  "></i></button>
                                </div>
                                <div class="remove">
                                    <button class="btn btn-icon btn-sm btn-sm waves-effect waves-light btn-outline-danger js-remove-item" data-table="#orders_table" data-url="${$table.data("url")}" data-id="${t}" data-bs-popup="tooltip" title="Xoá"><i class="bx bx-trash font-size-16 align-middle "></i></button>
                                </div>
                                <?php endif; ?>
                            </div>`;
                },
                }].map((item, index) => {
                item.targets = index;
                return item;
                }),
                drawCallback: function(e) {
                Sub99.initToolTips();
                if (table_data.data().length == 0) {
                    $(".next").addClass("disabled");
                }
                removeLoading($table);
                },
                initComplete: function(a, e) {
                $("#orders_table_filter input").unbind(), $("#orders_table_filter input").bind(
                    "keyup",
                    function(a) {
                    13 == a.keyCode && table_data.search(this.value).draw()
                    })
                }
            })
            })
        </script>
    </body>
</html>