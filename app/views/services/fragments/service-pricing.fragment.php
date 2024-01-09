<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= "Giá dịch vụ - " . $Service->get("title") . " - " . $Service->get("group") ?></h4>

        <!-- Card Border Shadow -->
    <div class="row">
        <div class="card data-url="<?= APPURL . "/services/" . $Service->get("id") . "/price" ?>"">
            <div class="row">
                <div class="col-md-4 d-flex align-items-center">
                    <h5 class="card-header"><?= "Giá dịch vụ - " . $Service->get("title") . " - " . $Service->get("group") ?></h5>
                </div>
                <?php if ($AuthUser->isAdmin()) : ?>
                    <div class="col-md-4 d-flex align-items-center">
                        <select class="form-select select" name="site_id">
                            <option value="" selected>All</option>
                            <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                <option value="<?= $site->get("id") ?>"><?= $site->get("domain") ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-md-4 d-flex align-items-center">
                  <select class="form-control select" name="server_id">
                    <?php foreach ($Servers->getDataAs("Server") as $sv) : ?>
                      <option value="<?= $sv->get("id") ?>" <?= 1 == $sv->get("id") ? "selected" : "" ?>><?= $sv->get("name") . " - " . getHost($sv->get("api_url")) ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
            </div>

            <div class="card-datatable">
              <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/price" ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="server_id" value="0">
                <div class="table-responsive">
                    <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                        <table class="dt-complex-header table table-bordered dataTable" id="prices_table">
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