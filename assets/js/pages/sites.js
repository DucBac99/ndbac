$(function () {
    $table = $("#sites_table");
    $card = $table.parents(".card:first");
    table_data = $table
        .on("preXhr.dt", function (t, a, e) {
            addLoading($table);
        })
        .DataTable({
            stateSave: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: $table.data("url"),
                data: function (d) {
                    d.search = d.search.value;
                    d.order = {
                        column: d.columns[d.order[0].column].data,
                        dir: d.order[0].dir,
                    };
                    delete d.columns;
                },
                dataFilter: function (d) {
                    var json = JSON.parse(d);
                    var data = {};
                    if (json.result) {
                        for (const i in json.data) {
                        json.data[i].DT_RowAttr = {
                            "data-id": json.data[i].id,
                        };
                        json.data[i].totalUsers = parseInt(json.data[i].totalUsers);
                        json.data[i].id = parseInt(json.data[i].id);
                        json.data[i].is_active = json.data[i].is_active == "1";
                        json.data[i].is_root = json.data[i].is_root == "1";
                        json.data[i].updated_at = moment
                            .utc(json.data[i].updated_at)
                            .format("YYYY-MM-DD HH:mm:ss");
                        }
                        data.data = json.data;
                        data.recordsTotal = 5000000;
                        data.recordsFiltered = 5000000;
                    } else {
                        data.data = [];
                        data.recordsTotal = 0;
                        data.recordsFiltered = 0;
                        Swal.fire({
                            title: "Oops...",
                            text:  json.msg,
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-primary waves-effect waves-light"
                            },
                            buttonsStyling: !1
                        });
                    }
                    return JSON.stringify(data);
                },
            },
            order: [[1, "desc"]],
            oLanguage: {
                sInfo: "",
            },
            pagingType: "simple",
            pageLength: 20,
            lengthMenu: [
                [20, 30, 40, 50],
                [20, 30, 40, 50],
            ],
            columnDefs: [
                {
                    orderable: false,
                    data: "id",
                    render: function (t, a, e) {
                        return `  <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="customCheck" id="data_id_${e.id}" value="${e.id}">
                                            <span class="form-check-label">&nbsp;</span>
                                        </label>`;
                    },
                },
                {
                    data: "id",
                    render: function (t, a, e) {
                        return `#${e.id}`;
                    },
                },
                {
                    data: "domain",
                    render: function (t, a, e) {
                        return `<a target="_blank" href="http://${t}">${t}</a>`;
                    },
                },
                {
                    data: "is_active",
                    render: function (t, a, e) {
                        if (t) {
                            return `<span class="font-size-12 badge bg-success bg-opacity-10 text-success">Hoạt động</span>`;
                        }
                        return `<span class="font-size-12 badge bg-danger bg-opacity-10 text-danger">Bảo trì</span>`;
                    },
                },
                {
                    data: "email",
                },
                {
                    data: "totalUsers",
                    render: function (t, a, e) {
                        return `${t.format()}`;
                    },
                },
                {
                    data: "updated_at",
                },
                {
                    orderable: false,
                    data: "id",
                    render: function (t, a, e) {
                        return `<div class="d-flex gap-2">
                                    <div class="edit">
                                        <button data-url="${$table.data(
                                            "url"
                                        )}" data-id="${t}"  data-domain="${
                            e.domain
                            }" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-warning js-set-admin" data-bs-popup="tooltip" title="Set admin"><i class="ti ti-shield-code font-size-16 align-middle "></i></button>
                                    </div>
                                    <div class="statistic">
                                        <a href="${$table.data(
                                        "url"
                                        )}/${t}/statistic" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-success"  data-bs-popup="tooltip" title="Thống kê"><i class="ti ti-chart-bar"></i></a>
                                    </div>
                                    <div class="edit">
                                        <button data-url="${$table.data(
                                            "url"
                                        )}" data-id="${t}"  data-domain="${
                            e.domain
                            }" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-dark js-change-domain" data-bs-popup="tooltip" title="Đổi tên miền"><i class="ti ti-arrows-exchange "></i></button>
                                    </div>
                                    <div class="edit">
                                        <a href="${$table.data(
                                            "url"
                                        )}/${t}" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-primary edit-item-btn" data-bs-popup="tooltip" title="Sửa"><i class="ti ti-edit font-size-16 align-middle "></i></a>
                                    </div>
                                    <div class="remove">
                                        <button class="btn btn-icon btn-sm waves-effect waves-light btn-outline-danger js-remove-item" data-table="#sites_table" data-url="${$table.data(
                                            "url"
                                        )}" data-id="${t}" data-bs-popup="tooltip" title="Xoá"><i class="ti ti-trash font-size-16 align-middle "></i></button>
                                    </div>
                                </div>`;
                    },
                },
            ].map((item, index) => {
                item.targets = index;
                return item;
            }),
            drawCallback: function () {
                if (table_data.data().length == 0) {
                $(".next").addClass("disabled");
                }
                Sub99.initToolTips();
                removeLoading($table);
            },
            initComplete: function (a, e) {
                $("#sites_table_filter input").unbind(),
                $("#sites_table_filter input").bind("keyup", function (a) {
                    13 == a.keyCode && table_data.search(this.value).draw();
                });
            },
        });
});

