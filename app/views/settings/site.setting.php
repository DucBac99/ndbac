<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= __("Site Settings") ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= __("Site Settings") ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="firstName" class="form-label fw-semibold"><?= __("Site name") ?></label>
                        <input class="form-control" name="name" type="text" value="<?= htmlchars($AuthSite->get("settings.site_name")) ?>" placeholder="<?= __("Enter site name here") ?>" maxlength="100">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold"><?= __("Site slogan") ?></label>
                        <input class="form-control" name="slogan" type="text" value="<?= htmlchars($AuthSite->get("settings.site_slogan")) ?>" placeholder="<?= __("Enter site slogan here") ?>" maxlength="100">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="firstName" class="form-label fw-semibold"><?= __("Site description") ?></label>
                        <textarea class="form-control" name="description" maxlength="255" rows="3"><?= htmlchars($AuthSite->get("settings.site_description")) ?></textarea>
                        <ul class="field-tips">
                            <li>Độ dài khuyến nghị của mô tả là 150-160 ký tự</li>
                        </ul>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold"><?= __("Keywords") ?></label>
                        <textarea class="form-control" name="keywords" maxlength="500" rows="3"><?= htmlchars($AuthSite->get("settings.site_keywords")) ?></textarea>
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