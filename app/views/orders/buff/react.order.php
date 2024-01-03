<div class="animate__animated p-6">
    <h4 class="text-lg font-semibold dark:text-white-light">Đơn hàng - <span class="fw-normal"><?= htmlchars($Service->get("title") . " - " . $Service->get("group")) ?></span></h4>
    <div class="mb-6 grid gap-6 xl:grid-cols-3 pt-2">
        <div class="panel h-full xl:col-span-2">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="text-lg font-semibold dark:text-white-light">Thêm mới đơn hàng</h5>
            </div>
            <div class="mb-5">
                <form class="js-ajax-form" action="<?= APPURL . "/orders/" . $Service->get("group") . "/" . $Service->get("idname") . "/new" ?>" method="POST" novalidate>
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="idname" value="<?= $Service->get("idname") ?>">
                    <div class="grid grid-cols-1 gap-4 sm:col-span-2">
                        <div class="mb-3">
                            <label for="spiRight">Facebook ID</label>
                            <div class="flex">
                                <input id="spiRight" name="seeding_uid" type="text" placeholder="Nhập ID cần tăng" class="form-input ltr:rounded-r-none rtl:rounded-l-none">
                                <button data-loading-text="<i class='ph-spinner spinner me-2'></i> ..." type="button" class="btn btn-primary ltr:rounded-l-none rtl:rounded-r-none btn-loading btn-get-id">GetID</button>
                            </div>
                            <span>ID phải là 1 dãy số. Nếu nhập Link hãy nhấn GET ID để lấy ID</span>
                        </div>

                        <?php
                            $realAmount = get_real_amount($Servers, 1, $AuthUser->get('idname'));
                        ?>
                        <div class="mb-3">
                            <label for="spiRight">Số lượng</label>
                            <input id="spiRight" name="order_amount" type="number" placeholder="Nhập số lượng cần tăng" class="form-input input-number" min="<?= $realAmount->min ?>" max="<?= $realAmount->max ?>">
                            <p>Số lượng tối thiểu bạn có thể mua là: <strong id="min_text"><?= number_format($realAmount->min) ?></strong></p>
                            <p>Số lượng tối đa bạn có thể mua là: <strong id="max_text"><?= number_format($realAmount->max) ?></strong></p>
                        </div>

                        <div class="mb-3">
                            <label for="spiRight">Server</label>
                            <?php $index = 0; foreach ($Servers as $sv) : ?>
                                <?php if (!empty($sv->options->public)) : ?>
                                    <?php
                                        $index++;
                                        $realPrice = get_real_price($Servers, $sv->id, $AuthUser->get('idname'));
                                        $realAmount = get_real_amount($Servers, $sv->id, $AuthUser->get('idname'));
                                    ?>
                                    <label class="inline-flex">
                                        <input type="checkbox" class="form-checkbox rounded-full js-choose-server" data-price="<?= $realPrice ?>" data-min="<?= $realAmount->min ?>" data-max="<?= $realAmount->max ?>" name="server_id" id="sv_<?= $sv->id ?>" value="<?= $sv->id ?>" required <?= $index == 1 ? "checked" : "" ?> />
                                        <span>
                                            <?= $sv->name ?>
                                            <span class="badge badge-outline-secondary"><?= number_format($realPrice) . "đ" ?></span>
                                            <?php if ($sv->options->maintain) : ?>
                                                <span class="badge badge-outline-danger">Bảo trì</span>
                                            <?php endif ?>
                                        </span>
                                    </label>
                                <?php endif ?>
                            <?php endforeach ?>
                            <div class="form-text">Sẽ có giá khác nhau cho mỗi cấp tài khoản, cũng như trên từng server. <a href="<?= APPURL . "/pricing-details" ?>" target="_blank" style="color: #007bff;">Xem thêm tại đây</a></div>
                        </div>

                        <div class="mb-3">
                            <label for="spiRight">Loại tương tác</label>
                            <div class="flex w-full flex-wrap gap-4">
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="like" hidden name="reaction_type[]" value="LIKE">
                                    <label class="form-check-label" for="like"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/like.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="love" hidden name="reaction_type[]" value="LOVE">
                                    <label class="form-check-label" for="love"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/love.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="care" hidden name="reaction_type[]" value="CARE">
                                    <label class="form-check-label" for="care"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/care.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="haha" hidden name="reaction_type[]" value="HAHA">
                                    <label class="form-check-label" for="haha"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/haha.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="wow" hidden name="reaction_type[]" value="WOW">
                                    <label class="form-check-label" for="wow"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/wow.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="sad" hidden name="reaction_type[]" value="SAD">
                                    <label class="form-check-label" for="sad"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/sad.svg" ?>" width="45px" /></label>
                                </div>
                                <div class="form-check form-check-inline mb-3 me-1">
                                    <input type="checkbox" class="form-check-input input-checkbox" id="angry" hidden name="reaction_type[]" value="ANGRY">
                                    <label class="form-check-label" for="angry"><img class="opacity-25" style="cursor: pointer;" src="<?= APPURL . "/assets/images/reactions/angry.svg" ?>" width="45px" /></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="spiRight">Ghi chú (Không bắt buộc)</label>
                            <textarea id="ctnTextarea" type="text" name="note" rows="5" class="form-textarea" required="" placeholder="Nhập nội dung cần ghi chú"></textarea>
                        </div>
                        <div class="relative flex items-center rounded border border-success bg-success-light p-3.5 text-success before:absolute before:top-1/2 before:-mt-2 before:border-l-8 before:border-t-8 before:border-b-8 before:border-t-transparent before:border-b-transparent before:border-l-inherit ltr:border-l-[64px] ltr:before:left-0 rtl:border-r-[64px] rtl:before:right-0 rtl:before:rotate-180 dark:bg-success-dark-light">
                            <span class="absolute inset-y-0 m-auto h-6 w-6 text-white ltr:-left-11 rtl:-right-11">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6">
                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="white" stroke-width="1.5"/>
                                    <path d="M12 6V18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="ltr:pr-2 rtl:pl-2"><strong class="ltr:mr-1 rtl:ml-1">Tạm tính: </strong><span id="total_price_order">0</span>đ</span>
                        </div>
                        <div class="relative flex items-center rounded border border-danger bg-danger-light p-3.5 text-danger before:absolute before:top-1/2 before:-mt-2 before:border-l-8 before:border-t-8 before:border-b-8 before:border-t-transparent before:border-b-transparent before:border-l-inherit ltr:border-l-[64px] ltr:before:left-0 rtl:border-r-[64px] rtl:before:right-0 rtl:before:rotate-180 dark:bg-danger-dark-light">
                            <span class="absolute inset-y-0 m-auto h-6 w-6 text-white ltr:-left-11 rtl:-right-11">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z" stroke="white" stroke-width="1.5"/>
                                    <path opacity="0.5" d="M7 4V2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path opacity="0.5" d="M17 4V2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path opacity="0.5" d="M2.5 9H21.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M18 17C18 17.5523 17.5523 18 17 18C16.4477 18 16 17.5523 16 17C16 16.4477 16.4477 16 17 16C17.5523 16 18 16.4477 18 17Z" fill="white"/>
                                    <path d="M18 13C18 13.5523 17.5523 14 17 14C16.4477 14 16 13.5523 16 13C16 12.4477 16.4477 12 17 12C17.5523 12 18 12.4477 18 13Z" fill="white"/>
                                    <path d="M13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17Z" fill="white"/>
                                    <path d="M13 13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13C11 12.4477 11.4477 12 12 12C12.5523 12 13 12.4477 13 13Z" fill="white"/>
                                    <path d="M8 17C8 17.5523 7.55228 18 7 18C6.44772 18 6 17.5523 6 17C6 16.4477 6.44772 16 7 16C7.55228 16 8 16.4477 8 17Z" fill="white"/>
                                    <path d="M8 13C8 13.5523 7.55228 14 7 14C6.44772 14 6 13.5523 6 13C6 12.4477 6.44772 12 7 12C7.55228 12 8 12.4477 8 13Z" fill="white"/>
                                </svg>
                            </span>
                            <span class="ltr:pr-2 rtl:pl-2"><strong class="ltr:mr-1 rtl:ml-1">Bảo hành: </strong><?= $Service->get("warranty") ?> ngày</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary !mt-6">Thêm đơn</button>
                </form>
            </div>
        </div>

        <div class="panel h-full">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="text-lg font-semibold dark:text-white-light">Ghi chú dịch vụ</h5>
            </div>
            <div class="list-inside list-disc space-y-3 font-semibold">
                <?= $Service->get("note") ?>
            </div>
        </div>
    </div>
</div>