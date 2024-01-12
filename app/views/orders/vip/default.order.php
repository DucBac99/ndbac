<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Đơn hàng - <span class="fw-normal"><?= htmlchars($Service->get("title") . " - " . $Service->get("group")) ?></span></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Thêm mới đơn hàng</h5>
                <!-- Account -->
                <hr class="my-0">
                <div class="card-body">
                    <form class="js-ajax-form" action="<?= APPURL . "/orders/" . $Service->get("group") . "/" . $Service->get("idname") . "/new" ?>" method="POST" novalidate>
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="idname" value="<?= $Service->get("idname") ?>">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="fw-bold form-label">Facebook ID</label>
                                    <div class="input-group">
                                        <input type="text" name="seeding_uid" value="" class="form-control" placeholder="Nhập ID cần tăng" required>
                                        <button class="btn btn-info btn-loading btn-get-id" data-initial-text="Get ID" data-loading-text="<i class='spinner-border'></i>" type="button">Get ID</button>
                                    </div>
                                    <div class="form-text">ID phải là 1 dãy số. Nếu nhập Link hãy nhấn GET ID để lấy ID</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $realAmount = get_real_amount($Servers, 1, $AuthUser->get('idname'));
                                ?>
                                <div class="mb-3">
                                    <label class="fw-bold form-label">Số lượng</label>
                                    <input type="number" name="order_amount" min="<?= $realAmount->min ?>" max="<?= $realAmount->max ?>" class="form-control input-number" placeholder="Nhập số lượng cần tăng" required>
                                    <div class="form-text">Số lượng tối thiểu bạn có thể mua là: <strong id="min_text"><?= number_format($realAmount->min) ?></strong></div>
                                    <div class="form-text">Số lượng tối đa bạn có thể mua là: <strong id="max_text"><?= number_format($realAmount->max) ?></strong></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="fw-bold form-label">Số tháng</label>
                                    <input type="number" name="month" min="1" class="form-control input-number" placeholder="Nhập số tháng cần tăng" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="fw-bold form-label">Server</label>
                                    <?php $index = 0;
                                    foreach ($Servers as $sv) : ?>
                                        <?php if (!empty($sv->options->public)) : ?>
                                        <?php
                                            $index++;
                                            $realPrice = get_real_price($Servers, $sv->id, $AuthUser->get('idname'));
                                            $realAmount = get_real_amount($Servers, $sv->id, $AuthUser->get('idname'));
                                        ?>
                                        <div class="form-check mb-2">
                                            <input type="radio" class="form-check-input js-choose-server" data-price="<?= $realPrice ?>" data-min="<?= $realAmount->min ?>" data-max="<?= $realAmount->max ?>" name="server_id" id="sv_<?= $sv->id ?>" value="<?= $sv->id ?>" required <?= $index == 1 ? "checked" : "" ?>>
                                            <label class="form-check-label" for="sv_<?= $sv->id ?>">
                                                <?= $sv->name ?>
                                                <span class="font-size-12 badge bg-label-primary bg-opacity-20 text-primary"><?= number_format($realPrice) . "đ" ?></span>
                                                <?php if ($sv->options->maintain) : ?>
                                                    <span class="font-size-12 badge bg-label-danger bg-opacity-20 text-danger">Bảo trì</span>
                                                <?php endif ?>
                                            </label>
                                        </div>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                    <div class="form-text">Sẽ có giá khác nhau cho mỗi cấp tài khoản, cũng như trên từng server. <a href="<?= APPURL . "/pricing-details" ?>" target="_blank">Xem thêm tại đây</a></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="fw-bold form-label">Ghi chú (không bắt buộc)</label>
                                    <textarea type="text" name="note" class="form-control" placeholder="Nhập nội dung cần ghi chú" rows="5" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <span class="alert-icon text-success me-2">
                                        <i class="ti ti-coin"></i>
                                    </span>
                                    <span class="fw-semibold">Tạm tính: <span id="total_price_order">0</span>đ</span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <span class="alert-icon text-danger me-2">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                    <span class="fw-semibold">Bảo hành: <?= $Service->get("warranty") ?> ngày</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="reset" class="btn btn-light">Làm lại</button>
                            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Thêm đơn</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"> Ghi chú dịch vụ</h5>
                </div>
                <hr class="my-0">
                <div class="card-body">
                <?= $Service->get("note") ?>
                </div>
            </div>
        </div>
    </div>
</div>