<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Hiệu ứng giao diện</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Hiệu ứng giao diện</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/themes/effects" ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label fw-semibold">Hiệu ứng</label>
                        <select class="form-control form-select" name="effect_name" required>
                            <option value="" disabled selected>Chọn hiệu ứng</option>
                            <?php require_once(APPPATH . "/inc/effects.inc.php") ?>
                            <?php foreach ($effects_list as $effect) : ?>
                                <option value="<?= $effect["name"] ?>" data-thumb="<?= $effect["thumb"] ?>" <?= $effect["name"] == $AuthSite->get("options.effect") ? "selected" : "" ?>><?= $effect["name"] ?></option>
                            <?php endforeach ?>
                        </select>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Lưu thay đổi</button>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6" id="card_thumb">

                    </div>
                </div>
            </form>
        </div>
        <!-- /Account -->
        </div>
    </div>
</div>