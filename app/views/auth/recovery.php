
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
        <title>Khôi phục mật khẩu | <?= site_settings("site_name") . " - " . site_settings("site_slogan") ?></title>
        <!-- Google font-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
        <!-- Font Awesome-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/font-awesome.css">
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/icofont.css">
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/themify.css">
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/flag-icon.css">
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/feather-icon.css">
        <!-- Plugins css start-->
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="../assets/css/vendors/bootstrap.css">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
        <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="../assets/css/responsive.css">
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
        <section>         
        <div class="container-fluid p-0"> 
            <div class="row m-0">
                <div class="col-12 p-0">
                <?php if (empty($success)) : ?>
                    <div class="login-card">
                        <img class="img-fluid bg-img-cover" src="../assets/images/bg-login.jpg" alt="">
                        <div class="login-main"> 
                            <form action="<?= APPURL . "/recovery" ?>" method="POST" class="theme-form login-form">
                            <input type="hidden" name="action" value="recover">
                            <h4 class="mb-3">Khôi phục mật khẩu</h4>
                            <div class="alert alert-success">Chúng tôi sẽ gửi cho bạn hướng dẫn trong email khi bạn nhập đầy đủ thông tin bên dưới</div>

                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger alert-icon-start fade show">
                                    <span class="alert-icon bg-danger text-white">
                                        <i class="ph-x-circle"></i>
                                    </span>
                                    <span class="fw-semibold"> <?= $error ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                <input class="form-control" type="text" name="email" required="" placeholder="Test@gmail.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Đặt lại mật khẩu                          </button>
                            </div>
                            <p class="text-start">Bạn đã nhớ ra ?<a class="ms-2" href="<?= APPURL . "/login" ?>">Đăng nhập</a></p>
                            </form>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="text-center">
                        <div class="avatar-lg mx-auto">
                            <div class="avatar-title rounded-circle bg-light">
                            <i class="bx bx-mail-send h2 mb-0 text-primary"></i>
                            </div>
                        </div>
                        <div class="p-2 mt-4">
                            <h4>Thành công !</h4>
                            <p class="text-muted">Hướng dẫn đặt lại mật khẩu được gửi đến địa chỉ email của bạn.</p>
                            <div class="mt-4">
                            <a href="<?= APPURL ?>" class="btn btn-primary w-100">Về trang chủ</a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </div>
        </section>
        <!-- page-wrapper end-->
        <!-- latest jquery-->
        <script src="../assets/js/jquery-3.5.1.min.js"></script>
        <!-- feather icon js-->
        <script src="../assets/js/icons/feather-icon/feather.min.js"></script>
        <script src="../assets/js/icons/feather-icon/feather-icon.js"></script>
        <!-- Sidebar jquery-->
        <script src="../assets/js/sidebar-menu.js"></script>
        <script src="../assets/js/config.js">   </script>
        <!-- Bootstrap js-->
        <script src="../assets/js/bootstrap/popper.min.js"></script>
        <script src="../assets/js/bootstrap/bootstrap.min.js"></script>
        <!-- Plugins JS start-->
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="../assets/js/script.js"></script>
        <!-- login js-->
        <!-- Plugin used-->
        <!-- tap on top starts-->
        <div class="tap-top"><i class="icon-control-eject"></i></div>
        <!-- tap on tap ends-->
    </body>
</html>