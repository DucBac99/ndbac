<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Danh sách servers</h4>

        <!-- Card Border Shadow -->
    <div class="row">
        <div class="card">
            <div class="row">
                <div class="col-md-4 d-flex align-items-center">
                    <h5 class="card-header">Bảng danh sách</h5>
                </div>
                <div class="col-md-8 mr-auto d-flex align-items-center justify-content-end">
                    <a class="btn rounded-pill btn-sm btn-success waves-effect waves-light p-2 ms-1" href="<?= APPURL . "/servers/new" ?>">
                        <i class="me-2 ti ti-plus label-icon"></i>Thêm mới
                    </a>
                    <a class="btn rounded-pill btn-sm btn-danger waves-effect waves-light p-2 ms-1 js-remove-list-item" data-url="<?= APPURL . "/servers" ?>" href="javascript:void(0)" data-table="#services_table">
                        <i class="me-2 ti ti-trash label-icon"></i>Xóa
                    </a>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                    <table class="dt-complex-header table table-bordered dataTable no-footer" id="servers_table" data-url="<?= APPURL . "/servers" ?>" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
                        <thead>
                            <tr>
                                <th style="width: 20px;">
                                    <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="customCheck0" id="customCheck0">
                                    <span class="form-check-label">&nbsp;</span>
                                    </label>
                                </th>
                                <th>#ID</th>
                                <th>Tên</th>
                                <th>API URL</th>
                                <th>Ngày cập nhật</th>
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