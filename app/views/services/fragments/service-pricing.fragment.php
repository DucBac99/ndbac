<div class="page-content">
  <div class="container-fluid">

    <!-- start page title -->
    <div class="row">
      <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18"> <?= "Giá dịch vụ - " . $Service->get("title") . " - " . $Service->get("group") ?></h4>

        </div>
      </div>
    </div>
    <!-- end page title -->
    <!-- Main charts -->
    <div class="row">
      <div class="col-xl-7 col-md-12">

        <div class="card" data-url="<?= APPURL . "/services/" . $Service->get("id") . "/price" ?>">
          <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
            <h6 class="py-sm-3 mb-sm-auto"> <?= "Giá dịch vụ - " . $Service->get("title") . " - " . $Service->get("group") ?></h6>
            <div class="wmin-sm-200 ms-4">
              <select class="form-control select" name="site_id">
                <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                  <option value="<?= $site->get("id") ?>" <?= $AuthSite->get("id") == $site->get("id") ? "selected" : "" ?>><?= $site->get("domain") ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="wmin-sm-200 ms-4">
              <select class="form-control select" name="server_id">
                <?php foreach ($Servers->getDataAs("Server") as $sv) : ?>
                  <option value="<?= $sv->get("id") ?>" <?= 1 == $sv->get("id") ? "selected" : "" ?>><?= $sv->get("name") . " - " . getHost($sv->get("api_url")) ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>

          <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/price" ?>" method="POST">
              <input type="hidden" name="action" value="save">
              <input type="hidden" name="server_id" value="0">
              <div class="row">
                <div class="table-responsive mb-4">
                  <table class="table table-bordered table-striped table-hover" id="prices_table">
                    <thead>
                      <tr>
                        <th>Tên miền</th>
                        <th>Vai trò</th>
                        <th>Giá</th>
                        <th>Mua tối thiểu</th>
                        <th>Mua tối đa</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Lưu thay đổi </button>
              </div>
            </form>
          </div>
        </div>

      </div>

    </div>
    <!-- /main charts -->
  </div> <!-- container-fluid -->
</div>
