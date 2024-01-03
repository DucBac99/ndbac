<div class="animate__animated p-6">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= $Site->isAvailable() ? htmlchars($Site->get("domain")) : "Site mới" ?></h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/sites/" . ($Site->isAvailable() ? $Site->get("id") : "new") ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">Doamin</label>
                        <input id="gridEmail" type="text" name="domain" value="<?= $Site->get("domain") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridPassword"><?= __("Site name") ?></label>
                        <input id="gridPassword" name="name" type="text" value="<?= htmlchars($Site->get("settings.site_name")) ?>" placeholder="<?= __("Enter site name here") ?>" maxlength="100" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail"><?= __("Site slogan") ?></label>
                        <input id="gridEmail" type="text" name="slogan" value="<?= htmlchars($Site->get("settings.site_slogan")) ?>" placeholder="<?= __("Enter site slogan here") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridPassword"><?= __("Site theme") ?></label>
                        <select id="gridState" name="theme" class="form-select text-white-dark">
                            <option value="" disabled selected>Chọn giao diện</option>
                            <?php foreach ($Themes->getDataAs("Theme") as $theme) : ?>
                            <option value="<?= $theme->get("id") ?>" <?= $Site->get("theme") == $theme->get("idname") ? "selected" : "" ?>><?= $theme->get("idname") ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="gridAddress1"><?= __("Site description") ?></label>
                    <textarea id="ctnTextarea" name="description" rows="3" class="form-textarea" required=""><?= htmlchars($Site->get("settings.site_description")) ?></textarea>
                    <span class="text-xs text-white-dark">Độ dài khuyến nghị của mô tả là 150-160 ký tự</span>
                </div>
                <div>
                    <label for="gridAddress1"><?= __("Keywords") ?></label>
                    <textarea id="ctnTextarea" name="keywords" rows="3" class="form-textarea" required=""><?= htmlchars($Site->get("settings.site_keywords")) ?></textarea>
                </div>
                <div>
                <label for="gridAddress1">Tình trạng</label>
                <select class="form-select text-white-dark">
                    <option value="1" <?= $Site->get("is_active") == 1 ? "selected" : "" ?>>Hoạt động</option>
                    <option value="0" <?= $Site->get("is_active") == 0 ? "selected" : "" ?>>Ngừng hoạt động</option>
                </select>
                </div>
                <div>
                    <label class="mt-1 flex cursor-pointer items-center">
                        <input type="checkbox" class="form-checkbox" <?= $Site->get("options.maintenance_mode") ? "checked" : "" ?> name="maintenance-mode" value="1">
                        <span class="text-white-dark">Bật chế độ bảo trì</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary !mt-6">Submit</button>
            </form>
        </div>
        <template x-if="codeArr.includes('code5')">
            <pre class="code overflow-auto rounded-md !bg-[#191e3a] p-4 text-white">&lt;!-- forms grid --&gt;
    &lt;form class="space-y-5"&gt;
        &lt;div class="grid grid-cols-1 sm:grid-cols-2 gap-4"&gt;
            &lt;div&gt;
                &lt;label for="gridEmail"&gt;Email&lt;/label&gt;
                &lt;input id="gridEmail" type="email" placeholder="Enter Email" class="form-input" /&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for="gridPassword"&gt;Password&lt;/label&gt;
                &lt;input id="gridPassword" type="Password" placeholder="Enter Password" class="form-input" /&gt;
            &lt;/div&gt;
        &lt;/div&gt;
        &lt;div&gt;
            &lt;label for="gridAddress1"&gt;Address&lt;/label&gt;
            &lt;input id="gridAddress1" type="text" placeholder="Enter Address" value="1234 Main St" class="form-input" /&gt;
        &lt;/div&gt;
        &lt;div&gt;
            &lt;label for="gridAddress2"&gt;Address2&lt;/label&gt;
            &lt;input id="gridAddress2" type="text" placeholder="Enter Address2" value="Apartment, studio, or floor" class="form-input" /&gt;
        &lt;/div&gt;
        &lt;div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"&gt;
            &lt;div class="md:col-span-2"&gt;
                &lt;label for="gridCity"&gt;City&lt;/label&gt;
                &lt;input id="gridCity" type="text" placeholder="Enter City" class="form-input" /&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for="gridState"&gt;State&lt;/label&gt;
                &lt;select id="gridState" class="form-select text-white-dark"&gt;
                    &lt;option&gt;Choose...&lt;/option&gt;
                    &lt;option&gt;...&lt;/option&gt;
                &lt;/select&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for="gridZip"&gt;Zip&lt;/label&gt;
                &lt;input id="gridZip" type="text" placeholder="Enter Zip" class="form-input" /&gt;
            &lt;/div&gt;
        &lt;/div&gt;
        &lt;div&gt;
            &lt;label class="flex items-center mt-1 cursor-pointer"&gt;
                &lt;input type="checkbox" class="form-checkbox" /&gt;
                &lt;span class="text-white-dark""&gt;Check me out&lt;/span&gt;
            &lt;/label&gt;
        &lt;/div&gt;
        &lt;button type="submit" class="btn btn-primary !mt-6"&gt;Submit&lt;/button&gt;
    &lt;/form&gt;
    </pre>
        </template>
    </div>
</div>