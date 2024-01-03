<div class="animate__animated p-6">
    <div x-data="custom">
        <div class="space-y-6">
        <h5 class="text-lg font-semibold dark:text-white-light">Biến động số dư</h5>
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
                        <div>
                            <select class="form-select text-white-dark" name="type" data-search-enabled="false">
                                <option value="">All</option>
                                <option value="+">Tiền vào</option>
                                <option value="-">Tiền ra</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="fluctuations_table" data-url="<?= APPURL . "/fluctuations" ?>">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Tên miền</th>
                                    <th>Số tiền trước</th>
                                    <th>Số tiền thay đổi</th>
                                    <th>Số tiền hiện tại</th>
                                    <th>Nội dung</th>
                                    <th>Thời gian</th>
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

