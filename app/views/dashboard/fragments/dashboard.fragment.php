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
                    <i class=" ti ti-coin-yen text-danger opacity-75 me-3"></i>
                    <div class="flex-fill">
                        <h6 class="mb-0">Tổng tiền nạp toàn thời gian</h6>
                        <span class="text-muted">111,111</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-2">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <i class="ti ti-dollar ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

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
                    <i class="ti ti-pound ph-2x text-primary opacity-75 me-3 font-size-30"></i>
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
                    <i class="ti ti-rupee ph-2x text-success opacity-75 me-3 font-size-30"></i>
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
                <i class="bx bx-dollar ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

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
                <i class="bx bx-pound ph-2x text-primary opacity-75 me-3 font-size-30"></i>
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
                <i class="bx bx-rupee ph-2x text-success opacity-75 me-3 font-size-30"></i>
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
                <i class="bx bx-dollar ph-2x text-indigo opacity-75 me-3 font-size-30"></i>

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
                <i class="bx bx-pound ph-2x text-primary opacity-75 me-3 font-size-30"></i>
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
                <i class="bx bx-rupee ph-2x text-success opacity-75 me-3 font-size-30"></i>
                <div class="flex-fill">
                <h6 class="mb-0">Tổng tiền hoàn hôm nay</h6>
                <span class="text-muted">0</span>
                </div>
            </div>

            </div>
        </div>
    </div>

    
</div>