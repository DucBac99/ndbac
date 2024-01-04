<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600"> Các cài đặt khác</h3>
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
                        <h5> Các cài đặt khác</h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox-primary-1" type="checkbox" class="form-check-input" <?= $AuthSite->get("options.maintenance_mode") ? "checked" : "" ?> id="maintenance-mode" name="maintenance-mode" value="1">
                                    <label for="checkbox-primary-1">Bật chế độ bảo trì</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Support Url</label>
                                <input class="form-control" type="text" name="support-url" value="<?= $AuthSite->get("options.support_url") ?>">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Link hướng dẫn</label>
                                <input class="form-control" type="text" name="instruction-url" value="<?= $AuthSite->get("options.instruction_url") ?>">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">License Key CKFinder</label>
                                <input class="form-control" type="text" name="license-key" value="<?= $AuthSite->get("options.licenseKey") ?>">
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