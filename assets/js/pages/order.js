$("body").on("change keyup", ".input-number", function () {
    var total = 1;
    var priceValue = $(".js-choose-server:checked").data("price");
    var price = parseInt(priceValue);

    var list = $(".input-number");
    for (var i = 0; i < list.length; i++) {
        total *= list[i].value;
    }
    total = price * total;
    $("#total_price_order").html(total.format());
});

$("body").on("change", ".js-choose-server", function () {
    $(".input-number").trigger("change");
    $this = $(this);
    var min = $this.data("min");
    var max = $this.data("max");
    var input_amount = $("input[name=order_amount]");

    input_amount.attr("min", min);
    input_amount.attr("max", max);

    $("#min_text").html(min.format());
    $("#max_text").html(max.format());
});

$("body").on("change keyup", ".input-count", function () {
    $this = $(this);
    var target = $($this.data("target"));
    var new_value = this.value.split("\n").filter((item) => item.trim() != "");
    var count = new_value.length;
    target.html(`( ${count.format()} )`);
});

$("body").on("click", ".input-checkbox", function () {
    $image = $(this).siblings("label").find("img");
    if (this.checked && $image.hasClass("opacity-25")) {
        $image.removeClass("opacity-25");
    } else if (!this.checked && !$image.hasClass("opacity-25")) {
        $image.addClass("opacity-25");
    }
});

$("body").on("reset", ".js-ajax-form", function () {
    $(this).find("img").addClass("opacity-25");
});

$("body").on("click", ".btn-get-id", function () {
    var button = $(this);
    if (!$("input[name=seeding_uid]").val()) {
        button.html(button.data("initial-text"));
        button.removeClass("disabled");
        return;
    }
    $.ajax({
        url: "/helpers",
        type: "POST",
        dataType: "jsonp",
        data: {
            action: "get_uid",
            url: $("input[name=seeding_uid]").val(),
            idname: $("input[name=idname]").val(),
        },
        error: function () {
            button.html(button.data("initial-text"));
            button.removeClass("disabled");
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
            button.html(button.data("initial-text"));
            button.removeClass("disabled");
            if (resp.result) {
                Swal.fire({
                    title: "Success",
                    text: resp.msg,
                    icon: "success",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                });
                $("input[name=seeding_uid]").val(resp.id);
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
});
