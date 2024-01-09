<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $Service->isAvailable() ? htmlchars($Service->get("title") . " - " . $Service->get("group")) : "Dịch vụ mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header"><?= $Service->isAvailable() ? htmlchars($Service->get("title") . " - " . $Service->get("group")) : "Dịch vụ mới" ?></h5>
                <!-- Account -->
                <hr class="my-0">
                <div class="card-body">
                    <form class="js-ajax-form" action="<?= APPURL . "/services/" . ($Service->isAvailable() ? $Service->get("id") : "new") ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="firstName" class="form-label">Tiêu đề gốc</label>
                                <input type="text" name="title" value="<?= $Service->get("title") ?>" class="form-control">
                            </div>
                            <?php if ($Service->isAvailable()) : ?>
                                <div class="mb-3 col-md-12">
                                    <label for="firstName" class="form-label">Tiêu đề khác</label>
                                    <input type="text" name="title_extra" value="<?= $Service->get("title_extra") ?>" class="form-control">
                                </div>
                            <?php endif ?>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">Số ngày bảo hành</label>
                                <input type="number" name="warranty" value="<?= $Service->get("warranty") ?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">Icon</label>
                                <input class="form-control" type="text" id="icon" name="icon" value="<?= $Service->get("icon") ?>" autofocus="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">ID Name</label>
                                <input class="form-control" type="text" name="idname" id="idname" value="<?= $Service->get("idname") ?>">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="organization" class="form-label">Group</label>
                                <select name="group" class="form-select" required>
                                    <option value="facebook" <?= $Service->get("group") == "facebook" ? "selected" : "" ?>>Facebook</option>
                                    <option value="youtube" <?= $Service->get("group") == "youtube" ? "selected" : "" ?>>Youtube</option>
                                    <option value="tiktok" <?= $Service->get("group") == "tiktok" ? "selected" : "" ?>>TikTok</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Tốc độ</label>
                                <input type="text" class="form-control" name="speed" value="<?= $Service->get("speed") ?>">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Max hold</label>
                                <input type="text" class="form-control" name="max_hold" value="<?= $Service->get("max_hold") ?>">
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" <?= $Service->get("is_public") ? "checked" : "" ?> id="public" name="public" value="1">
                                    <label class="form-check-label" for="defaultCheck1">Hiện thị</label>
                                    <p class="form-text">Để tránh hiểu nhầm. Hiện thị ở đây tức là áp dụng cho toàn bộ cả server. Nếu ko tích thì sẽ ẩn toàn bộ. tích thì hiện</p>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" <?= $Service->get("is_maintaince") ? "checked" : "" ?> id="maintaince" name="maintaince" value="1">
                                    <label class="form-check-label" for="defaultCheck1">Bảo trì</label>
                                    <p class="form-text">Bảo trì cũng tương tự là áp dụng cho toàn bộ các server. Nếu muốn tích lẻ thì phần Server đấu hãy setup chúng</p>
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

        <?php if ($Service->isAvailable()) : ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                <h5 class="mb-0"> Cài đặt thông báo</h5>
                </div>
                <hr class="my-0">
                <div class="card-body">
                <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/servers" ?>" method="POST">
                    <input type="hidden" name="action" value="note">
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                        <p class="fw-semibold">Nội dung thông báo</label>
                            <textarea name="note" class="form-control ckeditor"><?= $Service->get("note") ?></textarea>
                        </div>
                    </div>
                    </div>
                    <div class="text-end">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <?php endif ?>

        <?php if ($Service->isAvailable()) : ?>
        <div class="col-md-6">
            <div class="card mb-4" id="card-server" data-url="<?= APPURL . "/services/" . $Service->get("id") . "/servers" ?>">
                <div class="row">
                    <div class="col-md-4 d-flex align-items-center">
                        <h5 class="card-header">Bảng danh sách</h5>
                    </div>
                    <?php if ($AuthUser->isAdmin()) : ?>
                        <div class="col-md-4 d-flex align-items-center">
                            <select class="form-select select" name="site_id">
                                <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                    <option value="<?= $site->get("id") ?>"><?= $site->get("domain") ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Account -->
                <hr class="my-0">
                <div class="card-body">
                <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/servers" ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="site_id" value="0">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <div class="card-datatable table-responsive">
                                    <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                        <table class="dt-complex-header table table-bordered dataTable no-footer" id="servers_table" aria-describedby="DataTables_Table_1_info" style="width: 1394px;">
                                            <thead>
                                                <tr>
                                                    <th>Tên server</th>
                                                    <th>Miền server</th>
                                                    <th>Bảo trì</th>
                                                    <th>Hiện thị</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
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
        <?php endif ?>
    </div>
</div>