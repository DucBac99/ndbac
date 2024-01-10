<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $User->isAvailable() ? htmlchars($User->get("firstname") . " " . $User->get("lastname")) : "Người dùng mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Form người dùng</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form action="<?= APPURL . "/users/" . ($User->isAvailable() ? $User->get("id") : "new") ?>" method="POST" class="js-ajax-form" >
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label fw-semibold">Loại tài khoản</label>
                        <select class="form-control" name="account-type" <?= $User->get("id") == $AuthUser->get("id") ? "disabled" : "" ?>>
                            <?php foreach ($Roles->getDataAs("Role") as $role) : ?>
                            <option value="<?= $role->get("id") ?>" <?= $User->get("role_id") == $role->get("id") ? "selected" : "" ?>>
                                <?= $role->get("title") ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label fw-semibold">Tình trạng</label>
                        <select class="form-control" name="status" <?= $User->get("id") == $AuthUser->get("id") ? "disabled" : "" ?>>
                            <?php
                                if ($User->isAvailable()) {
                                    $status = $User->get("is_active") ? 1 : 0;
                                } else {
                                    $status = 1;
                                }
                            ?>
                            <option value="1" <?= $status == 1 ? "selected" : "" ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? "selected" : "" ?>>Ngừng hoạt động</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label fw-semibold">Tên đầu</label>
                        <input class="form-control" type="text" id="firstName" name="firstname" value="<?= $AuthUser->get("firstname") ?>" autofocus="">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label fw-semibold">Tên cuối</label>
                        <input class="form-control" type="text" name="lastname" id="lastName" value="<?= $AuthUser->get("lastname") ?>">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <input class="form-control" type="text" id="email" name="email" value="<?= $AuthUser->get("email") ?>" disabled>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="organization" class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="text" class="form-control" id="organization" name="password">
                        <p class="form-text text-muted">
                            Nếu bạn không muốn thay đổi mật khẩu thì hãy để trống các trường mật khẩu này!
                        </p>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label fw-semibold">Mật khẩu xác nhận</label>
                        <input type="text" class="form-control" name="password-confirm">
                    </div>
                    <div class="mb-3 col-md-6">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" <?= $User->get("has_analytics") ? "checked" : "" ?> id="has-analytics" name="has-analytics" value="1">
                            <label class="form-check-label" for="defaultCheck1">Cho phép xem thống kê tương tác</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" <?= $User->get("is_viewer") ? "checked" : "" ?> id="is-viewer" name="is-viewer" value="1">
                            <label class="form-check-label" for="defaultCheck1">Cho phép xem thống kê đơn</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Lưu thay đổi</button>
                </div>
            </form>
        </div>
        <!-- /Account -->
        </div>
    </div>
</div>