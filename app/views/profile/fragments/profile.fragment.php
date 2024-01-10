<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Thông tin cá nhân</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Thông tin cá nhân</h5>
        <!-- Account -->
        <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="../../assets/img/avatars/14.png" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
            <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light" tabindex="0">
                <span class="d-none d-sm-block">Upload new photo</span>
                <i class="ti ti-upload d-block d-sm-none"></i>
                <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                </label>
                <button type="button" class="btn btn-label-secondary account-image-reset mb-3 waves-effect">
                <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
                </button>

                <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 800K</div>
            </div>
            </div>
        </div>
        <hr class="my-0">
        <div class="card-body">
            <form id="formAccountSettings" action="<?= APPURL . "/profile" ?>" method="POST" class="fv-plugins-bootstrap5 fv-plugins-framework js-ajax-form" novalidate="novalidate">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6 fv-plugins-icon-container">
                        <label for="firstName" class="form-label fw-semibold">Tên đầu</label>
                        <input class="form-control" type="text" id="firstName" name="firstname" value="<?= $AuthUser->get("firstname") ?>" autofocus="">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <div class="mb-3 col-md-6 fv-plugins-icon-container">
                        <label for="lastName" class="form-label fw-semibold">Tên cuối</label>
                        <input class="form-control" type="text" name="lastname" id="lastName" value="<?= $AuthUser->get("lastname") ?>">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
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