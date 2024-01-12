<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Danh sách đơn hàng <span class="fw-normal"><?= htmlchars($Service->get("title") . " - " . $Service->get("group")) ?></h4>

        <!-- Card Border Shadow -->
    <div class="row">
        <div class="card">
            <div class="row">
                <div class="col-md-2 d-flex align-items-center">
                    <h5 class="card-header">Nâng cao</h5>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <select class="form-control select select2" name="status">
                        <option disabled selected>Status</option>
                        <option value="">All</option>
                        <?php foreach ($order_status as $type) : ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php if ($AuthUser->isAdmin()) : ?>
                    <div class="col-md-1 d-flex align-items-center">
                        <select class="form-control select select2" name="site_id">
                            <option disabled selected value="">Tên miền</option>
                            <option value="">All</option>
                            <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                <option value="<?= $site->get("id") ?>"><?= $site->get("domain") ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-md-1 d-flex align-items-center">
                    <select class="form-control select select2" name="server_id">
                        <option disabled selected value="">Server</option>
                        <option value="">All</option>
                        <?php foreach ($Servers as $sv) : ?>
                            <option value="<?= $sv->id ?>"><?= $sv->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-7 mr-auto d-flex align-items-center justify-content-end">
                    <a type="button" class="btn rounded-pill btn-sm btn-success waves-effect waves-light me-2" href="<?= $uri . "/new" ?>">
                        <i class="me-2 ti ti-plus label-icon"></i>Thêm mới
                    </a>
                    <button type="button" class="btn rounded-pill btn-sm btn-info waves-effect btn-label waves-light me-2 js-group-order" data-table="#orders_table" data-url="<?= $uri ?>">
                        <i class="ti ti-archive label-icon me-2"></i>
                        Gộp đơn
                    </button>

                    <button type="button" class="btn rounded-pill btn-sm btn-warning waves-effect btn-label waves-light me-2 js-warranty-check" data-table="#orders_table" data-url="<?= $uri ?>">
                        <i class="ti ti-aperture label-icon me-2"></i>
                        Bảo hành
                    </button>

                    <?php if ($AuthUser->isAdmin()) : ?>

                        <button type="button" class="btn rounded-pill btn-sm btn-dark waves-effect btn-label waves-light me-2 btn-refund-order-bulk" data-table="#orders_table" data-url="<?= $uri ?>">
                            <i class="ti ti-rotate label-icon me-2"></i>
                            Hoàn
                        </button>

                        <button type="button" class="btn rounded-pill btn-sm btn-primary waves-effect btn-label waves-light me-2" data-bs-toggle="modal" data-bs-target="#modal_edit_orders">
                            <i class="ti ti-edit label-icon me-2"></i>
                            Sửa
                        </button>

                        <button type="button" class="btn rounded-pill btn-sm btn-danger waves-effect btn-label waves-light js-remove-list-item" data-url="<?= $uri ?>" data-table="#orders_table">
                            <i class="ti ti-trash label-icon me-2"></i>
                            Xoá
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                    <table class="dt-complex-header table table-bordered dataTable" id="orders_table" data-url="<?= $uri ?>" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
                        <thead>
                            <tr>
                                <th style="width: 20px;">
                                    <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="customCheck0" id="customCheck0">
                                    <span class="form-check-label">&nbsp;</span>
                                    </label>
                                </th>
                                <th>#ID</th>
                                <th>Seeding UID</th>
                                <th>Order Amount</th>
                                <th>Start Num</th>
                                <th>Seeding Num</th>
                                <th>Reaction</th>
                                <th>Status</th>
                                <th>Bảo hành</th>
                                <th>Note</th>
                                <th>Note Extra</th>
                                <th>Ngày tạo</th>
                                <th>Người tạo</th>
                                <th>Nhóm</th>
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