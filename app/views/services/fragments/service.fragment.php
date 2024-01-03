<div class="grid grid-cols-1 gap-6 p-5 lg:grid-cols-2">
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light"><?= $Service->isAvailable() ? htmlchars($Service->get("title") . " - " . $Service->get("group")) : "Dịch vụ mới" ?></h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5 js-ajax-form"  action="<?= APPURL . "/services/" . ($Service->isAvailable() ? $Service->get("id") : "new") ?>" method="POST">
            <input type="hidden" name="action" value="save">
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div>
                        <label for="gridEmail">Tiêu đề gốc</label>
                        <input id="gridEmail" type="text" name="title" value="<?= $Service->get("title") ?>" class="form-input">
                    </div>
                    <?php if ($Service->isAvailable()) : ?>
                    <div>
                        <label for="gridEmail">Tiêu đề khác</label>
                        <input id="gridEmail" type="text" name="title_extra" value="<?= $Service->get("title_extra") ?>" class="form-input">
                    </div>
                    <?php endif ?>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">Số ngày bảo hành</label>
                        <input id="gridEmail" type="number" name="warranty" value="<?= $Service->get("warranty") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridEmail">Icon</label>
                        <input id="gridEmail" type="text" name="icon" value="<?= $Service->get("icon") ?>" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">ID Name</label>
                        <input id="gridEmail" type="text" name="idname" value="<?= $Service->get("idname") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridEmail">Group</label>
                        <select name="group" class="form-select text-white-dark">
                            <option value="facebook" <?= $Service->get("group") == "facebook" ? "selected" : "" ?>>Facebook</option>
                            <option value="youtube" <?= $Service->get("group") == "youtube" ? "selected" : "" ?>>Youtube</option>
                            <option value="tiktok" <?= $Service->get("group") == "tiktok" ? "selected" : "" ?>>TikTok</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="gridEmail">Tốc độ</label>
                        <input id="" type="text" name="speed" value="<?= $Service->get("speed") ?>" class="form-input">
                    </div>
                    <div>
                        <label for="gridEmail">Max hold</label>
                        <input id="" type="text" name="max_hold" value="<?= $Service->get("max_hold") ?>" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                    <div class="form-group mb-3">
                        <label class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="public" name="public" value="1" <?= $Service->get("is_public") ? "checked" : "" ?>>
                            <span class="form-check-label">Hiện thị</span>
                            <div class="form-text">Để tránh hiểu nhầm. Hiện thị ở đây tức là áp dụng cho toàn bộ cả server. Nếu ko tích thì sẽ ẩn toàn bộ. tích thì hiện</div>
                        </label>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="maintaince" name="maintaince" value="1" <?= $Service->get("is_maintaince") ? "checked" : "" ?>>
                            <span class="form-check-label">Bảo trì</span>
                        </label>
                        <div class="form-text">Bảo trì cũng tương tự là áp dụng cho toàn bộ các server. Nếu muốn tích lẻ thì phần Server đấu hãy setup chúng</div>
                    </div>
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

    <div class="panel">
    <?php if ($Service->isAvailable()) : ?>
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Cài đặt thông báo</h5>
        </div>
        <div class="mb-5 flex items-center justify-between">
            <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/servers" ?>" method="POST">
                <input type="hidden" name="action" value="note">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <p class="fw-semibold">Nội dung thông báo</label>
                            <textarea id="ctnTextarea" rows="3" name="note" class="form-textarea ckeditor" required=""><?= $Service->get("note") ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    <?php endif ?>
    </div>

    <div class="panel">
    <?php if ($Service->isAvailable()) : ?>
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center">
                <h5 class="text-lg pr-3 font-semibold dark:text-white-light">Server đấu</h5>
                <div class="ml-5">
                    <select name="group" class="form-select text-white-dark">
                        <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                            <option value="<?= $site->get("id") ?>" <?= $AuthSite->get("id") == $site->get("id") ? "selected" : "" ?>><?= $site->get("domain") ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-5 flex items-center justify-between">
            <form class="js-ajax-form" action="<?= APPURL . "/services/" . $Service->get("id") . "/servers" ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="site_id" value="0">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="servers_table" data-url="<?= APPURL . "/services" ?>">
                        <thead>
                            <tr>
                                <th>Tên server</th>
                                <th>Miền server</th>
                                <th>Bảo trì</th>
                                <th>Hiện thị</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    <?php endif ?>
    </div>

</div>