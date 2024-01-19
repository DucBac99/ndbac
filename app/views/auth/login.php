
<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?= APPURL . "/assets/" ?>"
    data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>Đăng nhập | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

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

        <!-- Core CSS -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/rtl/core.css?v=" . VERSION ?>" class="template-customizer-core-css" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/rtl/theme-default.css?v=" . VERSION ?>" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/css/demo.css?v=" . VERSION ?>" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/node-waves/node-waves.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css?v=" . VERSION ?>" />
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/typeahead-js/typeahead.css?v=" . VERSION ?>" />
        <!-- Vendor -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/libs/@form-validation/umd/styles/index.min.css?v=" . VERSION ?>" />

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="<?= APPURL . "/assets/vendor/css/pages/page-auth.css?v=" . VERSION ?>" />

        <!-- Helpers -->
        <script src="<?= APPURL . "/assets/vendor/js/helpers.js?v=" . VERSION ?>"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="<?= APPURL . "/assets/vendor/js/template-customizer.js?v=" . VERSION ?>"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="<?= APPURL . "/assets/js/config.js?v=" . VERSION ?>"></script>
    </head>

    <body>
        <!-- Content -->

        <div class="authentication-wrapper authentication-cover authentication-bg">
            <div class="authentication-inner row">
                <!-- /Left Text -->
                <div class="d-none d-lg-flex col-lg-7 p-0">
                    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                        <img
                        src="../../assets/img/illustrations/auth-login-illustration-light.png"
                        alt="auth-login-cover"
                        class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

                        <img
                        src="../../assets/img/illustrations/bg-shape-image-light.png"
                        alt="auth-login-cover"
                        class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png" />
                    </div>
                </div>
                <!-- /Left Text -->

                <!-- Login -->
                <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        <!-- Logo -->
                        <div class="app-brand mb-4">
                        <a href="index.html" class="app-brand-link gap-2">
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
                        </a>
                        </div>
                        <!-- /Logo -->
                        <h3 class="mb-1">Chào mừng đến với <?= site_settings("site_name") ?>! 👋</h3>
                        <p class="mb-4">Hãy đăng nhập vào tài khoản của bạn!</p>

                        <form id="formAuthentication" class="mb-3" action="<?= APPURL . "/login" ?>" method="POST">
                            <input type="hidden" name="action" value="login">
                            <?php if (!empty($FormError)) : ?>
                                <div class="alert alert-danger alert-icon-start fade show">
                                    <span class="alert-icon bg-danger text-white">
                                    <i class="ph-x-circle"></i>
                                    </span>
                                    <span class="fw-semibold"><?= $FormError ?>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập địa chỉ email" value="<?= Input::post("username") ?>">
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Mật khẩu</label>
                                <a href="<?= APPURL . "/recovery" ?>">
                                    <small>Quên mật khẩu?</small>
                                </a>
                                </div>
                                <div class="input-group input-group-merge">
                                <input type="password" name="password" class="form-control pe-5" id="password-input" placeholder="Nhập mật khẩu của bạn">
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-check" name="remember" value="1" <?= Input::post("remember") ? "checked" : "" ?>>
                                <label class="form-check-label" for="remember-me"> Ghi nhớ đăng nhập </label>
                                </div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">Đăng nhập</button>
                        </form>

                        <p class="text-center">
                        <span>Bạn chưa có tài khoản?</span>
                        <a href="<?= APPURL . "/signup" ?>">
                            <span>Đăng ký</span>
                        </a>
                        </p>

                        <div class="divider my-4">
                        <div class="divider-text">Đăng nhập với</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons fa-brands fa-google fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>

        <!-- / Content -->

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
        <script src="<?= APPURL . "/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js?v=" . VERSION ?>"></script>
        <script src="<?= APPURL . "/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js?v=" . VERSION ?>"></script>

        <!-- Main JS -->
        <script src="<?= APPURL . "/assets/js/main.js?v=" . VERSION ?>"></script>

        <!-- Page JS -->
        <script src="<?= APPURL . "/assets/js/pages-auth.js?v=" . VERSION ?>"></script>
    </body>
</html>
