<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
                <div class="col-6">
                    <h3 class="f-w-600">Danh sách cấp bậc</h3>
                </div>
                <div class="col-6">
                    <div class="breadcrumb-sec">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                            <li class="breadcrumb-item">Quản trị</li>
                            <li class="breadcrumb-item active">Roles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Bảng danh sách</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="roles_table" data-url="<?= APPURL . "/roles" ?>">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" name="customCheck0" id="customCheck0" style="width: 20px; height: 20px">
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
    </div>
</div>