<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600"><?= __("ReCaptcha") ?></h3>
        </div>
        <div class="col-6">
            <div class="breadcrumb-sec">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item">Hệ thống</li>
                <li class="breadcrumb-item active">Cài đặt</li>
            </ul>
            </div>
        </div>
        </div>
    </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><?= __("ReCaptcha") ?></h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                    <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-lg-5">
                                    <p class="f-w-600">Hướng dẫn</label>
                                    <p>
                                        Bạn nên làm theo các bước sau để nhận các khóa cần thiết:
                                    </p>
                                    <ul>
                                        <li><i class="fa fa-caret-right txt-secondary m-r-10"></i>
                                            Truy cập trang web reCAPTCHA của Google và tìm phần "Đăng ký trang web mới":<br>
                                            <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>
                                        </li>
                                        <li><i class="fa fa-caret-right txt-secondary m-r-10"></i>
                                            Nhập bất kỳ văn bản nào trong trường "Nhãn"
                                        </li>
                                        <li><i class="fa fa-caret-right txt-secondary m-r-10"></i>
                                            Chọn "reCAPTCHA v2" làm loại khóa trang
                                        </li>
                                        <li><i class="fa fa-caret-right txt-secondary m-r-10"></i>
                                            Nhập địa chỉ sau vào trường "Miền" trong một dòng riêng lẻ: <br>
                                            <strong><?= parse_url(APPURL, PHP_URL_HOST) ?></strong>
                                        </li>
                                        <li><i class="fa fa-caret-right txt-secondary m-r-10"></i>
                                            Sao chép và dán trang web và khóa bí mật
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg col-xl-5">
                                    <div class="form-group mb-3">
                                        <p class="fw-semibold">Site key</label>
                                        <input class="form-control" name="site-key" type="text" value="<?= $AuthSite->get("options.recaptcha_site_key") ?>" maxlength="100">
                                    </div>
                                    <div class="form-group mb-3">
                                        <p class="fw-semibold">Secret key</label>
                                        <input class="form-control" name="secret-key" type="text" value="<?= $AuthSite->get("options.recaptcha_secret_key") ?>" maxlength="100">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" <?= $AuthSite->get("options.signup_recaptcha_verification") ? "checked" : "" ?> id="signup-recaptcha-verification" name="signup-recaptcha-verification" value="1">
                                            <span class="form-check-label">Bật Recaptcha trong trang đăng ký</span>
                                        </label>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" <?= $AuthSite->get("options.signin_recaptcha_verification") ? "checked" : "" ?> id="signin-recaptcha-verification" name="signin-recaptcha-verification" value="1">
                                            <span class="form-check-label">Bật Recaptcha trong trang đăng nhập</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>