<div class="container-fluid">
    <div class="page-header dash-breadcrumb">
        <div class="row">
        <div class="col-6">
            <h3 class="f-w-600">Các cài đặt về đơn</h3>
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
                        <h5>Các cài đặt về đơn</h5>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form class="theme-form mega-form js-ajax-form" action="<?= APPURL . "/settings/" . $page ?>" method="POST">
                            <input type="hidden" name="action" value="save">
                            <div class="mb-3">
                                <label class="col-form-label">Max Hold</label>
                                <input class="form-control" type="number" name="max-hold" value="<?= get_option("MAX_HOLD") ?>">
                                <div class="form-text">Là giá trị cộng thêm ( vào max_hold ở mỗi loại dịch vụ) khi đơn running của từng loại <= (MAX_ORDER_RUNNING)</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Max Order Running</label>
                                <input class="form-control" type="number" name="max-order-running" value="<?= get_option("MAX_ORDER_RUNNING") ?>">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Max Order Hold</label>
                                <input class="form-control" type="number" name="max-order-hold" value="<?= get_option("MAX_ORDER_HOLD") ?>">
                                <div class="form-text">Là giá trị khi mà số đơn run trong hệ thống dưới số này sẽ bắt đầu reset hold và tăng x2 hold lên</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Max Pause</label>
                                <input class="form-control" type="number" name="max-pause" value="<?= get_option("MAX_PAUSE") ?>">
                                <div class="form-text">Là giá trị khi mà số log trong db của 1 đơn vượt quá số này thì sẽ pause đơn</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Max Status Pause</label>
                                <input class="form-control" type="number" name="max-status-pause" value="<?= get_option("MAX_STATUS_PAUSE") ?>">
                                <div class="form-text">Là giá trị status = 0 khi log của đơn nào đó quá số này sẽ pause đơn đó lại</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Số tương tác so sánh</label>
                                <input class="form-control" type="number" name="max-tt" value="<?= get_option("MAX_TT") ?>">
                                <div class="form-text">Là giá trị dùng để so sánh tương tác trên một ngày. Chủ yếu để làm đẹp cái thanh tiến trình ở thống kê tương tác</div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Số lương mua tối đa có thể gộp trong 1 nhóm</label>
                                <input class="form-control" type="number" name="max-num-amount-group-order" value="<?= get_option("MAX_NUM_AMOUNT_GROUP_ORDER") ?>">
                                <div class="form-text">Là giá trị tối đa lượng mua của một nhóm đơn hàng</div>
                            </div>
                            <div class="mb-3">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox-primary-1" type="checkbox" class="form-check-input" <?= get_option("CLOSE_ORDER_ALL_SERVICES") ? "checked" : "" ?> id="close-order-all-service" name="close-order-all-service" value="1">
                                    <label for="checkbox-primary-1">Bảo trì thêm đơn mới cho toàn bộ hệ thống</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Proxy dùng để đặt đơn</label>
                                <select class="form-select" name="type_proxy_for_order">
                                    <?php $type_proxy = get_option("type_proxy_for_order"); ?>
                                    <option value="proxyfb" <?= $type_proxy == "proxyfb" ? "selected" : "" ?>>ProxyFB</option>
                                    <option value="shoplike" <?= $type_proxy == "shoplike" ? "selected" : "" ?>>ShopLike</option>
                                    <option value="vitechcheap" <?= $type_proxy == "vitechcheap" ? "selected" : "" ?>>Vitechcheap</option>
                                </select>
                                <div class="form-text">Đây là loại proxy dùng để cho việc đặt đơn</div>
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
</div>