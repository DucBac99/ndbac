<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Nạp tiền</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Nạp tiền</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="content-heading">Thông tin thanh toán</h4>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Số Tài Khoản</label>
                            <input class="form-control" name="account_number" type="text" value="<?= $AuthSite->get("banking.info.account_number") ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Tên Tài Khoản</label>
                            <input class="form-control" name="account_name" type="text" value="<?= $AuthSite->get("banking.info.account_name") ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Ngân Hàng</label>
                            <input class="form-control" name="bank" type="text" value="<?= $AuthSite->get("banking.info.bank") ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Chi nhánh</label>
                            <input class="form-control" name="branch" type="text" value="<?= $AuthSite->get("banking.info.branch") ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Nội dung</label>
                            <input class="form-control" name="content" type="text" value="<?= $AuthSite->get("banking.info.content") ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="content-heading">Thông tin thanh toán</h4>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Số điện thoại</label>
                            <input class="form-control" name="username" type="text" value="<?= $AuthSite->get("banking.auth.username") ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Mật Khẩu</label>
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
                            <input class="form-control" name="password" type="text" value="<?= $pass ?>">
                        </div>
                        <div class="form-group mb-3">
                        <label for="lastName" class="form-label fw-semibold">Mã ngân hàng</label>
                            <select class="form-control" name="bank_code" id="bank_code">
                                <option value="ICB" <?= $AuthSite->get("banking.auth.bank_code") == "ICB" ? "selected" : "" ?>>(970415) VietinBank</option>
                                <option value="VCB" <?= $AuthSite->get("banking.auth.bank_code") == "VCB" ? "selected" : "" ?>>(970436) Vietcombank</option>
                                <option value="TCBB" <?= $AuthSite->get("banking.auth.bank_code") == "TCBB" ? "selected" : "" ?>>(970407) Techcombank Business</option>
                                <option value="TPB" <?= $AuthSite->get("banking.auth.bank_code") == "TPB" ? "selected" : "" ?>>(970423) TPBank</option>
                                <option value="MB" <?= $AuthSite->get("banking.auth.bank_code") == "MB" ? "selected" : "" ?>>(970422) MBBank</option>
                                <option value="ACB" <?= $AuthSite->get("banking.auth.bank_code") == "ACB" ? "selected" : "" ?>>(970416) ACB</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="lastName" class="form-label fw-semibold">Mẫu hiện thị</label>
                            <select class="form-control" name="template" id="template">
                                <option value="compact" <?= $AuthSite->get("banking.auth.template") == "compact" ? "selected" : "" ?>>compact</option>
                                <option value="compact2" <?= $AuthSite->get("banking.auth.template") == "compact2" ? "selected" : "" ?>>compact2</option>
                                <option value="qr_only" <?= $AuthSite->get("banking.auth.template") == "qr_only" ? "selected" : "" ?>>qr_only</option>
                                <option value="print" <?= $AuthSite->get("banking.auth.template") == "print" ? "selected" : "" ?>>print</option>
                            </select>
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