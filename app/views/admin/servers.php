
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Máy chủ dịch vụ | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" type="image/x-icon" href="favicon.png" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?= APPURL . "/assets/css/perfect-scrollbar.min.css?v=" . VERSION ?>" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?= APPURL . "/assets/css/style.css?v=" . VERSION ?>" />
        <link defer rel="stylesheet" type="text/css" media="screen" href="<?= APPURL . "/assets/css/animate.css?v=" . VERSION ?>" />
        <script src="<?= APPURL . "/assets/js/perfect-scrollbar.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/popper.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/tippy-bundle.umd.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/sweetalert.min.js?v=" . VERSION ?>"></script>
    </head>

    <body
        x-data="main"
        class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased"
        :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]"
    >
        <!-- sidebar menu overlay -->
        <div x-cloak class="fixed inset-0 z-50 bg-[black]/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>

        <!-- screen loader -->
        <div class="screen_loader animate__animated fixed inset-0 z-[60] grid place-content-center bg-[#fafafa] dark:bg-[#060818]">
            <svg width="64" height="64" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
                <path
                    d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z"
                >
                    <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="-360 67 67" dur="2.5s" repeatCount="indefinite" />
                </path>
                <path
                    d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z"
                >
                    <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="360 67 67" dur="8s" repeatCount="indefinite" />
                </path>
            </svg>
        </div>

        <!-- scroll to top button -->
        <div class="fixed bottom-6 z-50 ltr:right-6 rtl:left-6" x-data="scrollToTop">
            <template x-if="showTopButton">
                <button
                    type="button"
                    class="btn btn-outline-primary animate-pulse rounded-full bg-[#fafafa] p-2 dark:bg-[#060818] dark:hover:bg-primary"
                    @click="goToTop"
                >
                    <svg width="24" height="24" class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            opacity="0.5"
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z"
                            fill="currentColor"
                        />
                        <path
                            d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z"
                            fill="currentColor"
                        />
                    </svg>
                </button>
            </template>
        </div>

        <!-- start theme customizer section -->
        <?php require_once(APPPATH . '/views/components/configurator.component.php'); ?>
        <!-- end theme customizer section -->

        <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
            <!-- start sidebar section -->
            <?php require_once(APPPATH . '/views/components/navigation.component.php'); ?>
            <!-- end sidebar section -->

            <div class="main-content flex flex-col min-h-screen">
                <!-- start header section -->
                <?php require_once(APPPATH . '/views/components/topbar.component.php'); ?>
                <!-- end header section -->

                <?php require_once(APPPATH . '/views/admin/fragments/servers.fragment.php'); ?>

                <!-- start footer section -->
                <?php require_once(APPPATH . '/views/components/footer.component.php') ?>
                <!-- end footer section -->
            </div>
        </div>

        <script src="<?= APPURL . "/assets/libs/jquery/jquery.min.js?v=" . VERSION ?>"></script>

        <!-- Required datatable js -->
        <script src="<?= APPURL . "/assets/libs/datatables.net/js/jquery.dataTables.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/js/alpine-collaspe.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/alpine-persist.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine-ui.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine-focus.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/custom.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/apexcharts.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/js/pages/base.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/pages/custom.js?v=" . VERSION ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

        <script type="text/javascript">
        $(function() {
        $table = $("#servers_table");
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
                    json.data[i].created_at = moment.utc(json.data[i].created_at).local().format('YYYY-MM-DD HH:mm:ss');
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
            data: "name",
            }, {
            data: "api_url",
            render: function(t, a, e) {
                return `<a target="_blank" href="${t}">${t}</a>`
            },
            }, {
            data: "created_at",
            }, {
            orderable: false,
            data: "id",
            render: function(t, a, e) {
                return `<div class="relative flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]">
                            <div class="edit">
                                <a href="${$table.data("url")}/${t}" class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60" data-bs-popup="tooltip" title="Sửa">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5" stroke="#17a2b8" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M16.652 3.45506L17.3009 2.80624C18.3759 1.73125 20.1188 1.73125 21.1938 2.80624C22.2687 3.88124 22.2687 5.62415 21.1938 6.69914L20.5449 7.34795M16.652 3.45506C16.652 3.45506 16.7331 4.83379 17.9497 6.05032C19.1662 7.26685 20.5449 7.34795 20.5449 7.34795M16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9M20.5449 7.34795L14.5801 13.3128C14.1761 13.7168 13.9741 13.9188 13.7513 14.0926C13.4886 14.2975 13.2043 14.4732 12.9035 14.6166C12.6485 14.7381 12.3775 14.8284 11.8354 15.0091L10.1 15.5876M10.1 15.5876L8.97709 15.9619C8.71035 16.0508 8.41626 15.9814 8.21744 15.7826C8.01862 15.5837 7.9492 15.2897 8.03811 15.0229L8.41242 13.9M10.1 15.5876L8.41242 13.9" stroke="#17a2b8" stroke-width="1.5"/>
                                </svg>
                                </a>
                            </div>
                            <div class="remove">
                                <button class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 js-remove-item" data-table="#servers_table" data-url="${$table.data("url")}" data-id="${t}" data-bs-popup="tooltip" title="Xoá">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.1709 4C9.58273 2.83481 10.694 2 12.0002 2C13.3064 2 14.4177 2.83481 14.8295 4" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M20.5001 6H3.5" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M9.5 11L10 16" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M14.5 11L14 16" stroke="#dc3545" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                </button>
                            </div>
                        </div>`;
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
            $("#servers_table_filter input").unbind(), $("#servers_table_filter input").bind("keyup", function(a) {
                13 == a.keyCode && table_data.search(this.value).draw()
            })
            }
        })
        })
    </script>
    </body>
</html>
