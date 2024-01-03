<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Các cài đặt về đơn</h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/settings/" . $page ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Max Hold</label>
                        <input name="max-hold" type="number" value="<?= get_option("MAX_HOLD") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridEmail">Max Order Running</label>
                        <input id="gridEmail" type="number" name="max-order-running" value="<?= get_option("MAX_ORDER_RUNNING") ?>" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Max Order Hold</label>
                        <input name="max-order-hold" type="number" value="<?= get_option("MAX_ORDER_HOLD") ?>" class="form-input">
                        <span>Là giá trị khi mà số đơn run trong hệ thống dưới số này sẽ bắt đầu reset hold và tăng x2 hold lên</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Max Pause</label>
                        <input name="max-pause" type="number" value="<?= get_option("MAX_PAUSE") ?>" class="form-input">
                        <span>Là giá trị khi mà số log trong db của 1 đơn vượt quá số này thì sẽ pause đơn</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Max Status Pause</label>
                        <input name="max-status-pause" type="number" value="<?= get_option("MAX_STATUS_PAUSE") ?>" class="form-input">
                        <span>Là giá trị status = 0 khi log của đơn nào đó quá số này sẽ pause đơn đó lại</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Số tương tác so sánh</label>
                        <input name="max-tt" type="number" value="<?= get_option("MAX_TT") ?>" class="form-input">
                        <span>Là giá trị dùng để so sánh tương tác trên một ngày. Chủ yếu để làm đẹp cái thanh tiến trình ở thống kê tương tác</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Số lương mua tối đa có thể gộp trong 1 nhóm</label>
                        <input name="max-num-amount-group-order" type="number" value="<?= get_option("MAX_NUM_AMOUNT_GROUP_ORDER") ?>" class="form-input">
                        <span>Là giá trị tối đa lượng mua của một nhóm đơn hàng</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <label class="inline-flex">
                        <input type="checkbox" class="form-checkbox" <?= get_option("CLOSE_ORDER_ALL_SERVICES") ? "checked" : "" ?> id="close-order-all-service" name="close-order-all-service" value="1"/>
                        <span>Bảo trì thêm đơn mới cho toàn bộ hệ thống</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                <label for="gridEmail">Proxy dùng để đặt đơn</label>
                    <select name="type_proxy_for_order" class="form-select text-white-dark">
                        <?php $type_proxy = get_option("type_proxy_for_order"); ?>
                        <option value="proxyfb" <?= $type_proxy == "proxyfb" ? "selected" : "" ?>>ProxyFB</option>
                        <option value="shoplike" <?= $type_proxy == "shoplike" ? "selected" : "" ?>>ShopLike</option>
                        <option value="vitechcheap" <?= $type_proxy == "vitechcheap" ? "selected" : "" ?>>Vitechcheap</option>
                    </select>
                    <span>Đây là loại proxy dùng để cho việc đặt đơn</span>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>