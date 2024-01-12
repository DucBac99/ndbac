$("body").on("click", ".btn-refund-order", function () {
    var url = $(this).data("url");
    var id = $(this).data("id");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    Swal
        .fire({
            title: "Bạn có chắc hoàn không?",
            text: "Chỉ những đơn trong trạng thái RUNNING, PENDING mới có thể hoàn!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonText: "Có, Hoàn",
            cancelButtonText: "Huỷ",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-label-secondary waves-effect waves-light"
            },
            buttonsStyling: !1
        })
        .then(function (t) {
        if (t.value) {
            addLoading(card);
            $.ajax({
            url: url,
            type: "POST",
            dataType: "jsonp",
            data: {
                action: "refund",
                id: id,
            },
            error: function () {
                removeLoading(card);
                Swal.fire({
                    title: "Error",
                    text: "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
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
                    Swal.fire({
                        title: "Success",
                        text: resp.msg,
                        icon: "success",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    });
                    table_data.ajax.reload();
                } else {
                    Swal.fire({
                        title: "Error",
                        text: resp.msg,
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    });
                }
            },
            });
        }
        });
});

$("body").on("click", ".btn-show-order-comment", function () {
    var url = $(this).data("url");
    var id = $(this).data("id");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    addLoading(card);
    $modal = $("#modal_show_order");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "jsonp",
        data: {
        action: "get_comment",
        id: id,
        },
        error: function () {
            removeLoading(card);
            Swal.fire({
                title: "Oops...",
                text: "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!",
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
                $modal.modal("show");
                $modal.find(".modal-title").html("Xem nội dung comment đơn #".id)
                $modal.find("#comment_list").html(resp.data.map(item => item.comment).join("\n"))
            } else {
                Swal.fire({
                    title: "Oops...",
                    text: resp.msg,
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

$("body").on("click", ".btn-refund-order-bulk", function () {
    var url = $(this).data("url");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    var value_ids = [];
    $(table + " input:checkbox[name=customCheck]:checked").each(function () {
        value_ids.push($(this).val());
    });

    if (value_ids.length == 0) {
        Swal.fire({
            title: "Oops...",
            text: "Chưa chọn danh sách",
            icon: "info",
            customClass: {
                confirmButton: "btn btn-primary waves-effect waves-light"
            },
            buttonsStyling: !1
        });
        return;
    }

    Swal
        .fire({
            title: "Bạn có chắc hoàn không?",
            text: "Chỉ những đơn trong trạng thái RUNNING, PENDING mới có thể hoàn!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonText: "Có, Hoàn",
            cancelButtonText: "Huỷ",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-label-secondary waves-effect waves-light"
            },
            buttonsStyling: !1
        })
        .then(function (t) {
        if (t.value) {
            addLoading(card);
            $.ajax({
            url: url,
            type: "POST",
            dataType: "jsonp",
            data: {
                action: "refund_bulk",
                ids: value_ids,
            },
            error: function () {
                removeLoading(card);
                Swal.fire({
                    title: "Oops...",
                    text:  "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
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
                    Swal.fire({
                        title: "Success",
                        text:  resp.msg,
                        icon: "success",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    });
                    table_data.ajax.reload();
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
        }
        });
});

$("body").on("click", ".js-edit-list-order", function () {
    var url = $(this).data("url");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    var value_ids = [];
    $(table + " input:checkbox[name=customCheck]:checked").each(function () {
        value_ids.push($(this).val());
    });

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
    addLoading(card);

    $modal = $("#modal_edit_orders");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "jsonp",
        data: {
        action: "edit_bulk",
        ids: value_ids,
        status_edit: $modal
            .find("select[name=status_edit] option:selected")
            .val(),
        note_extra: $modal.find("textarea[name=note_extra]").val(),
        seeding_type: $modal
            .find("select[name=seeding_type] option:selected")
            .val(),
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
        removeLoading(card);
        if (resp.result == 1) {
            $modal.modal("hide");
            Swal.fire({
                title: "Success",
                text:  resp.msg,
                icon: "success",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
            table_data.ajax.reload();
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

$("body").on("click", ".js-warranty-check", function () {
    var url = $(this).data("url");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    var value_ids = [];
    $(table + " input:checkbox[name=customCheck]:checked").each(function () {
        value_ids.push($(this).val());
    });

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

    if (value_ids.length > 1) {
        Swal.fire({
            title: "Oops...",
            text:  "Hiện tại chỉ hỗ trợ bảo hành từng đơn 1",
            icon: "info",
            customClass: {
                confirmButton: "btn btn-primary waves-effect waves-light"
            },
            buttonsStyling: !1
        });
        return;
    }

    addLoading(card);

    $.ajax({
        url: url + "/1",
        type: "POST",
        dataType: "jsonp",
        data: {
        action: "check_warranty",
        id: value_ids[0],
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
        removeLoading(card);
        if (resp.result == 1) {
            Swal.fire({
                title: "Success",
                text:  resp.msg,
                icon: "success",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
            table_data.ajax.reload();
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

$("body").on("click", ".js-group-order", function () {
    var url = $(this).data("url");
    var table = $(this).data("table");
    var card = $(table).parents(".card:first");

    var value_ids = [];
    $(table + " input:checkbox[name=customCheck]:checked").each(function () {
        value_ids.push($(this).val());
    });

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

    Swal
        .fire({
            title: "Gộp nhóm",
            input: "text",
            inputPlaceholder:
                "Nhập mã nhóm cần gộp (vd: SABOMMO123), chứa tối đa 10 ký tự",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: !0,
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Bỏ qua",
            showLoaderOnConfirm: !0,
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-label-danger waves-effect waves-light"
            },
        preConfirm: function (t) {
            if (!t) {
            Swal.showValidationMessage("Vui lòng nhập mã nhóm!");
            return;
            }
            return $.ajax({
            url: url,
            type: "post",
            dataType: "jsonp",
            data: {
                action: "group_order",
                ids: value_ids,
                title: t,
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
        })
        .then(function (t) {
            if (!t.value) return;
            table_data.ajax.reload();
            Swal.fire({
                title: "Thành Công",
                text:  "Gộp đơn thành công",
                icon: "success",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            });
        });
});
