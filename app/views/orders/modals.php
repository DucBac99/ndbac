<?php
    $servicesModal = DB::table(TABLE_PREFIX . TABLE_SERVICES)
    ->where(TABLE_PREFIX . TABLE_SERVICES . ".is_public", "=", 1)
    ->select([
        TABLE_PREFIX . TABLE_SERVICES . ".id",
        TABLE_PREFIX . TABLE_SERVICES . ".idname",
    ])
    ->get();
?>
<div id="modal_edit_order" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content card">
            <div class="modal-header bg-primary text-white border-0">
                <h6 class="modal-title text-white">Primary header</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="js-ajax-form" action="<?= $uri ?>" method="POST">
                <div class="modal-body">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="order_id" value="0">

                <div class="mb-3">
                    <label class="fw-semibold">Status</label>
                    <select class="form-control select" name="status_edit">
                    <option value="" selected>All</option>
                    <?php foreach ($order_status as $type) : ?>
                        <option value="<?= $type ?>"><?= $type ?></option>
                    <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Note Extra</label>
                    <textarea class="form-control" name="note_extra" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Seeding Type</label>
                    <select class="form-control select" name="seeding_type">
                    <?php foreach ($servicesModal as $service) : ?>
                        <option value="<?= $service->idname ?>"><?= $service->idname ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_edit_orders" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content card">
            <div class="modal-header bg-primary text-white border-0">
                <h6 class="modal-title text-white">Sửa danh sách đơn hàng</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $uri ?>" method="POST">
                <div class="modal-body">
                <input type="hidden" name="action" value="edit_bulk">

                <div class="mb-3">
                    <label class="fw-semibold">Status</label>
                    <select class="form-control select" name="status_edit">
                    <option value="" selected>All</option>
                    <?php foreach ($order_status as $type) : ?>
                        <option value="<?= $type ?>"><?= $type ?></option>
                    <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Note Extra</label>
                    <textarea class="form-control" name="note_extra" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Seeding Type</label>
                    <select class="form-control select" name="seeding_type">
                    <?php foreach ($servicesModal as $service) : ?>
                        <option value="<?= $service->idname ?>" <?= $Service->get("idname") == $service->idname ? "selected" : "" ?>><?= $service->idname ?></option>
                    <?php endforeach ?>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary js-edit-list-order" data-url="<?= $uri ?>" data-table="#orders_table">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
