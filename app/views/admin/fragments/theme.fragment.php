<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $Theme->isAvailable() ? htmlchars($Theme->get("idname")) : "Giao diện mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header"><?= $Theme->isAvailable() ? htmlchars($Theme->get("idname")) : "Giao diện mới" ?></h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/themes/" . ($Theme->isAvailable() ? $Theme->get("id") : "new") ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="firstName" class="form-label">Tên</label>
                        <input type="text" name="idname" value="<?= $Theme->get("idname") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label">Thumb</label>
                        <div class="input-group">
                            <input class="form-control thumb" name="thumb" type="text" value="<?= $Theme->get("thumb") ?>" maxlength="100">
                            <button type="button" class="btn btn-success waves-effect waves-light btn-ckfinder" data-target=".thumb"><i class="ti ti-photo-edit"></i></button>
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