/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */
$(function () {
    Sub99.initFormWizard();
    Sub99.initChoices();
    Sub99.initGLightbox();
    Sub99.dateRangePicker();
    Sub99.initButtonLoading();
    Sub99.AjaxForms();
    Sub99.SelectAll();
    Sub99.RemoveListItem();
    Sub99.RemoveItem();
});

/**
 * NextPost Namespace
 */
var Sub99 = {};
function addLoading($card) {
    if ($card.find(".card-overlay").length > 0) {
        return;
    }
    $card.append(
        '<div class="card-overlay"><i class="bx bx-loader-alt spinner text-body"></i></div>'
    );
}

function removeLoading($card) {
    $card.find(".card-overlay").remove();
}

Sub99.dateRangePicker = function () {
    if (!$().daterangepicker) {
        console.warn("Warning - daterangepicker.js is not loaded.");
        return;
    }
    $(".daterange").daterangepicker({
        parentEl: ".content-inner",
        maxDate: moment(),
        locale: {
        format: "DD/MM/YYYY",
        },
    });

    $(".daterange-single").daterangepicker({
        parentEl: ".content-inner",
        maxDate: moment(),
        singleDatePicker: true,
        locale: {
        format: "DD/MM/YYYY",
        },
    });
};

Sub99.initGLightbox = function () {
    if (typeof GLightbox == "undefined") {
        console.warn("Warning - glightbox.min.js is not loaded.");
        return;
    }
    // Image lightbox
    const lightbox = GLightbox({
        selector: '[data-bs-popup="lightbox"]',
        loop: true,
        svg: {
        next:
            document.dir == "rtl"
            ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"/></g></svg>'
            : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"> <g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"/></g></svg>',
        prev:
            document.dir == "rtl"
            ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"/></g></svg>'
            : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"/></g></svg>',
        },
    });
};

Sub99.initButtonLoading = function () {
    const btnLoadingElement = document.querySelectorAll(".btn-loading");
    if (btnLoadingElement) {
        btnLoadingElement.forEach(function (button) {
        button.addEventListener("click", function () {
            const initialText = button.dataset.initialText,
            loadingText = button.dataset.loadingText;
            button.innerHTML = loadingText;
            button.classList.add("disabled");
        });
        });
    }
};

Sub99.initFormWizard = function () {
    if (!$().steps) {
        console.warn("Warning - steps.min.js is not loaded.");
        return;
    }

    // Basic wizard setup
    $(".steps-form").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        labels: {
        previous:
            document.dir == "rtl"
            ? '<i class="ph-arrow-circle-right me-2"></i> Lùi'
            : '<i class="ph-arrow-circle-left me-2"></i> Lùi',
        next:
            document.dir == "rtl"
            ? 'Tiếp <i class="ph-arrow-circle-left ms-2"></i>'
            : 'Tiếp <i class="ph-arrow-circle-right ms-2"></i>',
        finish: 'Xác nhận <i class="bx bx-paper-plane ms-2 label-icon"></i>',
        },
        onFinished: function (event, currentIndex) {
        form.submit();
        },
    });

    //
    // Wizard with validation
    //

    // Stop function if validation is missing
    if (!$().validate) {
        console.warn("Warning - validate.min.js is not loaded.");
        return;
    }

    // Show form
    const validationExampleElement = $(".steps-validation");
    const form = validationExampleElement.show();

    // Initialize wizard
    validationExampleElement.steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        titleTemplate: '<span class="number">#index#</span> #title#',
        labels: {
        previous:
            document.dir == "rtl"
            ? '<i class="ph-arrow-circle-right me-2"></i> Lùi'
            : '<i class="ph-arrow-circle-left me-2"></i> Lùi',
        next:
            document.dir == "rtl"
            ? 'Tiếp <i class="ph-arrow-circle-left ms-2"></i>'
            : 'Tiếp <i class="ph-arrow-circle-right ms-2"></i>',
        finish: 'Xác nhận <i class="bx bx-paper-plane ms-2 label-icon"></i>',
        },
        transitionEffect: "fade",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }

        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
        },
        onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
        },
        onFinished: function (event, currentIndex) {
        form.submit();
        },
    });

    // Initialize validation
    validationExampleElement.validate({
        ignore: "input[type=hidden], .select2-search__field", // ignore hidden fields
        errorClass: "validation-invalid-label",
        highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        },

        // Different components require proper error label placement
        errorPlacement: function (error, element) {
        // Input with icons and Select2
        if (element.hasClass("select2-hidden-accessible")) {
            error.appendTo(element.parent());
        }

        // Input group, form checks and custom controls
        else if (
            element.parents().hasClass("form-control-feedback") ||
            element.parents().hasClass("form-check") ||
            element.parents().hasClass("input-group")
        ) {
            error.appendTo(element.parent().parent());
        }

        // Other elements
        else {
            error.insertAfter(element);
        }
        },
        rules: {
        email: {
            email: true,
        },
        },
    });
};

