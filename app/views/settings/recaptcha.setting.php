<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= __("ReCaptcha") ?></h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/settings/" . $page ?>" method="POST">
            <input type="hidden" name="action" value="save">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <h5 class="mb-5 text-lg font-semibold dark:text-white-light">Hướng dẫn</h5>
                    <p>Bạn nên làm theo các bước sau để nhận các khóa cần thiết:</p>
                    <ul class="list-inside list-disc space-y-3 font-semibold">
                        <li class="mb-1">
                            Truy cập trang web reCAPTCHA của Google và tìm phần "Đăng ký trang web mới":
                            <a href="https://www.google.com/recaptcha/admin" target="blank">https://www.google.com/recaptcha/admin</a>
                        </li>
                        <li class="mb-1">Nhập bất kỳ văn bản nào trong trường "Nhãn"</li>
                        <li class="mb-1">Chọn "reCAPTCHA v2" làm loại khóa trang</li>
                        <li class="mb-1 mt-1">
                            Nhập địa chỉ sau vào trường "Miền" trong một dòng riêng lẻ: <br>
                            <strong><?= parse_url(APPURL, PHP_URL_HOST) ?></strong>
                        </li>
                        <li class="mb-1">Sao chép và dán trang web và khóa bí mật</li>
                    </ul>
                </div>
                <div class="sm:col-span-1">
                    <div>
                        <label for="gridEmail">Site key</label>
                        <input name="site-key" type="text" value="<?= $AuthSite->get("options.recaptcha_site_key") ?>" class="form-input">
                    </div>
                    <div class="mb-4">
                        <label for="gridEmail">Secret key</label>
                        <input name="secret-key" type="text" value="<?= $AuthSite->get("options.recaptcha_secret_key") ?>" class="form-input">
                    </div>
                    <div>
                        <label class="inline-flex">
                            <input type="checkbox" class="form-checkbox" <?= $AuthSite->get("options.signup_recaptcha_verification") ? "checked" : "" ?> id="signup-recaptcha-verification" name="signup-recaptcha-verification" value="1"/>
                            <span>Bật Recaptcha trong trang đăng ký</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex">
                            <input type="checkbox" class="form-checkbox" <?= $AuthSite->get("options.signin_recaptcha_verification") ? "checked" : "" ?> id="signin-recaptcha-verification" name="signin-recaptcha-verification" value="1"/>
                            <span>Bật Recaptcha trong trang đăng nhập</span>
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>