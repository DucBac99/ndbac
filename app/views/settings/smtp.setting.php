<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= __("SMTP") ?></h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form" id="smtp-form"  action="<?= APPURL . "/settings/" . $page ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">SMTP Server</label>
                        <input id="gridEmail" type="text" name="host" value="<?= htmlchars($AuthSite->get("email_settings.smtp.host")) ?>" class="form-input">
                        <span>Nếu bạn để trống trường này thì các giá trị trường khác sẽ bị bỏ qua và cấu hình mặc định của máy chủ sẽ được sử dụng.</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">From</label>
                        <input id="gridEmail" type="text" name="from" value="<?= htmlchars($AuthSite->get("email_settings.smtp.from")) ?>" class="form-input">
                        <span>Tất cả các email sẽ được gửi từ địa chỉ email này.</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">Port</label>
                        <select id="gridState" name="port" class="form-select text-white-dark">
                            <?php $port = $AuthSite->get("email_settings.smtp.port") ?>
                            <option value="25" <?= $port == "25" ? "selected" : "" ?>>25</option>
                            <option value="465" <?= $port == "465" ? "selected" : "" ?>>465</option>
                            <option value="587" <?= $port == "587" ? "selected" : "" ?>>587</option>
                        </select>
                    </div>
                    <div>
                        <label for="gridEmail">Encryption</label>
                        <select id="gridState" name="encryption" class="form-select text-white-dark">
                            <?php $encryption = $AuthSite->get("email_settings.smtp.encryption") ?>
                            <option value="">Không</option>
                            <option value="ssl" <?= $encryption == "ssl" ? "selected" : "" ?>>SSL</option>
                            <option value="tls" <?= $encryption == "tls" ? "selected" : "" ?>>TLS</option>
                        </select>
                    </div>
                </div>
                <div class="pb-1">
                    <div>
                        <label class="inline-flex">
                            <input type="checkbox" class="form-checkbox" id="auth" name="auth" value="1" <?= $AuthSite->get("email_settings.smtp.auth") ? "checked" : "" ?> />
                            <span>SMTP Auth</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">Auth. username</label>
                        <input id="gridEmail" type="text" name="username" value="<?= htmlchars($AuthSite->get("email_settings.smtp.username")) ?>" class="form-input">
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
                    <div>
                        <label for="gridEmail">Auth. password</label>
                        <input id="gridEmail" type="text" name="password" value="<?= $password ?>" class="form-input">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>