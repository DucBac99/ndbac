<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= __("ReCaptcha") ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= __("ReCaptcha") ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row push">
                    <div class="col-lg-5">
                        <p class="fw-semibold">Hướng dẫn</label>
                        <p>
                            Bạn nên làm theo các bước sau để nhận các khóa cần thiết:
                        </p>
                        <ul class="field-tips">
                            <li class="mb-15">
                            Truy cập trang web reCAPTCHA của Google và tìm phần "Đăng ký trang web mới":<br>
                            <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>
                            </li>
                            <li class="mb-15">
                            Nhập bất kỳ văn bản nào trong trường "Nhãn"
                            </li>
                            <li class="mb-15">
                            Chọn "reCAPTCHA v2" làm loại khóa trang
                            </li>
                            <li class="mb-15">
                            Nhập địa chỉ sau vào trường "Miền" trong một dòng riêng lẻ: <br>
                            <strong><?= parse_url(APPURL, PHP_URL_HOST) ?></strong>
                            </li>
                            <li class="mb-15">
                            Sao chép và dán trang web và khóa bí mật
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-7 col-xl-5">
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
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Lưu thay đổi</button>
                </div>
            </form>
        </div>
        <!-- /Account -->
        </div>
    </div>
</div>