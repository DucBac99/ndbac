<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Nạp tiền</h4>

    <!-- Card Border Shadow -->
    <div class="row justify-content-center mb-4">
        <div class="col-xl-6 col-md-12">
            <div class="card mb-xl-0">
                <div class="card-body">
                    <div class="p-2">
                    <h5 class="font-size-16">Thông tin chuyển khoản</h5>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Số tài khoản</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" placeholder="" disabled="disabled" class="form-control" value="<?= $AuthSite->get("banking.info.account_number") ?>">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Ngân hàng</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" placeholder="" disabled="disabled" class="form-control" value="<?= $AuthSite->get("banking.info.bank") ?>">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Chủ tài khoản</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" placeholder="" disabled="disabled" type="text" placeholder="" class="form-control" value="<?= $AuthSite->get("banking.info.account_name") ?>">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Chi Nhánh</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" placeholder="Chi nhánh" disabled="disabled" value="<?= $AuthSite->get("banking.info.branch") ?>" class="form-control">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Nội dung chuyển
                            khoản</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" placeholder="" disabled="disabled" type="text" placeholder="" class="form-control" value="<?= $AuthSite->get("banking.info.content") ?> <?= $AuthUser->get("id") ?>">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="col-10 mb-4 offset-1">
                        <div class="form-group row mb-0">
                            <label class="col-md-3 col-form-label form-control-label">Nhập số tiền</label>
                            <div class="col-md-9">
                            <div class="form-group">
                                <div class="">
                                <input type="text" class="form-control" id="money" placeholder="Nhập vào số tiền để tạo QR code">
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-6 col-md-12">
            <div class="card mb-xl-0">
                <div class="card-body">
                    <div class="p-2">
                        <img class="rounded mx-auto d-block" id="qr_code" src="https://img.vietqr.io/image/<?= $AuthSite->get("banking.auth.bank_code") ?>-<?= $AuthSite->get("banking.info.account_number") ?>-<?= $AuthSite->get("banking.auth.template") ?>.png?amount=0&accountName=<?= $AuthSite->get("banking.info.account_name") ?>&addInfo=<?= $AuthSite->get("banking.info.content") . " " . $AuthUser->get("id") ?>" style="width: 400px;">
                    </div>
                </div>
            <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Lưu ý!</h4>
                <p> - Quý khách ghi đúng thông tin nạp tiền thì tài khoản sẽ được cộng tự động sau khi giao dịch
                    thành công.</p>
                <p> - Quý khách nhập sai nội dung chuyển khoản sẽ không được hoàn tiền </p>
                <p> - Vui lòng nạp tối thiểu 100.000đ, dưới tối thiểu sẽ không hỗ trợ giải quyết </p>
            </div>
        </div>
    </div>
</div>