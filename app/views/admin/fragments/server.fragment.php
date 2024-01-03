<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= $Server->isAvailable() ? htmlchars($Server->get("name")) : "Server mới" ?></h5>
        </div>
        <div class="mb-5">
        <form class="js-ajax-form" action="<?= APPURL . "/servers/" . ($Server->isAvailable() ? $Server->get("id") : "new") ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="mb-4">
                        <label for="gridEmail">Tên</label>
                        <input id="gridEmail" type="text" name="name" value="<?= $Server->get("name") ?>" class="form-input" required>
                    </div>
                    <div class="mb-4">
                        <label for="gridEmail">API URL</label>
                        <input id="gridEmail" type="text" name="api_url" value="<?= $Server->get("api_url") ?>" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="mb-4">
                        <label for="gridEmail">API KEY</label>
                        <input id="gridEmail" type="text" name="api_key" value="<?= $Server->get("api_key") ?>" class="form-input" required>
                    </div>
                    <div class="mb-4">
                        <label for="gridEmail">API USER ID</label>
                        <input id="gridEmail" type="text" name="api_user_id" value="<?= $Server->get("api_user_id") ?>" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <label class="inline-flex">
                        <input type="checkbox" class="form-checkbox" />
                        <span >Hiển thị</span>
                    </label>
                    <label class="inline-flex">
                        <input type="checkbox" class="form-checkbox text-success" />
                        <span>Bảo trì</span>
                    </label>
                    <label class="inline-flex">
                        <input type="checkbox" class="form-checkbox text-secondary" />
                        <span>Cho phép hoàn đơn</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>