Sub99.initToolTips = function () {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-popup="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
};

Sub99.initChoices = function () {
    if (!window.Choices) {
        console.warn("Warning - choices.min.js is not loaded.");
        return;
    }
    //
    // Basic examples
    //

    // Default initialization
    $(".select").each(function () {
        let searchEnabled = true;
        $this = $(this);
        if ($this.data("search-enabled") == false) {
        searchEnabled = false;
        }

        new Choices(this, {
        noResultsText: "không tìm thấy",
        loadingText: "Tìm kiếm...",
        searchEnabled: searchEnabled,
        itemSelectText: "",
        });
    });
};

/**
 * Ajax forms
 */
Sub99.AjaxForms = function () {
    var $form;

    $("body").on("submit", ".js-ajax-form", function () {
        $form = $(this);
        submitable = true;

        $form
        .find("input:required")
        .not(":disabled")
        .each(function () {
            if (!$(this).val()) {
            submitable = false;
            }
        });

        if (submitable) {
        $card = $form.parents(".card");
        addLoading($card);

        $.ajax({
            url: $form.attr("action"),
            type: $form.attr("method"),
            dataType: "jsonp",
            data: $form.serialize(),
            error: function () {
                removeLoading($card);
                Swal.fire({
                    title: "Error!",
                    text: "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                });
            },

            success: function (resp) {
            if (typeof resp.redirect === "string") {
                window.location.href = resp.redirect;
            } else if (typeof resp.msg === "string") {
                var result = resp.result || 0;
                var reset = resp.reset || 0;
                var novalidate = resp.novalidate || 0;
                var reload_table = resp.reload_table || 0;
                switch (result) {
                case true:
                case 1: //
                    Swal.fire({
                        title: "Success",
                        text: resp.msg,
                        icon: "success",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    })
                    // Swal.fire("Success", resp.msg, "success");
                    if (reset) {
                        $form[0].reset();
                    }
                    if (reload_table && table_data) {
                        $(".modal").modal("hide");
                        table_data.ajax.reload();
                    }
                    break;

                case 2: //
                    Swal.fire({
                        title: "Info!",
                        text: resp.msg,
                        icon: "info",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    })
                    break;

                default:
                    if (novalidate) {
                    $form.addClass("was-validated");
                    }
                    Swal.fire({
                        title: "Error!",
                        text: resp.msg,
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-primary waves-effect waves-light"
                        },
                        buttonsStyling: !1
                    })
                    break;
                }
                removeLoading($card);
            } else {
                removeLoading($card);
                Swal.fire({
                    title: "Error!",
                    text: " Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                })
                // Swal.fire(
                // "Error",
                // "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                // "error"
                // );
            }
            },
        });
        } else {
        $form.addClass("was-validated");
            Swal.fire({
                title: "Error!",
                text: " Nhập đầy đủ thông tin",
                icon: "error",
                customClass: {
                    confirmButton: "btn btn-primary waves-effect waves-light"
                },
                buttonsStyling: !1
            })
        }

        return false;
    });
};

/**
 * Select/Unselect All
 */
Sub99.SelectAll = function () {
    $("#customCheck0").click(function () {
        $('.table tbody input[type="checkbox"]').prop("checked", this.checked);
    });
};

Sub99.LogoType = function () {
    $(".btn-ckfinder").on("click", function () {
        var imgSrc = $($(this).data("target"));
        CKFinder.modal({
        chooseFiles: true,
        width: 800,
        height: 600,
        onInit: function (finder) {
            finder.on("files:choose", function (evt) {
            var file = evt.data.files.first();
            imgSrc.val(file.getUrl());
            });
        },
        });
    });
};

/**
 * Remove List Item (Data entry)
 *
 * Sends remove request to the backend
 * for selected list item (data entry)
 */
Sub99.RemoveListItem = function () {
    $("body").on("click", ".js-remove-list-item", function () {
        var url = $(this).data("url");
        var table = $(this).data("table");
        var card = $(this).parents("block-mode-loading-refresh");

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
            })
            return;
        }

        Swal.fire({
        title: "Bạn có chắc không?",
        text: "Xoá rồi không thể lấy lại dữ liệu!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#34c38f",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Có, Xoá",
        cancelButtonText: "Huỷ",
        }).then(function (t) {
        if (t.value) {
            addLoading(card);
            $.ajax({
            url: url,
            type: "POST",
            dataType: "jsonp",
            data: {
                action: "remove",
                ids: value_ids,
            },
            error: function () {
                removeLoading(card);
                Swal.fire(
                "Oops...",
                "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!",
                "error"
                );
            },
            success: function (resp) {
                removeLoading(card);
                if (resp.result == 1) {
                Swal.fire("Success", resp.msg, "success");
                value_ids.forEach((id) => {
                    var item = $(table + " tr[data-id='" + id + "']");
                    item.fadeOut(500, function () {
                    item.remove();
                    });
                });
                } else {
                Swal.fire("Oops...", resp.msg, "error");
                }
            },
            });
        }
        });
    });
};

