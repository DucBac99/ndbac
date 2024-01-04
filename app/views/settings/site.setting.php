<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600"><?= __("Site Settings") ?></h3>
        </div>
        <div class="col-6">
            <div class="breadcrumb-sec">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item">Hệ thống</li>
                <li class="breadcrumb-item active">Cài đặt</li>
            </ul>
            </div>
        </div>
        </div>
    </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5><?= __("Site Settings") ?></h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="col-form-label"><?= __("Site name") ?></label>
                                <input class="form-control" type="text" name="name" value="<?= htmlchars($AuthSite->get("settings.site_name")) ?>" placeholder="<?= __("Enter site name here") ?>" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label"><?= __("Site slogan") ?></label>
                                <input class="form-control" type="text" name="slogan" value="<?= htmlchars($AuthSite->get("settings.site_slogan")) ?>"placeholder="<?= __("Enter site slogan here") ?>" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label"><?= __("Site description") ?></label>
                                <textarea class="form-control" type="text" name="description" maxlength="255" rows="3"><?= htmlchars($AuthSite->get("settings.site_description")) ?></textarea>
                                <ul class="field-tips">
                                    <li>Độ dài khuyến nghị của mô tả là 150-160 ký tự</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label"><?= __("Keywords") ?></label>
                                <textarea class="form-control" type="text" name="description" maxlength="255" rows="3"><?= htmlchars($AuthSite->get("settings.site_keywords")) ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>