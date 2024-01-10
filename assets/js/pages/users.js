$("body").on("click", ".btn-edit-balance", function () {
    $this = $(this);
    var id = $this.data("id");
    var url = $this.data("url");
    var table = $($this.data("table"));
    var card = table.parents(".card");

    $modal = $("#modal_edit_balance");
    $card = $modal.find(".card");
    $modal.find(".modal-title").html("Sửa số dư USER#" + id);
    $modal.find("input[name=user_id]").val(id);
    $modal.find("input[name=money]").val("");
    $modal.modal("show");
});

$("body").on("click", ".btn-gen-qr", function () {
    $this = $(this);
    var id = $this.data("id");
    var url = $this.data("url");
    var table = $($this.data("table"));
    var card = table.parents(".card");

    addLoading(card);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "jsonp",
        data: {
            action: "gen_qr",
            id: id,
        },
        error: function () {
            removeLoading(card);
            Swal.fire({
                title: "Oops...",
                text:  "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!",
                icon: "error",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
        },
        success: function (resp) {
            console.log("11111");
            removeLoading(card);
            if (resp.result == 1) {
                console.log(resp);
                $modal = $("#modal_gen_qr");
                $modal.modal("show");
                $modal.find("img").attr("src", resp.url);
                $modal.find("#title_email").html(resp.email);
            } else {
                Swal.fire({
                    title: "Oops...",
                    text:  resp.msg,
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                });
            }
        },
    });
});

$(".select2").on("change", function (e) {
    table_data.ajax.reload();
});

$(function () {
    $table = $("#users_table");
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
                    d.site_id = $("select[name=site_id] option:selected").val();

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
                        json.data[i].balance = parseInt(json.data[i].balance);
                        json.data[i].total_deposit = parseInt(json.data[i].total_deposit);
                        json.data[i].id = parseInt(json.data[i].id);
                        json.data[i].is_active = json.data[i].is_active == "1";
                        }
                        data.data = json.data;
                        data.recordsTotal = 5000000;
                        data.recordsFiltered = 5000000;
                    } else {
                        data.data = [];
                        data.recordsTotal = 0;
                        data.recordsFiltered = 0;
                        Swal.fire("Oops...", json.msg, "error");
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
                    targets: 0,
                },
                {
                    data: "id",
                    render: function (t, a, e) {
                        return `#${e.id}`;
                    },
                },
                {
                    data: "email",
                },
                {
                    data: "title",
                    render: function (t, a, e) {
                        return `<span class="badge bg-label-${e.color}">${t}</span>`;
                    },
                },
                {
                    data: "domain",
                    render: function (t, a, e) {
                        return `<a target="_blank" href="http://${t}">${t}</a>`;
                    },
                },
                {
                    data: "firstname",
                },
                {
                    data: "lastname",
                },
                {
                    data: "is_active",
                    render: function (t, a, e) {
                        if (t) {
                        return `<span class="font-size-12 badge bg-success bg-opacity-10 text-success">Hoạt động</span>`;
                        } else {
                        return `<span class="font-size-12 badge bg-danger bg-opacity-10 text-danger">Dừng hoạt động</span>`;
                        }
                    },
                },
                {
                    data: "balance",
                    render: function (t, a, e) {
                        return `${t.format()}`;
                    },
                },
                {
                    data: "total_deposit",
                    render: function (t, a, e) {
                        return `${t.format()}`;
                    },
                },
                {
                    orderable: false,
                    data: "id",
                    render: function (t, a, e) {
                        return `<div class="d-flex gap-2">
                                    <div class="edit">
                                        <a href="${$table.data(
                                            "url"
                                        )}/${t}" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-primary edit-item-btn"  data-bs-popup="tooltip" title="Sửa"><i class="ti ti-edit font-size-16 align-middle "></i></a>
                                    </div>
                                    <div class="statistic">
                                        <a href="${$table.data(
                                            "url"
                                        )}/${t}/statistic" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-success"  data-bs-popup="tooltip" title="Thống kê"><i class="ti ti-chart-bar font-size-16 align-middle "></i></a>
                                    </div>
                                    <div class="balance">
                                        <button data-url="${$table.data(
                                            "url"
                                        )}" data-id="${t}" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-warning btn-edit-balance" data-bs-popup="tooltip" title="Thay đổi số dư"><i class="ti ti-coins font-size-16 align-middle "></i></button>
                                    </div>
                                    <div class="login">
                                        <button data-url="${$table.data(
                                            "url"
                                        )}" data-id="${t}" class="btn btn-icon btn-sm waves-effect waves-light btn-outline-secondary btn-gen-qr" data-bs-popup="tooltip" title="Tạo code đăng nhập"><i class="ti ti-qrcode font-size-16 align-middle "></i></button>
                                    </div>
                                    <div class="remove">
                                        <button class="btn btn-icon btn-sm waves-effect waves-light btn-outline-danger js-remove-item" data-table="#users_table" data-url="${$table.data(
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
                removeLoading($table);
                Sub99.initToolTips();
            },
            initComplete: function (a, e) {
                $("#users_table_filter input").unbind(),
                $("#users_table_filter input").bind("keyup", function (a) {
                    13 == a.keyCode && table_data.search(this.value).draw();
                });
            },
        });
});

$("body").on("click", ".js-change-analytics", function () {
    $this = $(this);
    var url = $this.data("url");
    var table = $($this.data("table"));
    var card = table.parents(".card");
    var modal = $this.parents(".modal");
    var value_ids = [];
    $($this.data("table") + " input:checkbox[name=customCheck]:checked").each(
        function () {
            value_ids.push($(this).val());
        }
    );

    if (value_ids.length == 0) {
        Swal.fire({
            title: "Oops...",
            text:  "Chưa chọn danh sách",
            icon: "info",
            customClass: {
                confirmButton: "btn btn-primary waves-effect waves-light"
            },
            buttonsStyling: !1
        });
        return;
    }
    $(".modal").modal("hide");
    addLoading(card);
    $.ajax({
        url: url,
        type: "POST",
        dataType: "jsonp",
        data: {
            action: "change_analytics",
            ids: value_ids,
            "has-analytics": modal.find("input[name=has-analytics]").is(":checked"),
        },
        error: function () {
            removeLoading(card);
            Swal.fire({
                title: "Oops...",
                text:  resp.msg,
                icon: "error",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
        },
        success: function (resp) {
            removeLoading(card);
            if (resp.result == 1) {
            table_data.ajax.reload();
            Swal.fire({
                title: "Success",
                text: resp.msg,
                icon: "success",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
            } else {
                Swal.fire({
                    title: "Oops...",
                    text:  resp.msg,
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                });
            }
        },
    });
});
