<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
        <span class="app-brand-logo demo">
        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
            fill="#7367F0" />
            <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
            fill="#161616" />
            <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
            fill="#161616" />
            <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
            fill="#7367F0" />
        </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="main">Main</span>
        </li>
        <li class="menu-item">
            <a href="<?= APPURL . "/dashboard" ?>" class="menu-link">
            <i class="menu-icon tf-icons ti ti-smart-home"></i>
            <div data-i18n="Trang chủ">Trang chủ</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?= APPURL . "/pricing-details" ?>" class="menu-link">
            <i class="menu-icon ti ti-list"></i>
            <div data-i18n="Bảng giá dịch vụ">Bảng giá dịch vụ</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?= APPURL . "/fluctuations" ?>" class="menu-link">
            <i class="menu-icon tf-icons ti ti-activity"></i>
            <div data-i18n="Biến động số dư">Biến động số dư</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?= APPURL . "/payment-history" ?>" class="menu-link">
            <i class="menu-icon tf-icons ti ti-credit-card"></i>
            <div data-i18n="Lịch sử nạp tiền">Lịch sử nạp tiền</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Tăng tương tác">Tăng tương tác</span>
        </li>
        <?php require(APPPATH . "/inc/group_orders.inc.php"); ?>
        <?php foreach ($group_orders as $group) : ?>
            <?php
                $subQuery = DB::table(TABLE_PREFIX . TABLE_SERVICE_TITLES)
                ->select(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.title')
                ->where(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.service_id', '=', DB::raw("`" . TABLE_PREFIX . TABLE_SERVICES . "`.`id`"))
                ->where(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.site_id', '=', DB::raw($AuthSite->get("id")));

                $servicesNav = DB::table(TABLE_PREFIX . TABLE_SERVICES)
                ->where(TABLE_PREFIX . TABLE_SERVICES . ".is_public", "=", 1)
                ->where(TABLE_PREFIX . TABLE_SERVICES . ".group", "=", $group["idname"])
                ->select([
                    TABLE_PREFIX . TABLE_SERVICES . ".title",
                    TABLE_PREFIX . TABLE_SERVICES . ".id",
                    TABLE_PREFIX . TABLE_SERVICES . ".idname",
                    TABLE_PREFIX . TABLE_SERVICES . ".icon",
                ])
                ->select(DB::subQuery($subQuery, 'title_extra'))
                ->get();

                $navs = [];
                foreach ($servicesNav as $service) {
                $title = explode(" ", $service->title, 2);
                if (count($title) == 2) {
                    if (!isset($navs[$title[0]])) {
                    $navs[$title[0]] = [];
                    }
                    $navs[$title[0]][] = $service;
                } else {
                    $navs['Unknown'][] = $service;
                }
                }
            ?>
            <?php if (count($navs) > 0) : ?>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-brand-facebook"></i>
                        <div data-i18n="<?= $group["title"] ?>"><?= $group["title"] ?></div>
                    </a>
                    <?php if (count($navs) > 0) : ?>
                        <ul class="menu-sub">
                        <?php foreach ($navs as $key => $services) : ?>
                            <li class="menu-item">
                                <li class="menu-item">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <div data-i18n="<?= $group["title"] . " " . $key ?>"><?= $group["title"] . " " . $key ?></div>
                                    </a>
                                    <?php if (count($services) > 0) : ?>
                                        <ul class="menu-sub">
                                            <?php foreach ($services as $key2 => $service) : ?>
                                                <li class="menu-item">
                                                    <a href="<?= APPURL . "/orders/" . $group["idname"] . "/" . $service->idname ?>" class="menu-link">
                                                        <div data-i18n="<?= $service->title_extra ? $service->title_extra : $service->title  ?>"><?= $service->title_extra ? $service->title_extra : $service->title  ?></div>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>


        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Quản trị">Quản trị</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shield"></i>
                <div data-i18n="Quản trị">Quản trị</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= APPURL . "/users" ?>" class="menu-link">
                        <div data-i18n="Người dùng">Người dùng</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= APPURL . "/services" ?>" class="menu-link">
                        <div data-i18n="Dịch vụ">Dịch vụ</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= APPURL . "/servers" ?>" class="menu-link">
                        <div data-i18n="Servers">Servers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= APPURL . "/roles" ?>" class="menu-link">
                        <div data-i18n="Cấp bậc">Cấp bậc</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= APPURL . "/sites" ?>" class="menu-link">
                        <div data-i18n="Site đại lý">Site đại lý</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Hệ thống">Hệ thống</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Hệ thống">Hệ thống</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Giao diện">Giao diện</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= APPURL . "/themes" ?>" class="menu-link">
                                    <div data-i18n="Giao diện đại lý">Giao diện đại lý</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/themes/effects" ?>" class="menu-link">
                                    <div data-i18n="Hiệu ứng">Hiệu ứng</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Cài đặt">Cài đặt</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/site" ?>" class="menu-link">
                                    <div data-i18n="Site">Site</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/logotype" ?>" class="menu-link">
                                    <div data-i18n="Logo">Logo</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/other" ?>" class="menu-link">
                                    <div data-i18n="Khác">Khác</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/order" ?>" class="menu-link">
                                    <div data-i18n="Đơn">Đơn</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Tích hợp">Tích hợp</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/topup" ?>" class="menu-link">
                                    <div data-i18n="Nạp tiền">Nạp tiền</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/smtp" ?>" class="menu-link">
                                    <div data-i18n="SMTP">SMTP</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/settings/recaptcha" ?>" class="menu-link">
                                    <div data-i18n="ReCaptcha">ReCaptcha</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </li>
            </ul>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Proxy">Proxy</span>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-link"></i>
                <div data-i18n="Proxy">Proxy</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Proxy xoay">Proxy xoay</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= APPURL . "/proxy/shoplike" ?>" class="menu-link">
                                    <div data-i18n="Shoplike">Shoplike</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Proxy tĩnh">Proxy tĩnh</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="<?= APPURL . "/proxy/proxyfb" ?>" class="menu-link">
                                    <div data-i18n="ProxyFB">ProxyFB</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= APPURL . "/proxy/vitechcheap" ?>" class="menu-link">
                                    <div data-i18n="VitechCheap">VitechCheap</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </li>
            </ul>
        </li>
    </ul>
</aside>