<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Các cài đặt khác</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Các cài đặt khác</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" <?= $AuthSite->get("options.maintenance_mode") ? "checked" : "" ?> id="maintenance-mode" name="maintenance-mode" value="1">
                            <label class="form-check-label fw-semibold" for="defaultCheck1">Bật chế độ bảo trì</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Support Url</label>
                        <input class="form-control" name="support-url" type="text" value="<?= $AuthSite->get("options.support_url") ?>">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Link hướng dẫn</label>
                        <input class="form-control" name="instruction-url" type="text" value="<?= $AuthSite->get("options.instruction_url") ?>">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">License Key CKFinder</label>
                        <input class="form-control" name="license-key" type="text" value="<?= $AuthSite->get("options.licenseKey") ?>">
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