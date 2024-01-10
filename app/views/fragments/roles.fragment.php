<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Danh sách vai trò</h4>

        <!-- Card Border Shadow -->
    <div class="row">
        <div class="card">
            <div class="row">
                <div class="col-md-2 d-flex align-items-center">
                    <h5 class="card-header">Bảng danh sách</h5>
                </div>
                <?php if ($AuthUser->isAdmin()) : ?>
                    <div class="col-md-2 d-flex align-items-center">
                        <select class="form-select select" name="site_id">
                            <option value="" selected>All</option>
                            <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                <option value="<?= $site->get("id") ?>"><?= $site->get("domain") ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-md-8 mr-auto d-flex align-items-center justify-content-end">
                    <a class="btn rounded-pill btn-sm btn-success waves-effect waves-light p-2 ms-1" href="<?= APPURL . "/roles/new" ?>">
                        <i class="me-2 ti ti-plus label-icon"></i>Thêm mới
                    </a>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                    <table class="dt-complex-header table table-bordered dataTable" id="roles_table" data-url="<?= APPURL . "/roles" ?>" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
                        <thead>
                            <tr>
                                <th style="width: 20px;">
                                    <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="customCheck0" id="customCheck0">
                                    <span class="form-check-label">&nbsp;</span>
                                    </label>
                                </th>
                                <th>#ID</th>
                                <th>Tên ID</th>
                                <th>Tên miền</th>
                                <th>Tiêu đề</th>
                                <th>Màu sắc</th>
                                <th>Tổng nạp</th>
                                <th>Cập nhật lúc</th>
                                <th>Hành động</th>
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