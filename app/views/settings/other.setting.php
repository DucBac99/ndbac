<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Các cài đặt khác</h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/settings/" . $page ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                    <label class="inline-flex">
                        <input type="checkbox" class="form-checkbox" <?= $AuthSite->get("options.maintenance_mode") ? "checked" : "" ?>  id="maintenance-mode" name="maintenance-mode" value="1"/>
                        <span>Bật chế độ bảo trì</span>
                    </label>
                    </div>
                    <div>
                        <label for="gridEmail">Support Url</label>
                        <input id="gridEmail" type="text" name="support-url" value="<?= $AuthSite->get("options.support_url") ?>" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Link hướng dẫn</label>
                        <input name="instruction-url" type="text" value="<?= $AuthSite->get("options.instruction_url") ?>" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">License Key CKFinder</label>
                        <input name="license-key" type="text" value="<?= $AuthSite->get("options.licenseKey") ?>" class="form-input">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>