$("body").on("click", ".js-set-admin", function () {
    $this = $(this);
    var title = $this.data("domain");
    Swal.fire({
        title: 'Set admin cho "' + title + '"',
        input: "text",
        inputPlaceholder: "Nhập User ID",
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: !0,
        confirmButtonText: "Set admin",
        cancelButtonText: "Bỏ qua",
        showLoaderOnConfirm: !0,
        customClass: {
            confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            cancelButton: "btn btn-label-danger waves-effect waves-light"
        },
        preConfirm: function (t) {
        if (!t) {
            Swal.showValidationMessage("Vui lòng nhập user_id!");
            return;
        }
        return $.ajax({
            url: $this.data("url"),
            type: "post",
            dataType: "jsonp",
            data: {
            action: "set_admin",
            id: $this.data("id"),
            user_id: t,
            },
        })
            .done(function (resp) {
                if (!resp.result) {
                    Swal.showValidationMessage(resp.msg);
                }
            })
            .fail(function () {
                Swal.showValidationMessage(
                    "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!"
                );
            });
        },
        allowOutsideClick: false,
    }).then(function (t) {
        if (!t.value) return;
        Swal.fire("Thành Công", "Set admin thành công", "success");
    });
});

$("body").on("click", ".js-change-domain", function () {
    $this = $(this);
    var title = $this.data("domain");
    Swal.fire({
        title: 'Chuyển tên miền cho "' + title + '"',
        input: "text",
        inputPlaceholder: "Nhập tên miền mới",
        inputAttributes: {
            autocapitalize: "off"
        },
        showCancelButton: !0,
        confirmButtonText: "Chuyển tên miền",
        cancelButtonText: "Bỏ qua",
        showLoaderOnConfirm: !0,
        customClass: {
            confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            cancelButton: "btn btn-label-danger waves-effect waves-light"
        },
        preConfirm: function (t) {
        if (!t) {
            Swal.showValidationMessage("Vui lòng nhập domain!");
            return;
        }
        if (t == title) {
            Swal.showValidationMessage("Bạn đang nhập domain cũ!");
            return;
        }
        return $.ajax({
            url: $this.data("url"),
            type: "post",
            dataType: "jsonp",
            data: {
            action: "change_domain",
            id: $this.data("id"),
            domain: t,
            },
        })
            .done(function (resp) {
                if (!resp.result) {
                    Swal.showValidationMessage(resp.msg);
                }
            })
            .fail(function () {
                Swal.showValidationMessage(
                    "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!"
                );
            });
        },
        allowOutsideClick: false,
    }).then(function (t) {
        if (!t.value) return;
        Swal.fire("Thành Công", "Đổi tên miền thành công", "success");
    });
});