Sub99.RemoveItem = function () {
    $("body").on("click", ".js-remove-item", function () {
        var url = $(this).data("url");
        var id = $(this).data("id");
        var table = $(this).data("table");
        var card = $(this).parents("block-mode-loading-refresh");

        Swal.fire({
        title: "Bạn có chắc không?",
        text: "Xoá rồi không thể lấy lại dữ liệu!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#34c38f",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Có, Xoá",
        cancelButtonText: "Huỷ",
        }).then(function (t) {
        if (t.value) {
            addLoading(card);
            $.ajax({
            url: url,
            type: "POST",
            dataType: "jsonp",
            data: {
                action: "remove",
                id: id,
            },
            error: function () {
                removeLoading(card);
                Swal.fire(
                "Error",
                "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
                "error"
                );
            },
            success: function (resp) {
                removeLoading(card);
                if (resp.result == 1) {
                Swal.fire("Success", resp.msg, "success");
                var item = $(table + " tr[data-id='" + id + "']");
                item.fadeOut(500, function () {
                    item.remove();
                });

                var item = $("#postid-" + id);
                item.fadeOut(500, function () {
                    item.remove();
                });
                } else {
                Swal.fire("Error", resp.msg, "error");
                }
            },
            });
        }
        });
    });
};

Sub99.editOrder = function () {
    $("body").on("click", ".edit-order-btn", function () {
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
            action: "get_info_summary",
            id: id,
        },
        error: function () {
            removeLoading(card);
            Swal.fire(
            "Error",
            "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
            "error"
            );
        },
        success: function (resp) {
            removeLoading(card);
            if (resp.result == 1) {
            $modal = $("#modal_edit_order");
            $card = $modal.find(".card");
            $modal.find(".modal-title").html("Sửa đơn hàng #" + id);
            $modal.find("input[name=order_id]").val(id);
            $modal.find("textarea[name=note_extra]").val(resp.data.note_extra);
            $modal
                .find("select[name=status_edit]")
                .val(resp.data.status)
                .trigger("change");
            $modal
                .find("select[name=seeding_type]")
                .val(resp.data.seeding_type)
                .trigger("change");
            $modal.modal("show");
            } else {
            Swal.fire("Error", resp.msg, "error");
            }
        },
        });
    });
};

/**
 * Settings
 */
Sub99.Settings = function () {
    $(".js-settings-menu").on("click", function () {
        $(".asidenav").toggleClass("mobile-visible");
        $(this).toggleClass("mdi-menu-down mdi-menu-up");

        $("html, body").delay(200).animate({
        scrollTop: "0px",
        });
    });

    if ($("#smtp-form").length == 1) {
        $("#smtp-form :input[name='auth']")
        .on("change", function () {
            if ($(this).is(":checked")) {
            $("#smtp-form :input[name='username'], :input[name='password']").prop(
                "disabled",
                false
            );
            } else {
            $("#smtp-form :input[name='username'], :input[name='password']")
                .prop("disabled", true)
                .val("");
            }
        })
        .trigger("change");
    }
};

/**
 * Profile
 */
Sub99.Profile = function () {
    $(".js-resend-verification-email").on("click", function () {
        var $this = $(this);
        var $card = $this.parents(".card");

        addLoading($card);
        $.ajax({
        url: $this.data("url"),
        type: "POST",
        dataType: "jsonp",
        data: {
            action: "resend-email",
        },

        error: function () {
            Swal.fire(
            "Error",
            "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!!",
            "error"
            );
            removeLoading($card);
        },

        success: function (resp) {
            if (resp.result) {
            $this.remove();
            Swal.fire("Success", resp.msg, "success");
            } else {
            Swal.fire("Error", resp.msg, "error");
            }
            removeLoading($card);
        },
        });
    });
};

/* Functions */

/**
 * Validate Email
 */
function isValidEmail(email) {
    var re =
        /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

/**
 * Validate URL
 */
function isValidUrl(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(
        url
    );
}

Number.prototype.format = function (t, n) {
    var e = "\\d(?=(\\d{" + (n || 3) + "})+" + (t > 0 ? "\\." : "$") + ")";
    return this.toFixed(Math.max(0, ~~t)).replace(new RegExp(e, "g"), "$&,");
};
