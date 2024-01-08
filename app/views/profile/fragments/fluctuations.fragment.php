<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Biến động số dư</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card">
            <div class="row d-flex">
                <h5 class="card-header">Bảng danh sách</h5>
                <?php if ($AuthUser->isAdmin()) : ?>
                <div class="col-md-2 mb-3">
                    <select class="form-select select" name="site_id">
                    <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                        <option value="<?= $site->get("id") ?>" <?= $AuthSite->get("id") == $site->get("id") ? "selected" : "" ?>><?= $site->get("domain") ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2 mb-3">
                <select class="form-select select" name="type" data-search-enabled="false">
                    <option value="">All</option>
                    <option value="+">Tiền vào</option>
                    <option value="-">Tiền ra</option>
                </select>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                    <table class="dt-complex-header table table-bordered dataTable no-footer" id="fluctuations_table" data-url="<?= APPURL . "/fluctuations" ?>" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
                        <thead>
                            <tr>
                                <th>#ID</th>
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