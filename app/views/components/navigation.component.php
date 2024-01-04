<header class="main-nav">
    <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/logo.png" alt=""></a></div>
    <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/logo-icon.png" alt=""></a></div>
    <div class="morden-logo"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/morden-logo.png" alt=""></a></div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="menu-title text-dark" data-key="t-menu">Main</li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="<?= APPURL . "/dashboard" ?>"><i data-feather="home"></i><span>Trang chủ</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="<?= APPURL . "/pricing-details" ?>"><i data-feather="file-text"></i><span>Bảng giá dịch vụ</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="<?= APPURL . "/fluctuations" ?>"><i data-feather="activity"> </i><span>Biến động số dư</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="<?= APPURL . "/payment-history" ?>"><i data-feather="credit-card"> </i><span>Lịch sử nạp tiền</span></a></li>
                    <?php if ($AuthUser->get("has_analytics")) : ?>
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="<?= APPURL . "/analytics" ?>"><i data-feather="monitor"> </i><span>Thống kê tương tác</span></a></li>
                    <?php endif ?>

                    <li class="menu-title text-dark" data-key="t-apps">Tăng tương tác</li>
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
                        <li class="mega-menu">
                            <a class="nav-link menu-title active" href="#">
                                <i data-feather="facebook"></i>
                                <span><?= $group["title"] ?></span>
                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                </div>
                            </a>
                            <?php if (count($navs) > 0) : ?>
                                <div class="mega-menu-container menu-content">
                                    <div class="container">
                                        <div class="row">
                                        <?php foreach ($navs as $key => $services) : ?>
                                            <div class="col mega-box">
                                                <div class="link-section">
                                                    <div class="submenu-title">
                                                        <h5><?= $group["title"] . " " . $key ?></h5>
                                                        <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                        </div>
                                                    </div>
                                                    <?php if (count($services) > 0) : ?>
                                                        <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                            <?php foreach ($services as $key2 => $service) : ?>
                                                                <li><a href="<?= APPURL . "/orders/" . $group["idname"] . "/" . $service->idname ?>"><?= $service->title_extra ? $service->title_extra : $service->title  ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                        <?php endif; ?>
                    <?php endforeach; ?>


                    <?php if ($AuthUser->isAdmin()) : ?>
                    <li class="menu-title text-dark" data-key="t-menu">Quản trị</li>
                    <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="settings"></i><span>Quản trị</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="<?= APPURL . "/users" ?>" data-key="t-users">Người dùng</a></li>
                            <li><a href="<?= APPURL . "/services" ?>" data-key="t-services">Dịch vụ</a></li>
                            <li><a href="<?= APPURL . "/servers" ?>" data-key="t-servers">Servers</a></li>
                            <li><a href="<?= APPURL . "/roles" ?>" data-key="t-roles">Cấp bậc</a></li>
                            <li><a href="<?= APPURL . "/sites" ?>" data-key="t-sites">Site đại lý</a></li>
                        </ul>
                    </li>

                    <li class="menu-title text-dark" data-key="t-settings">Hệ thống</li>
                    <li class="mega-menu">
                        <a class="nav-link menu-title active" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg><span>Hệ thống</span>
                            <div class="according-menu"><i class="fa fa-caret-right"></i>
                            </div>
                        </a>
                        <div class="mega-menu-container menu-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Giao diện</h5>
                                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                </div>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                <li><a href="<?= APPURL . "/themes" ?>" data-key="themes">Giao diện đại lý</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/themes/effects" ?>" data-key="effects">Hiệu ứng</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5> Cài đặt</h5>
                                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                </div>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                <li><a href="<?= APPURL . "/settings/site" ?>" data-key="site" target="_blank">Site</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/settings/logotype" ?>" data-key="logotype" target="_blank">Logo</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/settings/other" ?>" data-key="other" target="_blank">Khác</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/settings/order" ?>" data-key="other" target="_blank">Đơn</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Tích hợp</h5>
                                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                </div>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                <li><a href="<?= APPURL . "/settings/topup" ?>" data-key="topup">Nạp tiền</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/settings/smtp" ?>" data-key="smtp">SMTP</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/settings/recaptcha" ?>" data-key="recaptcha">ReCaptcha</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="menu-title text-dark" data-key="t-menu">Proxy</li>
                    <li class="mega-menu">
                        <a class="nav-link menu-title active" href="#">
                        <i data-feather="link"></i><span>Proxy</span>
                            <div class="according-menu"><i class="fa fa-caret-right"></i>
                            </div>
                        </a>
                        <div class="mega-menu-container menu-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Proxy xoay</h5>
                                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                </div>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                <li><a href="<?= APPURL . "/proxy/shoplike" ?>" data-key="shoplike">Shoplike</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5> Proxy tĩnh</h5>
                                                <div class="according-menu"><i class="fa fa-caret-right"></i>
                                                </div>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu" style="display: none;">
                                                <li><a href="<?= APPURL . "/proxy/proxyfb" ?>" data-key="proxyfb" target="_blank">FroxyFB</a>
                                                </li>
                                                <li><a href="<?= APPURL . "/proxy/vitechcheap" ?>" data-key="vitechcheap" target="_blank">VitechCheap</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>