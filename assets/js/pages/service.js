function get_list_price_by_domain() {
    var card = $(".card");
    addLoading(card);
    $.ajax({
        url: card.data("url"),
        type: "POST",
        dataType: "jsonp",
        data: {
        action: "list_price_by_domain",
        site_id: $('select[name="site_id"] option:selected').val(),
        server_id: $('select[name="server_id"] option:selected').val(),
        },
        error: function () {
            removeLoading(card);
            Swal.fire({
                title: "Oops...",
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
                card
                .find("input[name='server_id']")
                .val($('select[name="server_id"] option:selected').val());
                var roles = resp.roles;
                if (roles.length == 0) {
                    $("#prices_table > tbody").html(
                        '<tr class="text-center"><td colspan="6">No Data</td></tr>'
                    );
                    return;
                }
                var price = resp.price;
                var amount = resp.amount;
                var html = "";
                var domainId = 0;
                for (let i = 0; i < roles.length; i++) {
                    const role = roles[i];
                    if (domainId != parseInt(role.site_id)) {
                        domainId = parseInt(role.site_id);
                        html += `<tr>
                                    <td><a href="http://${role.domain}" target="_blank">${
                                        role.domain
                                    }</a></td>
                                    <td>Mặc định</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập giá mặc định" name="data[${
                                            role.site_id
                                        }][price][default]" value="${
                                        price["site_id_" + role.site_id]
                                            ? price["site_id_" + role.site_id]["default"]
                                            : 0
                                        }">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập số lượng mua tối thiểu" name="data[${
                                            role.site_id
                                        }][amount][default][min]" value="${
                                            amount["site_id_" + role.site_id] &&
                                            amount["site_id_" + role.site_id]["default"]
                                                ? amount["site_id_" + role.site_id]["default"]["min"]
                                                : 0
                                            }">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập số lượng mua tối đa" name="data[${
                                            role.site_id
                                        }][amount][default][max]" value="${
                                            amount["site_id_" + role.site_id] &&
                                            amount["site_id_" + role.site_id]["default"]
                                                ? amount["site_id_" + role.site_id]["default"]["max"]
                                                : 0
                                            }">
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex">
                                            <a href="javascript:void(0)" class="text-body" type="button">
                                            <i class="ti ti-list"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                    }
                    html += `<tr>
                                    <td><a href="http://${role.domain}" target="_blank">${
                                        role.domain
                                    }</a></td>
                                    <td><span class="badge bg-${
                                    role.color
                                    } bg-opacity-10 text-${role.color}">${
                                        role.title
                                    }</span></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập giá ${
                                                            role.title
                                                        }" name="data[${role.site_id}][price][${
                                            role.idname
                                        }]" value="${
                                            price["site_id_" + role.site_id]
                                            ? price["site_id_" + role.site_id][role.idname]
                                            : 0
                                        }">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập số lượng mua tối thiểu" name="data[${
                                                            role.site_id
                                                        }][amount][${role.idname}][min]" value="${
                                            amount["site_id_" + role.site_id] &&
                                            amount["site_id_" + role.site_id][role.idname]
                                            ? amount["site_id_" + role.site_id][role.idname]["min"]
                                            : 0
                                        }">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="Nhập số lượng mua tối đa" name="data[${
                                                            role.site_id
                                                        }][amount][${role.idname}][max]" value="${
                                            amount["site_id_" + role.site_id] &&
                                            amount["site_id_" + role.site_id][role.idname]
                                            ? amount["site_id_" + role.site_id][role.idname]["max"]
                                            : 0
                                        }">
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex">
                                            <a href="javascript:void(0)" class="text-body" type="button">
                                            <i class="ti ti-list"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                }

                $("#prices_table > tbody").html(html);
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
    }

    function get_list_server_by_domain() {
    var card = $("#card-server");
    addLoading(card);
    $.ajax({
        url: card.data("url"),
        type: "POST",
        dataType: "jsonp",
        data: {
        action: "list_server_by_domain",
        site_id: $('select[name="site_id"] option:selected').val(),
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
                card
                .find("input[name=site_id]")
                .val($('select[name="site_id"] option:selected').val());
                var html = "";
                var data = resp.data;
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    html += `
                    <tr>
                        <td>${element.name}</td>
                            <td>
                            <a target="_blank" href="${element.api_url}">${
                                element.api_url
                            }</a>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <div class="form-check form-switch form-switch-md mb-2">
                                    <input id='data[${
                                    element.id
                                    }][maintain]['hidden']' type='hidden' name='data[${
                                element.id
                            }][maintain]' value="0">
                                    <input type="checkbox" class="form-check-input" id="data[${
                                        element.id
                                    }][maintain]" name="data[${
                                element.id
                            }][maintain]" value="1" ${
                                element?.options?.maintain ? "checked" : ""
                            }>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <div class="form-check form-switch form-switch-md mb-2">
                                    <input id='data[${
                                    element.id
                                    }][public][hidden]' type='hidden' name='data[${
                                element.id
                            }][public]' value="0">

                                    <input type="checkbox" class="form-check-input" id="data[${
                                        element.id
                                    }][public]" name="data[${element.id}][public]" value="1"  ${
                                element?.options?.public ? "checked" : ""
                            }>
                                    </div>
                                </div>
                            </td>
                        </td>
                    </tr>
                    `;
                }
                $("#servers_table > tbody").html(html);
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
    }
