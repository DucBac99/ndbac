<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600"><?= __("SMTP") ?></h3>
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
                        <h5><?= __("SMTP") ?></h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                    <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="col-form-label">SMTP Server</label>
                                <input class="form-control" type="text" name="host" value="<?= htmlchars($AuthSite->get("email_settings.smtp.host")) ?>" maxlength="200">
                                <div class="form-text">Nếu bạn để trống trường này thì các giá trị trường khác sẽ bị bỏ qua và cấu hình mặc định của máy chủ sẽ được sử dụng.</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">From</label>
                                <input class="form-control" type="text" name="from" value="<?= htmlchars($AuthSite->get("email_settings.smtp.from")) ?>">
                                <div class="form-text">Tất cả các email sẽ được gửi từ địa chỉ email này.</div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-6">
                                    <label class="col-form-label">Port</label>
                                    <select class="form-control" name="port">
                                        <?php $port = $AuthSite->get("email_settings.smtp.port") ?>
                                        <option value="25" <?= $port == "25" ? "selected" : "" ?>>25</option>
                                        <option value="465" <?= $port == "465" ? "selected" : "" ?>>465</option>
                                        <option value="587" <?= $port == "587" ? "selected" : "" ?>>587</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">Encryption</label>
                                    <select class="form-control" name="encryption">
                                        <?php $encryption = $AuthSite->get("email_settings.smtp.encryption") ?>
                                        <option value="">Không</option>
                                        <option value="ssl" <?= $encryption == "ssl" ? "selected" : "" ?>>SSL</option>
                                        <option value="tls" <?= $encryption == "tls" ? "selected" : "" ?>>TLS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox-primary-1" type="checkbox" name="auth" class="form-check-input" value="1" <?= $AuthSite->get("email_settings.smtp.auth") ? "checked" : "" ?>>
                                    <label for="checkbox-primary-1">SMTP Auth</label>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-6">
                                    <label class="col-form-label">Auth. username</label>
                                    <input class="form-control" type="text" name="username" value="<?= htmlchars($AuthSite->get("email_settings.smtp.username")) ?>">
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
                                <div class="col-6">
                                    <label class="col-form-label">Auth. password</label>
                                    <input class="form-control" type="text" name="password" value="<?= $password ?>">
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