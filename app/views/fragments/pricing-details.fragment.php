<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Bảng giá dịch vụ</h4>

    <!-- Card Border Shadow -->

    <div class="row">
        <div class="text-center">
            <ul class="nav nav-pills card justify-content-center d-inline-block shadow py-1 px-2">
                <?php foreach ($Servers as $key => $server) : ?>
                <li class="nav-item d-inline-block">
                    <a href="#server-tab-<?= $server->id ?>" class="nav-link <?= $key == 0 ? "active" : "" ?>" data-id="<?= $server->id ?>"><?= $server->name ?></a>
                </li>
                <?php endforeach ?>
            </ul>
        </div>

        <div class="tab-content">
            <?php foreach ($Servers as $key => $server) : ?>
                <div class="card tab-pane fade <?= $key == 0 ? "show active" : "" ?>" id="server-tab-<?= $server->id ?>">
                    <div class="card-body">

                    </div>
                </div>
            <?php endforeach ?>

        </div>
    </div>
</div>