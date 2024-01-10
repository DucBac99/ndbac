<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $Site->isAvailable() ? htmlchars($Site->get("domain")) : "Site mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= $Site->isAvailable() ? htmlchars($Site->get("domain")) : "Site mới" ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
        <form class="js-ajax-form" action="<?= APPURL . "/sites/" . ($Site->isAvailable() ? $Site->get("id") : "new") ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label">Domain</label>
                        <input type="text" name="domain" value="<?= $Site->get("domain") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label"><?= __("Site name") ?></label>
                        <input class="form-control" name="name" type="text" value="<?= htmlchars($Site->get("settings.site_name")) ?>" placeholder="<?= __("Enter site name here") ?>" maxlength="100">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label"><?= __("Site slogan") ?></label>
                        <input class="form-control" name="slogan" type="text" value="<?= htmlchars($Site->get("settings.site_slogan")) ?>" placeholder="<?= __("Enter site slogan here") ?>" maxlength="100">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label"><?= __("Site theme") ?></label>
                        <select class="form-control select" name="theme" data-search-enabled="false" required>
                            <option value="" disabled selected>Chọn giao diện</option>
                            <?php foreach ($Themes->getDataAs("Theme") as $theme) : ?>
                                <option value="<?= $theme->get("id") ?>" <?= $Site->get("theme") == $theme->get("idname") ? "selected" : "" ?>><?= $theme->get("idname") ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="email" class="form-label"><?= __("Site description") ?></label>
                        <textarea class="form-control" name="description" maxlength="255" rows="3"><?= htmlchars($Site->get("settings.site_description")) ?></textarea>
                        <ul class="field-tips">
                            <li>Độ dài khuyến nghị của mô tả là 150-160 ký tự</li>
                        </ul>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="organization" class="form-label"><?= __("Keywords") ?></label>
                        <textarea class="form-control" name="keywords" maxlength="500" rows="3"><?= htmlchars($Site->get("settings.site_keywords")) ?></textarea>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="address" class="form-label">Tình trạng</label>
                        <select class="form-control" name="status">
                            <option value="1" <?= $Site->get("is_active") == 1 ? "selected" : "" ?>>Hoạt động</option>
                            <option value="0" <?= $Site->get("is_active") == 0 ? "selected" : "" ?>>Ngừng hoạt động</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" <?= $Site->get("options.maintenance_mode") ? "checked" : "" ?> id="maintenance-mode" name="maintenance-mode" value="1">
                            <label class="form-check-label" for="defaultCheck1">Bật chế độ bảo trì</label>
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