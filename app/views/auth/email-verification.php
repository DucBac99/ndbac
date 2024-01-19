
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

      <title>Xác minh Email | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>

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
                    src="../../assets/img/illustrations/auth-verify-email-illustration-light.png"
                    alt="auth-verify-email-cover"
                    class="img-fluid my-5 auth-illustration"
                    data-app-light-img="illustrations/auth-verify-email-illustration-light.png"
                    data-app-dark-img="illustrations/auth-verify-email-illustration-dark.png" />

                    <img
                    src="../../assets/img/illustrations/bg-shape-image-light.png"
                    alt="auth-verify-email-cover"
                    class="platform-bg"
                    data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png" />
                </div>
            </div>
            <!-- /Left Text -->

            <!--  Verify email -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-4 p-sm-5">
                <div class="w-px-400 mx-auto">
                    <h3 class="mb-1">Xác minh Email thành công ✉️</h3>
                    <p class="text-start mb-4">
                        Bạn đã xác minh thành công địa chỉ email của mình.
                    </p>
                    <a class="btn btn-primary w-100 mb-3" href="<?= APPURL ?>"> Về trang chủ </a>
                </div>
            </div>
            <!-- / Verify email -->
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

    <!-- Main JS -->
    <script src="<?= APPURL . "/assets/js/main.js?v=" . VERSION ?>"></script>

    <!-- Page JS -->
  </body>
</html>
