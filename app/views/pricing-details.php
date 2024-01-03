
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Bảng giá chi tiết | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>
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

                <?php require_once(APPPATH . '/views/fragments/pricing-details.fragment.php'); ?>

                <!-- start footer section -->
                <?php require_once(APPPATH . '/views/components/footer.component.php') ?>
                <!-- end footer section -->
            </div>
        </div>

        <script src="<?= APPURL . "/assets/libs/jquery/jquery.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/alpine-collaspe.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/alpine-persist.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine-ui.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine-focus.min.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/alpine.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/libs/datatables.net/js/jquery.dataTables.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js?v=" . VERSION ?>"></script>

        <script src="<?= APPURL . "/assets/js/custom.js?v=" . VERSION ?>"></script>
        <script defer src="<?= APPURL . "/assets/js/apexcharts.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/pages/base.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/js/pages/custom.js?v=" . VERSION ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

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
                Swal.fire(
                "Error",
                "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                "error"
                );
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
                    <div class="fw-bold border-bottom pb-2 mt-1 mb-2"><i class="ph-folder me-2 text-primary"></i> Member</div>
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
                    <div class="fw-bold border-bottom pb-2 mt-1 mb-2"><i class="ph-folder me-2 text-${element.color}"></i> ${element.title}  - Nạp Tối Thiểu : ${element.amount ? parseInt(element.amount).format() : 0}đ </div>
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
                Swal.fire(
                    "Error",
                    resp.msg,
                    "error"
                );
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
