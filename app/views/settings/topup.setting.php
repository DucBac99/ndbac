<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600">Nạp tiền</h3>
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
                        <h5>Nạp tiền</h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="pb-3">Thông tin thanh toán</h5>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Số Tài Khoản</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="account_number" value="<?= $AuthSite->get("banking.info.account_number") ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Tên Tài Khoản</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="account_name" value="<?= $AuthSite->get("banking.info.account_name") ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Ngân Hàng</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="bank" value="<?= $AuthSite->get("banking.info.bank") ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Chi nhánh</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="branch" value="<?= $AuthSite->get("banking.info.branch") ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Nội dung</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="content" value="<?= $AuthSite->get("banking.info.content") ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="pb-3">Thông tin tài khoản</h5>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Số điện thoại</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="username" value="<?= $AuthSite->get("banking.auth.username") ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Mật khẩu</label>
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
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="password" value="<?= $pass ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Mã ngân hàng</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="bank_code" id="bank_code">
                                                <option value="ICB" <?= $AuthSite->get("banking.auth.bank_code") == "ICB" ? "selected" : "" ?>>(970415) VietinBank</option>
                                                <option value="VCB" <?= $AuthSite->get("banking.auth.bank_code") == "VCB" ? "selected" : "" ?>>(970436) Vietcombank</option>
                                                <option value="TCBB" <?= $AuthSite->get("banking.auth.bank_code") == "TCBB" ? "selected" : "" ?>>(970407) Techcombank Business</option>
                                                <option value="TPB" <?= $AuthSite->get("banking.auth.bank_code") == "TPB" ? "selected" : "" ?>>(970423) TPBank</option>
                                                <option value="MB" <?= $AuthSite->get("banking.auth.bank_code") == "MB" ? "selected" : "" ?>>(970422) MBBank</option>
                                                <option value="ACB" <?= $AuthSite->get("banking.auth.bank_code") == "ACB" ? "selected" : "" ?>>(970416) ACB</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-sm-3 col-form-label">Mẫu hiện thị</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="template" id="template">
                                                <option value="compact" <?= $AuthSite->get("banking.auth.template") == "compact" ? "selected" : "" ?>>compact</option>
                                                <option value="compact2" <?= $AuthSite->get("banking.auth.template") == "compact2" ? "selected" : "" ?>>compact2</option>
                                                <option value="qr_only" <?= $AuthSite->get("banking.auth.template") == "qr_only" ? "selected" : "" ?>>qr_only</option>
                                                <option value="print" <?= $AuthSite->get("banking.auth.template") == "print" ? "selected" : "" ?>>print</option>
                                            </select>
                                        </div>
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