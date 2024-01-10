<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Các cài đặt về đơn</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Các cài đặt về đơn</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Max Hold</label>
                        <input class="form-control" name="max-hold fw-semibold" type="number" value="<?= get_option("MAX_HOLD") ?>">
                        <div class="form-text">Là giá trị cộng thêm ( vào max_hold ở mỗi loại dịch vụ) khi đơn running của từng loại <= (MAX_ORDER_RUNNING)</div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Max Order Running</label>
                        <input class="form-control" name="max-order-running" type="number" value="<?= get_option("MAX_ORDER_RUNNING") ?>">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Max Order Hold</label>
                        <input class="form-control" name="max-order-hold" type="number" value="<?= get_option("MAX_ORDER_HOLD") ?>">
                        <div class="form-text">Là giá trị khi mà số đơn run trong hệ thống dưới số này sẽ bắt đầu reset hold và tăng x2 hold lên</div>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Max Pause</label>
                        <input class="form-control" name="max-pause" type="number" value="<?= get_option("MAX_PAUSE") ?>">
                        <div class="form-text">Là giá trị khi mà số log trong db của 1 đơn vượt quá số này thì sẽ pause đơn</div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Max Status Pause</label>
                        <input class="form-control" name="max-status-pause" type="number" value="<?= get_option("MAX_STATUS_PAUSE") ?>">
                        <div class="form-text">Là giá trị status = 0 khi log của đơn nào đó quá số này sẽ pause đơn đó lại</div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Số tương tác so sánh</label>
                        <input class="form-control" name="max-tt" type="number" value="<?= get_option("MAX_TT") ?>">
                        <div class="form-text">Là giá trị dùng để so sánh tương tác trên một ngày. Chủ yếu để làm đẹp cái thanh tiến trình ở thống kê tương tác</div>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Số lương mua tối đa có thể gộp trong 1 nhóm</label>
                        <input class="form-control" name="max-num-amount-group-order" type="number" value="<?= get_option("MAX_NUM_AMOUNT_GROUP_ORDER") ?>">
                        <div class="form-text">Là giá trị tối đa lượng mua của một nhóm đơn hàng</div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" <?= get_option("CLOSE_ORDER_ALL_SERVICES") ? "checked" : "" ?> id="close-order-all-service" name="close-order-all-service" value="1">
                            <label class="form-check-label" for="defaultCheck1">Bảo trì thêm đơn mới cho toàn bộ hệ thống</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="lastName" class="form-label fw-semibold">Proxy dùng để đặt đơn</label>
                        <select class="form-select" name="type_proxy_for_order">
                            <?php $type_proxy = get_option("type_proxy_for_order"); ?>
                            <option value="proxyfb" <?= $type_proxy == "proxyfb" ? "selected" : "" ?>>ProxyFB</option>
                            <option value="shoplike" <?= $type_proxy == "shoplike" ? "selected" : "" ?>>ShopLike</option>
                            <option value="vitechcheap" <?= $type_proxy == "vitechcheap" ? "selected" : "" ?>>Vitechcheap</option>
                        </select>
                        <div class="form-text">Đây là loại proxy dùng để cho việc đặt đơn</div>
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