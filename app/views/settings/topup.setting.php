<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Nạp tiền</h5>
        </div>
        <div class="mb-5">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="mb-2">Thông tin thanh toán</h5>
                        <div class="mb-3">
                            <label class="text-white-dark">Số tài khoản</label>
                            <input name="account_number" type="text" value="<?= $AuthSite->get("banking.info.account_number") ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Tên Tài Khoản</label>
                            <input name="account_name" type="text" value="<?= $AuthSite->get("banking.info.account_name") ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Ngân Hàng</label>
                            <input name="bank" type="text" value="<?= $AuthSite->get("banking.info.bank") ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Chi nhánh</label>
                            <input name="branch" type="text" value="<?= $AuthSite->get("banking.info.branch") ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Nội dung</label>
                            <input name="content" type="text" value="<?= $AuthSite->get("banking.info.content") ?>" class="form-input">
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-2">Thông tin tài khoản</h5>
                        <div class="mb-3">
                            <label class="text-white-dark">Số điện thoại</label>
                            <input name="username" type="text" value="<?= $AuthSite->get("banking.auth.username") ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Mật Khẩu</label>
                            <?php
                                try {
                                    $pass = \Defuse\Crypto\Crypto::decrypt(
                                    $AuthSite->get("banking.auth.password"),
                                    \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY)
                                    );
                                } catch (\Exception $e) {
                                    $pass = "";
                                }
                            ?>
                            <input name="password" type="text" value="<?= $pass ?>" class="form-input">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Mã ngân hàng</label>
                            <select id="gridState" name="bank_code" class="form-select text-white-dark">
                                <option value="ICB" <?= $AuthSite->get("banking.auth.bank_code") == "ICB" ? "selected" : "" ?>>(970415) VietinBank</option>
                                <option value="VCB" <?= $AuthSite->get("banking.auth.bank_code") == "VCB" ? "selected" : "" ?>>(970436) Vietcombank</option>
                                <option value="TCBB" <?= $AuthSite->get("banking.auth.bank_code") == "TCBB" ? "selected" : "" ?>>(970407) Techcombank Business</option>
                                <option value="TPB" <?= $AuthSite->get("banking.auth.bank_code") == "TPB" ? "selected" : "" ?>>(970423) TPBank</option>
                                <option value="MB" <?= $AuthSite->get("banking.auth.bank_code") == "MB" ? "selected" : "" ?>>(970422) MBBank</option>
                                <option value="ACB" <?= $AuthSite->get("banking.auth.bank_code") == "ACB" ? "selected" : "" ?>>(970416) ACB</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="text-white-dark">Mẫu hiện thị</label>
                            <select id="gridState" name="template" class="form-select text-white-dark">
                                <option value="compact" <?= $AuthSite->get("banking.auth.template") == "compact" ? "selected" : "" ?>>compact</option>
                                <option value="compact2" <?= $AuthSite->get("banking.auth.template") == "compact2" ? "selected" : "" ?>>compact2</option>
                                <option value="qr_only" <?= $AuthSite->get("banking.auth.template") == "qr_only" ? "selected" : "" ?>>qr_only</option>
                                <option value="print" <?= $AuthSite->get("banking.auth.template") == "print" ? "selected" : "" ?>>print</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>
