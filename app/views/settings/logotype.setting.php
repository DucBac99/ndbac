<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600"><?= __("Logotype") ?></h3>
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
                        <h5><?= __("Logotype") ?></h5>
                    </div>
                    <hr>
                    <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                        <input type="hidden" name="action" value="save">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><?= __("Logo") ?></label>
                                <div class="input-group">
                                    <input class="form-control logotype" name="logotype" type="text" value="<?= htmlchars($AuthSite->get("settings.logotype")) ?>" maxlength="100">
                                    <button type="button" class="input-group-text btn-success btn-ckfinder" data-target=".logotype"><i data-feather="image"></i></button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?= __("Logomark") ?></label>
                                <div class="input-group">
                                    <input class="form-control logomark" name="logomark" type="text" value="<?= htmlchars($AuthSite->get("settings.logomark")) ?>" maxlength="100">
                                    <button type="button" class="input-group-text btn-success btn-ckfinder" data-target=".logomark"><i data-feather="image"></i></button>
                                </div>
                                <ul>
                                    <li><i class="fa fa-angle-double-right txt-primary m-r-10"></i>Sẽ được sử dụng làm menu và biểu tượng yêu thích được thu nhỏ</li>
                                    <li><i class="fa fa-angle-double-right txt-primary m-r-10"></i>Nên sử dụng hình ảnh PNG 128px x 128px</li>
                                </ul>
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