<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= __("Logotype") ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= __("Logotype") ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold"><?= __("Logo") ?></label>
                        <div class="input-group">
                        <input class="form-control logotype" name="logotype" type="text" value="<?= htmlchars($AuthSite->get("settings.logotype")) ?>" maxlength="100">
                            <button type="button" class="btn btn-success waves-effect waves-light btn-ckfinder" data-target=".logotype"><i class="ti ti-photo-edit"></i></button>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold"><?= __("Logomark") ?></label>
                        <div class="input-group">
                        <input class="form-control logotype" name="logomark" type="text" value="<?= htmlchars($AuthSite->get("settings.logomark")) ?>" maxlength="100">
                            <button type="button" class="btn btn-success waves-effect waves-light btn-ckfinder" data-target=".logomark"><i class="ti ti-photo-edit"></i></button>
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