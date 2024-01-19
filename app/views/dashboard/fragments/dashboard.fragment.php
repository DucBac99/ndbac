<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Thống kê</h4>


    <!-- Card Border Shadow -->
    <div class="row" id="TotalOrder">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-shopping-cart ti-md"></i></span>
                </div>
                <h4 class="ms-1 mb-0">42</h4>
                </div>
                <p class="mb-1">Đơn mua</p>
                <p class="mb-0">
                <span class="fw-medium me-1">+18.2%</span>
                <small class="text-muted">than last week</small>
                </p>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-warning"
                    ><i class="ti ti-checkup-list ti-md"></i
                    ></span>
                </div>
                <h4 class="ms-1 mb-0">8</h4>
                </div>
                <p class="mb-1">Đơn hoàn tất</p>
                <p class="mb-0">
                <span class="fw-medium me-1">-8.7%</span>
                <small class="text-muted">than last week</small>
                </p>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-danger">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-danger"
                    ><i class="ti ti-arrow-back-up ti-md"></i
                    ></span>
                </div>
                <h4 class="ms-1 mb-0">27</h4>
                </div>
                <p class="mb-1">Đơn hoàn</p>
                <p class="mb-0">
                <span class="fw-medium me-1">+4.3%</span>
                <small class="text-muted">than last week</small>
                </p>
            </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-info"><i class="ti ti-settings ti-md"></i></span>
                </div>
                <h4 class="ms-1 mb-0">13</h4>
                </div>
                <p class="mb-1">Đơn xử lý</p>
                <p class="mb-0">
                <span class="fw-medium me-1">-2.5%</span>
                <small class="text-muted">than last week</small>
                </p>
            </div>
            </div>
        </div>
    </div>

    <div class="row" id="TotalCharge">
        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <i class=" ti ti-coin-yen ph-3x text-danger opacity-75 me-3 font-size-30"></i>
                    <div class="flex-fill">
                        <h6 class="mb-0">Tổng tiền nạp toàn thời gian</h6>
                        <span class="text-muted">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-coin-euro ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

                    <div class="flex-fill">
                        <h6 class="mb-0">Tổng tiền nạp tháng <?= date('m/Y') ?></h6>
                        <span class="text-muted">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-coin-bitcoin ph-2x text-primary opacity-75 me-3 font-size-30"></i>
                    <div class="flex-fill">
                        <h6 class="mb-0">Tổng tiền nạp tuần</h6>
                        <span class="text-muted">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-coin ph-2x text-success opacity-75 me-3 font-size-30"></i>
                    <div class="flex-fill">
                        <h6 class="mb-0">Tổng tiền nạp hôm nay</h6>
                        <span class="text-muted">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="TotalSpend">
        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-yen text-danger opacity-75 me-3 font-size-50"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền tiêu toàn thời gian</h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-euro ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền tiêu tháng <?= date('m/Y') ?></h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-bitcoin ph-2x text-primary opacity-75 me-3 font-size-30"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền tiêu tuần</h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin ph-2x text-success opacity-75 me-3 font-size-30"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền tiêu hôm nay</h6>
                <span class="text-muted">0</span>
                </div>
            </div>

            </div>
        </div>
    </div>

    <div class="row" id="TotalRefund">
        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-yen text-danger opacity-75 me-3 font-size-50"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền hoàn toàn thời gian</h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-euro ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền hoàn tháng <?= date('m/Y') ?></h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin-bitcoin ph-2x text-primary opacity-75 me-3 font-size-30"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền hoàn tuần</h6>
                <span class="text-muted">0</span>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
            <div class="d-flex align-items-center">
                <i class="ti ti-coin ph-2x text-success opacity-75 me-3 font-size-30"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền hoàn hôm nay</h6>
                <span class="text-muted">0</span>
                </div>
            </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">


            <div class="card">
                <div class="card-body text-center">
                    <div class="d-inline-flex bg-success bg-opacity-10 text-success rounded-pill p-2 mb-3 mt-1">
                        <i class=" bx bx-file-blank ph-2x m-1 font-size-30"></i>
                    </div>
                    <h5 class="card-title">API docs</h5>
                    <p class="mb-3">Bạn muốn tích hợp hệ thống chúng tôi vào hệ thống của bạn. Hãy tìm hiểu API docs</p>
                    <a href="https://documenter.getpostman.com/view/4725791/2s9Xy5MWB1" class="btn btn-success mb-1">Xem API docs</a>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <form action="<?= APPURL . "/api-docs" ?>" method="POST">
                    <input type="hidden" name="action" value="renew">
                    <div class="mb-4">
                        <div class="fw-bold border-bottom pb-2 mb-2">API KEY</div>
                        <p class="mb-3">Bên dưới chứa API key của bạn. Nếu trống hãy nhấn "Làm mới API Key" để lấy</p>
                        <div class="flex-fill align-self-center mb-3">
                        <div class="d-flex">
                            <div class="me-2" style="width: 500px">
                            <input type="text" name="api_key" id="api_key" class="form-control" value="<?= $AuthUser->get("api_key") ? ($AuthUser->get("id") . "." . $AuthUser->get("api_key")) : "" ?>" readonly>
                            </div>
                            <a href="javascript:void(0)" class="align-self-center btn-copy fs-sm" data-clipboard-target="#api_key"><u>Nhấn để copy</u></a>
                        </div>
                        </div>
                        <button type="button" class="btn btn-primary js-renew-api-key">Tạo mới API key</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6">

            <!-- Clean blog layout #1 -->
            <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                Hoạt động gần đây
                </h5>
            </div>

            <div class="card-body">

                <div class="list-feed">
                <?php foreach ($fluctuations as $item) : ?>
                    <?php
                    $date = new \Moment\Moment($item->date, date_default_timezone_get());
                    ?>
                    <div class="list-feed-item">
                    <?php
                    $name = $item->firstname . " " . $item->lastname;
                    $len = mb_strlen($name);
                    $display_name = mb_substr($name, 0, 2);
                    if ($len > 2) {
                        $display_name .= str_repeat("*", $len - 2);
                    }
                    ?>
                    <a href="javascript:void(0)"><?= $display_name ?></a> <?= $item->content ?>
                    <div class="text-muted"><?= $date->format("Y-m-d H:i:s") ?></div>
                    </div>
                    <hr />
                <?php endforeach ?>

                </div>
            </div>
            </div>
            <!-- /clean blog layout #1 -->


        </div>
    </div>
</div>