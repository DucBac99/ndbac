
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

      <title>Đặt lại mật khẩu | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

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
            <!-- Reset Password -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-4 p-sm-5">
                <div class="w-px-400 mx-auto">
                <?php if (empty($success)) : ?>
                    <h4 class="mb-1">Đặt lại mật khẩu🔒</h4>
                    <p class="mb-4">cho <span class="fw-medium"><?= $AuthUser->get("firstname") . " " . $AuthUser->get("lastname") ?></span></p>
                    <form id="formAuthentication" class="mb-3" action="<?= APPURL . "/recovery/" . $Route->params->id . "." . $Route->params->hash ?>" method="POST">
                        <input type="hidden" name="action" value="reset">

                        <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger alert-icon-start fade show">
                            <span class="alert-icon bg-danger text-white">
                            <i class="ph-x-circle"></i>
                            </span>
                            <span class="fw-semibold"> <?= $error ?>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Nhập mật khẩu mới</label>
                            <div class="input-group input-group-merge">
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới">
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="confirm-password">Nhập mật khẩu xác nhận</label>
                            <div class="input-group input-group-merge">
                            <input type="password" name="password-confirm" class="form-control" placeholder="Nhập mật khẩu xác nhận">
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100 mb-3">Đặt lại mật khẩu</button>
                    </form>
                <?php else : ?>
                    <div class="text-center">
                        <div class="avatar-lg mx-auto">
                        <div class="avatar-title rounded-circle bg-light">
                            <i class="bx bxs-envelope h2 mb-0 text-primary"></i>
                        </div>
                        </div>
                        <div class="p-2 mt-4">
                        <h4>Thành công</h4>
                        <p>Bạn đã đổi thành công mật khẩu của mình. Hãy đăng nhập lại</p>
                        <div class="mt-4">
                            <a href="<?= APPURL ?>" class="btn btn-primary w-10">Về trang chủ</a>
                        </div>
                        </div>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <!-- /Reset Password -->
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
