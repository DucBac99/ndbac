
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
        <title>Roles | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>
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
        <link rel="stylesheet" type="text/css" href="<?= APPURL . "/assets/css/vendors/datatables.css?v=" . VERSION ?>">
        <style>
            .btn-air-danger {

            }
        </style>
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
            <?php require_once(APPPATH . '/views/fragments/roles.fragment.php'); ?>
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
        <!-- <script src="<?= APPURL . "/assets/js/dashboard/default.js?v=" . VERSION ?>"></script> -->
        <script src="<?= APPURL . "/assets/js/notify/index.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/greeting.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/select2/select2.full.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/select2/select2-custom.js?v=" . VERSION ?>"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->

        <script src="<?= APPURL . "/assets/js/sweet-alert/sweetalert.min.js?v=" . VERSION ?>"></script>
        <!-- <script src="<?= APPURL . "/assets/js/sweet-alert/app.js?v=" . VERSION ?>"></script> -->

        <!-- datatable js-->
        <script src="<?= APPURL . "/assets/js/datatable/datatables/jquery.dataTables.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/datatable/datatables/datatable.custom.js?v=" . VERSION ?>"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

        <script src="<?= APPURL . "/assets/js/theme-customizer/customizer.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/script.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/custom.js?v=" . VERSION ?>"></script>
        <!-- login js-->
        <!-- Plugin used-->
        <script type="text/javascript">
            $(function() {
                $('.select').on('change', function(e) {
                    table_data.ajax.reload();
                });

                $table = $("#roles_table");
                $card = $table.parents(".card:first");
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
                        d.site_id = $("select[name=site_id] option:selected").val();
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
                            json.data[i].id = parseInt(json.data[i].id);
                            json.data[i].amount = parseInt(json.data[i].amount);
                            json.data[i].updated_at = moment.utc(json.data[i].updated_at).local().format('YYYY-MM-DD HH:mm:ss');
                        }
                        data.data = json.data;
                        data.recordsTotal = 5000000;
                        data.recordsFiltered = 5000000;
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
                    oLanguage: {
                        sInfo: "",
                    },
                    pagingType: "simple",
                    pageLength: 20,
                    lengthMenu: [
                        [20, 30, 40, 50],
                        [20, 30, 40, 50]
                    ],
                    columnDefs: [{
                        orderable: false,
                        data: "id",
                        render: function(t, a, e) {
                            return `  <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="customCheck" id="data_id_${e.id}" value="${e.id}" style="width: 20px; height: 20px">
                                                    <span class="form-check-label">&nbsp;</span>
                                                </label>`
                        },
                    }, {
                    data: "id",
                    render: function(t, a, e) {
                        return `#${e.id}`
                    },
                    }, {
                        data: "idname",
                    }, {
                        data: "domain",
                        render: function(t, a, e) {
                            return `<a target="_blank" href="http://${t}">${t}</a>`
                        },
                    }, {
                        data: "title",
                    }, {
                        data: "color",
                        render: function(t, a, e) {
                            return `<span class="badge badge-${t}">${t}</span>`
                        },
                    }, {
                        data: "amount",
                        render: function(t, a, e) {
                            return `${t.format()}`
                        },
                    }, {
                        data: "updated_at",
                    }, {
                        orderable: false,
                        data: "id",
                        render: function(t, a, e) {
                            return `<ul class="d-flex justify-content-between list-unstyled">
                                        <li class="edit">
                                            <a href="${$table.data("url")}/${t}" class="btn btn-pill btn-outline-primary btn-air-primary btn-xs edit-item-btn" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M4 22H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M13.8881 3.66293L14.6296 2.92142C15.8581 1.69286 17.85 1.69286 19.0786 2.92142C20.3071 4.14999 20.3071 6.14188 19.0786 7.37044L18.3371 8.11195M13.8881 3.66293C13.8881 3.66293 13.9807 5.23862 15.3711 6.62894C16.7614 8.01926 18.3371 8.11195 18.3371 8.11195M13.8881 3.66293L7.07106 10.4799C6.60933 10.9416 6.37846 11.1725 6.17992 11.4271C5.94571 11.7273 5.74491 12.0522 5.58107 12.396C5.44219 12.6874 5.33894 12.9972 5.13245 13.6167L4.25745 16.2417M18.3371 8.11195L11.5201 14.9289C11.0584 15.3907 10.8275 15.6215 10.5729 15.8201C10.2727 16.0543 9.94775 16.2551 9.60398 16.4189C9.31256 16.5578 9.00282 16.6611 8.38334 16.8675L5.75834 17.7426M5.75834 17.7426L5.11667 17.9564C4.81182 18.0581 4.47573 17.9787 4.2485 17.7515C4.02128 17.5243 3.94194 17.1882 4.04356 16.8833L4.25745 16.2417M5.75834 17.7426L4.25745 16.2417" stroke="currentColor" stroke-width="1.5"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li class="remove">
                                            <button type="button" class="btn btn-pill btn-outline-primary btn-air-primary btn-xs btn-link js-remove-item" data-url="${$table.data("url")}/${t}" data-id="${t}" data-table="#users_table" data-bs-toggle="tooltip" title="Xoá">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                                    <path d="M20.5001 6H3.5" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M9.5 11L10 16" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M14.5 11L14 16" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="var(--bs-danger)" stroke-width="1.5"/>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>`;
                        },
                    }].map((item, index) => {
                        item.targets = index;
                        return item;
                    }),
                    drawCallback: function() {
                        Sub99.initToolTips();
                        if (table_data.data().length == 0) {
                            $(".next").addClass("disabled");
                        }
                        removeLoading($table);
                    },
                    initComplete: function(a, e) {
                        $("#roles_table_filter input").unbind(), $("#roles_table_filter input").bind("keyup", function(a) {
                            13 == a.keyCode && table_data.search(this.value).draw()
                        })
                    }
                })
            })
        </script>
    </body>
</html>