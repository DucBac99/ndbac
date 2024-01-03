<div class="animate__animated p-6">
    <div x-data="custom">
        <div class="space-y-6">
        <h5 class="text-lg font-semibold dark:text-white-light">Lịch sử nạp tiền</h5>
            <div class="panel items-center p-2">
                <div class="flex items-center justify-between">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="mb-5 flex items-center justify-between">
                            <h5 class="text-lg dark:text-white-light">Bảng danh sách</h5>
                        </div>
                        <?php if ($AuthUser->isAdmin()) : ?>
                        <div>
                            <select class="form-select text-white-dark" name="site_id">
                            <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                <option value="<?= $site->get("id") ?>" <?= $AuthSite->get("id") == $site->get("id") ? "selected" : "" ?>><?= $site->get("domain") ?></option>
                            <?php endforeach ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="payments_table" data-url="<?= APPURL . "/payment-history" ?>">
                            <thead>
                                <tr>
                                    <th>Thông tin</th>
                                    <th>Số Tiền</th>
                                    <th>Tên miền</th>
                                    <th>Tình trạng</th>
                                    <th>Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

