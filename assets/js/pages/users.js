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
            Swal.fire(
            "Oops...",
            "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!",
            "error"
            );
        },
        success: function (resp) {
            removeLoading(card);
            if (resp.result == 1) {
            console.log(resp);
            $modal = $("#modal_gen_qr");
            $modal.modal("show");
            $modal.find("img").attr("src", resp.url);
            $modal.find("#title_email").html(resp.email);
            } else {
            Swal.fire("Oops...", resp.msg, "error");
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
                            <input class="form-check-input" type="checkbox" name="customCheck" id="data_id_${e.id}" value="${e.id}" style="width: 20px; height: 20px">
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
                return `<span class="badge rounded-pill badge-${e.color}">${t}</span>`;
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
                    return `<span class="font-size-12 badge bg-success">Hoạt động</span>`;
                } else {
                    return `<span class="font-size-12 badge bg-danger">Dừng hoạt động</span>`;
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
                return ` <ul class="d-flex justify-content-between list-unstyled>
                            <li class="edit">
                                <a href="${$table.data("url")}/${t}" class="btn btn-outline-primary btn-sm btn-xs edit-item-btn" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Sửa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 22H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M13.8881 3.66293L14.6296 2.92142C15.8581 1.69286 17.85 1.69286 19.0786 2.92142C20.3071 4.14999 20.3071 6.14188 19.0786 7.37044L18.3371 8.11195M13.8881 3.66293C13.8881 3.66293 13.9807 5.23862 15.3711 6.62894C16.7614 8.01926 18.3371 8.11195 18.3371 8.11195M13.8881 3.66293L7.07106 10.4799C6.60933 10.9416 6.37846 11.1725 6.17992 11.4271C5.94571 11.7273 5.74491 12.0522 5.58107 12.396C5.44219 12.6874 5.33894 12.9972 5.13245 13.6167L4.25745 16.2417M18.3371 8.11195L11.5201 14.9289C11.0584 15.3907 10.8275 15.6215 10.5729 15.8201C10.2727 16.0543 9.94775 16.2551 9.60398 16.4189C9.31256 16.5578 9.00282 16.6611 8.38334 16.8675L5.75834 17.7426M5.75834 17.7426L5.11667 17.9564C4.81182 18.0581 4.47573 17.9787 4.2485 17.7515C4.02128 17.5243 3.94194 17.1882 4.04356 16.8833L4.25745 16.2417M5.75834 17.7426L4.25745 16.2417" stroke="currentColor" stroke-width="1.5"/>
                                    </svg>
                                </a>
                            </li>
                            <li class="statistic">
                                <a href="${$table.data("url")}/${t}/statistic" class="btn btn-outline-success btn-sm btn-xs" data-container="body" data-bs-toggle="popover" data-placement="top" data-bs-toggle="tooltip" title="Thống kê" data-original-title="Icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 22H2" stroke="var(--bs-success)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M21 22V14.5C21 13.6716 20.3284 13 19.5 13H16.5C15.6716 13 15 13.6716 15 14.5V22" stroke="var(--bs-success)" stroke-width="1.5"/>
                                        <path d="M15 22V5C15 3.58579 15 2.87868 14.5607 2.43934C14.1213 2 13.4142 2 12 2C10.5858 2 9.87868 2 9.43934 2.43934C9 2.87868 9 3.58579 9 5V22" stroke="var(--bs-success)" stroke-width="1.5"/>
                                        <path d="M9 22V9.5C9 8.67157 8.32843 8 7.5 8H4.5C3.67157 8 3 8.67157 3 9.5V22" stroke="var(--bs-success)" stroke-width="1.5"/>
                                    </svg>
                                </a>
                            </li>
                            <li class="balance">
                                <a href="${$table.data("url")}/${t}" data-id="${t}" class="btn-edit-balance" data-container="body" data-bs-toggle="tooltip" title="Thay đổi số dư" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                        <circle cx="12" cy="12" r="10" stroke="var(--bs-warning)" stroke-width="1.5"/>
                                        <path d="M12 6V18" stroke="var(--bs-warning)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="var(--bs-warning)" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </li>
                            <li class="login">
                            <a href="${$table.data("url")}/${t}" data-id="${t}" class=" btn-gen-qr" data-bs-toggle="tooltip" title="Tạo code đăng nhập">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart bookmark-search">
                                        <path d="M2 16.9C2 15.5906 2 14.9359 2.29472 14.455C2.45963 14.1859 2.68589 13.9596 2.955 13.7947C3.43594 13.5 4.09063 13.5 5.4 13.5H6.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5V18.6C10.5 19.9094 10.5 20.5641 10.2053 21.045C10.0404 21.3141 9.81411 21.5404 9.545 21.7053C9.06406 22 8.40937 22 7.1 22C5.13594 22 4.15391 22 3.4325 21.5579C3.02884 21.3106 2.68945 20.9712 2.44208 20.5675C2 19.8461 2 18.8641 2 16.9Z" stroke="var(--bs-dark)" stroke-width="1.5"/>
                                        <path d="M13.5 5.4C13.5 4.09063 13.5 3.43594 13.7947 2.955C13.9596 2.68589 14.1859 2.45963 14.455 2.29472C14.9359 2 15.5906 2 16.9 2C18.8641 2 19.8461 2 20.5675 2.44208C20.9712 2.68945 21.3106 3.02884 21.5579 3.4325C22 4.15391 22 5.13594 22 7.1C22 8.40937 22 9.06406 21.7053 9.545C21.5404 9.81411 21.3141 10.0404 21.045 10.2053C20.5641 10.5 19.9094 10.5 18.6 10.5H17.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5V5.4Z" stroke="var(--bs-dark)" stroke-width="1.5"/>
                                        <path opacity="0.5" d="M16.5 6.25C16.5 5.73459 16.5 5.47689 16.6291 5.29493C16.6747 5.23072 16.7307 5.17466 16.7949 5.12911C16.9769 5 17.2346 5 17.75 5C18.2654 5 18.5231 5 18.7051 5.12911C18.7693 5.17466 18.8253 5.23072 18.8709 5.29493C19 5.47689 19 5.73459 19 6.25C19 6.76541 19 7.02311 18.8709 7.20507C18.8253 7.26928 18.7693 7.32534 18.7051 7.37089C18.5231 7.5 18.2654 7.5 17.75 7.5C17.2346 7.5 16.9769 7.5 16.7949 7.37089C16.7307 7.32534 16.6747 7.26928 16.6291 7.20507C16.5 7.02311 16.5 6.76541 16.5 6.25Z" fill="var(--bs-dark)"/>
                                        <path d="M19 13.5H17C15.5955 13.5 14.8933 13.5 14.3889 13.8371C14.1705 13.983 13.983 14.1705 13.8371 14.3889C13.5 14.8933 13.5 15.5955 13.5 17" stroke="var(--bs-dark)" stroke-width="1.5"/>
                                        <path opacity="0.5" d="M12.75 22C12.75 22.4142 13.0858 22.75 13.5 22.75C13.9142 22.75 14.25 22.4142 14.25 22H12.75ZM12.75 19V22H14.25V19H12.75Z" fill="var(--bs-dark)"/>
                                        <path d="M17 22H19C19.9319 22 20.3978 22 20.7654 21.8478C21.2554 21.6448 21.6448 21.2554 21.8478 20.7654C22 20.3978 22 19.9319 22 19" stroke="var(--bs-dark)" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path opacity="0.5" d="M22.75 13.5C22.75 13.0858 22.4142 12.75 22 12.75C21.5858 12.75 21.25 13.0858 21.25 13.5H22.75ZM22.75 17V13.5H21.25V17H22.75Z" fill="var(--bs-dark)"/>
                                        <path d="M2 7.1C2 5.13594 2 4.15391 2.44208 3.4325C2.68945 3.02884 3.02884 2.68945 3.4325 2.44208C4.15391 2 5.13594 2 7.1 2C8.40937 2 9.06406 2 9.545 2.29472C9.81411 2.45963 10.0404 2.68589 10.2053 2.955C10.5 3.43594 10.5 4.09063 10.5 5.4V6.5C10.5 8.38562 10.5 9.32843 9.91421 9.91421C9.32843 10.5 8.38562 10.5 6.5 10.5H5.4C4.09063 10.5 3.43594 10.5 2.955 10.2053C2.68589 10.0404 2.45963 9.81411 2.29472 9.545C2 9.06406 2 8.40937 2 7.1Z" stroke="var(--bs-dark)" stroke-width="1.5"/>
                                        <path opacity="0.5" d="M5 6.25C5 5.73459 5 5.47689 5.12911 5.29493C5.17466 5.23072 5.23072 5.17466 5.29493 5.12911C5.47689 5 5.73459 5 6.25 5C6.76541 5 7.02311 5 7.20507 5.12911C7.26928 5.17466 7.32534 5.23072 7.37089 5.29493C7.5 5.47689 7.5 5.73459 7.5 6.25C7.5 6.76541 7.5 7.02311 7.37089 7.20507C7.32534 7.26928 7.26928 7.32534 7.20507 7.37089C7.02311 7.5 6.76541 7.5 6.25 7.5C5.73459 7.5 5.47689 7.5 5.29493 7.37089C5.23072 7.32534 5.17466 7.26928 5.12911 7.20507C5 7.02311 5 6.76541 5 6.25Z" fill="var(--bs-dark)"/>
                                        <path opacity="0.5" d="M5 17.75C5 17.2346 5 16.9769 5.12911 16.7949C5.17466 16.7307 5.23072 16.6747 5.29493 16.6291C5.47689 16.5 5.73459 16.5 6.25 16.5C6.76541 16.5 7.02311 16.5 7.20507 16.6291C7.26928 16.6747 7.32534 16.7307 7.37089 16.7949C7.5 16.9769 7.5 17.2346 7.5 17.75C7.5 18.2654 7.5 18.5231 7.37089 18.7051C7.32534 18.7693 7.26928 18.8253 7.20507 18.8709C7.02311 19 6.76541 19 6.25 19C5.73459 19 5.47689 19 5.29493 18.8709C5.23072 18.8253 5.17466 18.7693 5.12911 18.7051C5 18.5231 5 18.2654 5 17.75Z" fill="var(--bs-dark)"/>
                                        <path opacity="0.5" d="M16 17.75C16 17.0478 16 16.6967 16.1685 16.4444C16.2415 16.3352 16.3352 16.2415 16.4444 16.1685C16.6967 16 17.0478 16 17.75 16C18.4522 16 18.8033 16 19.0556 16.1685C19.1648 16.2415 19.2585 16.3352 19.3315 16.4444C19.5 16.6967 19.5 17.0478 19.5 17.75C19.5 18.4522 19.5 18.8033 19.3315 19.0556C19.2585 19.1648 19.1648 19.2585 19.0556 19.3315C18.8033 19.5 18.4522 19.5 17.75 19.5C17.0478 19.5 16.6967 19.5 16.4444 19.3315C16.3352 19.2585 16.2415 19.1648 16.1685 19.0556C16 18.8033 16 18.4522 16 17.75Z" fill="var(--bs-dark)"/>
                                    </svg>
                                </a>
                            </li>
                            <li class="remove">
                                <a href="${$table.data("url")}/${t}" data-id="${t}" class="js-remove-item" data-table="#users_table" data-bs-toggle="tooltip" title="Xoá" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                        <path d="M20.5001 6H3.5" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M9.5 11L10 16" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M14.5 11L14 16" stroke="var(--bs-danger)" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="var(--bs-danger)" stroke-width="1.5"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>`;
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
      Swal.fire("Oops...", "Chưa chọn danh sách", "info");
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
        Swal.fire(
          "Oops...",
          "Oops! Đã xảy ra lỗi. Vui lòng thử lại sau!",
          "error"
        );
      },
      success: function (resp) {
        removeLoading(card);
        if (resp.result == 1) {
          table_data.ajax.reload();
          Swal.fire("Success", resp.msg, "success");
        } else {
          Swal.fire("Oops...", resp.msg, "error");
        }
      },
    });
});
