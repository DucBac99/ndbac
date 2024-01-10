<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $Role->isAvailable() ? htmlchars($Role->get("title")) : "Vai trò mới" ?></h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="card mb-4">
        <h5 class="card-header">Form nhập liệu</h5>
        <!-- Account -->
        <hr class="my-0">
        <div class="card-body">
            <form class="js-ajax-form" action="<?= APPURL . "/roles/" . ($Role->isAvailable() ? $Role->get("id") : "new") ?>" method="POST">
                <input type="hidden" name="action" value="save">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label fw-semibold">ID name (*)</label>
                        <input type="text" name="idname" value="<?= $Role->get("idname") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label fw-semibold">Tiêu đề (*)</label>
                        <input type="text" name="title" value="<?= $Role->get("title") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label fw-semibold">Điều kiện nạp</label>
                        <input type="number" name="amount" value="<?= $Role->get("amount") ?>" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label fw-semibold">Màu sắc (*)</label>
                        <select name="color" class="form-select" required>
                            <option value="primary" <?= $Role->get("color") == "primary" ? "selected" : "" ?>>Primary</option>
                            <option value="secondary" <?= $Role->get("color") == "secondary" ? "selected" : "" ?>>Secondary</option>
                            <option value="danger" <?= $Role->get("color") == "danger" ? "selected" : "" ?>>Danger</option>
                            <option value="success" <?= $Role->get("color") == "success" ? "selected" : "" ?>>Success</option>
                            <option value="info" <?= $Role->get("color") == "info" ? "selected" : "" ?>>Info</option>
                            <option value="pink" <?= $Role->get("color") == "pink" ? "selected" : "" ?>>Pink</option>
                            <option value="purple" <?= $Role->get("color") == "purple" ? "selected" : "" ?>>Purple</option>
                            <option value="indigo" <?= $Role->get("color") == "indigo" ? "selected" : "" ?>>Indio</option>
                            <option value="teal" <?= $Role->get("color") == "teal" ? "selected" : "" ?>>Teal</option>
                            <option value="yellow" <?= $Role->get("color") == "yellow" ? "selected" : "" ?>>Yellow</option>
                        </select>
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