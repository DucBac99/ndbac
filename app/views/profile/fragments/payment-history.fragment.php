<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Lịch sử nạp tền</h4>

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
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                    <table class="dt-complex-header table table-bordered dataTable no-footer" id="payments_table" data-url="<?= APPURL . "/payment-history" ?>" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
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