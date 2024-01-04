<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
                <div class="col-6">
                    <h3 class="f-w-600">Danh sách người dùng</h3>
                </div>
                <div class="col-6">
                    <div class="breadcrumb-sec">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                            <li class="breadcrumb-item">Quản trị</li>
                            <li class="breadcrumb-item active">Users</li>
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
                            <table class="table table-bordered table-striped" id="users_table" data-url="<?= APPURL . "/users" ?>">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" name="customCheck0" id="customCheck0" style="width: 20px; height: 20px">
                                                <span class="form-check-label">&nbsp;</span>
                                            </label>
                                        </th>
                                        <th>#ID</th>
                                        <th>Email</th>
                                        <th>Loại tài khoản</th>
                                        <th>Miền</th>
                                        <th>Tên đầu</th>
                                        <th>Tên cuối</th>
                                        <th>Tình trạng</th>
                                        <th>Số dư</th>
                                        <th>Tổng nạp</th>
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