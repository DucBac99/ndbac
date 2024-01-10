<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= __("SMTP") ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= __("SMTP") ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">SMTP Server</label>
                        <input class="form-control" name="host" type="text" value="<?= htmlchars($AuthSite->get("email_settings.smtp.host")) ?>" maxlength="200">
                        <ul class="field-tips">
                            <li>Nếu bạn để trống trường này thì các giá trị trường khác sẽ bị bỏ qua và cấu hình mặc định của máy chủ sẽ được sử dụng.</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">From</label>
                        <input class="form-control" name="from" type="text" value="<?= htmlchars($AuthSite->get("email_settings.smtp.from")) ?>" maxlength="200">
                        <ul class="field-tips">
                            <li>Tất cả các email sẽ được gửi từ địa chỉ email này.</li>
                        </ul>
                    </div>
                    <div class=" mb-3 col-md-6">
                        <label class="form-label fw-semibold">Port</label>
                        <select name="port" class="form-control">
                            <?php $port = $AuthSite->get("email_settings.smtp.port") ?>
                            <option value="25" <?= $port == "25" ? "selected" : "" ?>>25</option>
                            <option value="465" <?= $port == "465" ? "selected" : "" ?>>465</option>
                            <option value="587" <?= $port == "587" ? "selected" : "" ?>>587</option>
                        </select>
                    </div>
                    <div class=" mb-3 col-md-6">
                        <label class="form-label fw-semibold">Encryption</label>
                        <select name="encryption" class="form-control">
                            <?php $encryption = $AuthSite->get("email_settings.smtp.encryption") ?>
                            <option value="">Không</option>
                            <option value="ssl" <?= $encryption == "ssl" ? "selected" : "" ?>>SSL</option>
                            <option value="tls" <?= $encryption == "tls" ? "selected" : "" ?>>TLS</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div>
                            <label class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="auth" name="auth" value="1" <?= $AuthSite->get("email_settings.smtp.auth") ? "checked" : "" ?>>
                                <span class="form-check-label">SMTP Auth</span>
                            </label>
                        </div>
                    </div>
                    <div class=" mb-3 col-md-6">
                        <label class="form-label fw-semibold">Auth. username</label>
                        <input class="form-control" name="username" type="text" value="<?= htmlchars($AuthSite->get("email_settings.smtp.username")) ?>" maxlength="200">
                    </div>
                    <?php
                        $password = $AuthSite->get("email_settings.smtp.password");
                        if ($AuthSite->get("email_settings.smtp.password")) {
                            try {
                                $password = \Defuse\Crypto\Crypto::decrypt(
                                    $AuthSite->get("email_settings.smtp.password"),
                                    \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY)
                                );
                            } catch (Exception $e) {
                            }
                        }

                    ?>
                    <div class=" mb-3 col-md-6">
                        <label class="form-label fw-semibold">Auth. password</label>
                        <input class="form-control" name="password" type="password" value="<?= $password ?>" maxlength="200">
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