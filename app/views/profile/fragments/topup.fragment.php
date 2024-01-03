<div class="animate__animated p-6">
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="text-lg font-semibold dark:text-white-light">Thông tin chuyển khoản</h5>
            </div>
            <div class="mb-5">
                <form class="space-y-5">
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalEmail" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Số tài khoản</label>
                        <input id="horizontalEmail" type="text" placeholder="" class="form-input flex-1" disabled="disabled" value="<?= $AuthSite->get("banking.info.account_number") ?>">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalPassword" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Ngân hàng</label>
                        <input type="text" placeholder="" disabled="disabled" class="form-input flex-1" value="<?= $AuthSite->get("banking.info.bank") ?>">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalEmail" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Chủ tài khoản</label>
                        <input id="horizontalEmail" type="text" placeholder="" class="form-input flex-1" disabled="disabled" value="<?= $AuthSite->get("banking.info.account_name") ?>">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalPassword" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Chi Nhánh</label>
                        <input type="text" placeholder="" disabled="disabled" class="form-input flex-1" value="<?= $AuthSite->get("banking.info.branch") ?>">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalEmail" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Nội dung chuyển khoản</label>
                        <input id="horizontalEmail" type="text" placeholder="" class="form-input flex-1" disabled="disabled" value="<?= $AuthSite->get("banking.info.content") ?>">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <label for="horizontalPassword" class="mb-0 rtl:ml-2 sm:w-1/4 sm:ltr:mr-2">Nhập số tiền</label>
                        <input type="text" placeholder="Nhập vào số tiền để tạo QR code" class="form-input flex-1">
                    </div>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="mb-5 flex items-center justify-between">
                <h5 class="text-lg font-semibold dark:text-white-light">Mã QR chuyển khoản</h5>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center">
                <img src="https://img.vietqr.io/image/<?= $AuthSite->get("banking.auth.bank_code") ?>-<?= $AuthSite->get("banking.info.account_number") ?>-<?= $AuthSite->get("banking.auth.template") ?>.png?amount=0&accountName=<?= $AuthSite->get("banking.info.account_name") ?>&addInfo=<?= $AuthSite->get("banking.info.content") . " " . $AuthUser->get("id") ?>" alt="image" class="h-full w-full object-cover" id="qr_code" style="width: 400px;">
            </div>
        </div>

        <div class="panel lg:col-span-2 bg-warning-light text-black">
            <h5 class="mb-2 text-lg font-semibold dark:text-white-light">Lưu ý</h5>
            <div class="w-full rounded-md">
                <ul class="space-y-3 font-semibold">
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-primary ltr:mr-2 rtl:ml-2 rtl:rotate-180">
                            <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="list-text">Quý khách ghi đúng thông tin nạp tiền thì tài khoản sẽ được cộng tự động sau khi giao dịch thành công.</span>
                    </li>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-primary ltr:mr-2 rtl:ml-2 rtl:rotate-180">
                            <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="list-text">Quý khách nhập sai nội dung chuyển khoản sẽ không được hoàn tiền</span>
                    </li>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-primary ltr:mr-2 rtl:ml-2 rtl:rotate-180">
                            <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="list-text">Vui lòng nạp tối thiểu 100.000đ, dưới tối thiểu sẽ không hỗ trợ giải quyết</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>