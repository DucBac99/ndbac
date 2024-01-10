<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $Server->isAvailable() ? htmlchars($Server->get("name")) : "Server mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= $Server->isAvailable() ? htmlchars($Server->get("name")) : "Server mới" ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/servers/" . ($Server->isAvailable() ? $Server->get("id") : "new") ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label">Tên</label>
                        <input type="text" name="name" value="<?= $Server->get("name") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label">API URL</label>
                        <input type="text" name="api_url" value="<?= $Server->get("api_url") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label">API KEY</label>
                        <input class="form-control" type="text" id="api_key" name="api_key" value="<?= $Server->get("api_key") ?>" autofocus="" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label">API USER ID</label>
                        <input type="text" name="api_user_id" value="<?= $Server->get("api_user_id") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" <?= $Server->get("is_public") ? "checked" : "" ?> id="public" name="public" value="1">
                            <label class="form-check-label" for="defaultCheck1">Hiện thị</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" <?= $Server->get("is_maintenance") ? "checked" : "" ?> id="maintenance" name="maintenance" value="1">
                            <label class="form-check-label" for="defaultCheck1">Bảo trì</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" <?= $Server->get("allow_refund") ? "checked" : "" ?> id="allow_refund" name="allow_refund" value="1">
                            <label class="form-check-label" for="defaultCheck1">Cho phép hoàn đơn</label>
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