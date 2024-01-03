<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= __("Site Settings") ?></h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/settings/" . $page ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail"><?= __("Site name") ?></label>
                        <input id="gridEmail" type="text" name="name" value="<?= htmlchars($AuthSite->get("settings.site_name")) ?>" class="form-input" placeholder="<?= __("Enter site name here") ?>">
                    </div>
                    <div>
                        <label for="gridEmail"><?= __("Site slogan") ?></label>
                        <input id="gridEmail" type="text" name="slogan" value="<?= htmlchars($AuthSite->get("settings.site_slogan")) ?>" class="form-input" placeholder="<?= __("Enter site slogan here") ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail"><?= __("Site description") ?></label>
                        <textarea id="ctnTextarea" name="description" rows="3" class="form-textarea" required=""><?= htmlchars($AuthSite->get("settings.site_description")) ?></textarea>
                        <span class="text-xs text-white-dark">Độ dài khuyến nghị của mô tả là 150-160 ký tự</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail"><?= __("Keywords") ?></label>
                        <textarea id="ctnTextarea" name="keywords" rows="3" class="form-textarea" required=""><?= htmlchars($AuthSite->get("settings.site_keywords")) ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>