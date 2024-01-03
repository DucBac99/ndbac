<div class="animate__animated p-6">
    <div x-data="custom">
        <div class="space-y-6">
        <h5 class="text-lg font-semibold dark:text-white-light">Danh sách đơn hàng <span class="fw-normal"><?= htmlchars($Service->get("title") . " - " . $Service->get("group")) ?></h5>
            <div class="panel items-center p-2">
                <div class="flex items-center justify-between">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-4">
                        <div class="mb-3 items-center justify-between">
                            <h5 class="text-lg dark:text-white-light">Nâng cao:</h5>
                        </div>
                        <div>
                            <select class="form-select text-white-dark" name="status">
                                <option disabled selected>Status</option>
                                <option value="">All</option>
                                <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                    <option value="<?= $site->get("id") ?>" <?= $AuthSite->get("id") == $site->get("id") ? "selected" : "" ?>><?= $site->get("domain") ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <?php if ($AuthUser->isAdmin()) : ?>
                        <div>
                            <select class="form-select text-white-dark" name="site_id">
                                <option disabled selected value="">Tên miền</option>
                                <option value="">All</option>
                                <?php foreach ($Sites->getDataAs("Site") as $site) : ?>
                                <option value="<?= $site->get("id") ?>"><?= $site->get("domain") ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div>
                            <select class="form-select text-white-dark" name="server_id">
                                <option disabled selected value="">Server</option>
                                <option value="">All</option>
                                <?php foreach ($Servers as $sv) : ?>
                                    <option value="<?= $sv->id ?>"><?= $sv->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="ml-auto flex space-x-2">
                        <a type="button" class="btn btn-success text-white p-2" href="<?= $uri . "/new" ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            Thêm
                        </a>
                        <button type="button" class="btn btn-secondary text-white p-2 js-group-order" data-table="#orders_table" data-url="<?= $uri ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <path d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z" fill="white"/>
                                <path opacity="0.5" d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M8.5 18.5L8.5 13.5M8.5 13.5L6.5 15.375M8.5 13.5L10.5 15.375" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Gộp đơn
                        </button>
                        <button type="button" class="btn btn-warning text-white p-2 js-warranty-check" data-table="#orders_table" data-url="<?= $uri ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <circle cx="12" cy="12" r="10" stroke="white" stroke-width="1.5"/>
                                <circle cx="12" cy="12" r="4" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M15 9L19 5" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M5 19L9 15" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M9 9L5 5" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M19 19L15 15" stroke="white" stroke-width="1.5"/>
                            </svg>
                            Bảo hành
                        </button>
                        <?php if ($AuthUser->isAdmin()) : ?>
                        <button type="button" class="btn btn-dark text-white p-2 btn-refund-order-bulk" data-table="#orders_table" data-url="<?= $uri ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <g clip-path="url(#clip0_1276_6232)">
                                <path d="M19.7285 10.9288C20.4413 13.5978 19.7507 16.5635 17.6569 18.6573C14.5327 21.7815 9.46736 21.7815 6.34316 18.6573C3.21897 15.5331 3.21897 10.4678 6.34316 7.3436C9.46736 4.21941 14.5327 4.21941 17.6569 7.3436L18.364 8.05071M18.364 8.05071H14.1213M18.364 8.05071V3.80807" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_1276_6232">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>
                            Hoàn
                        </button>
                        <button type="button" class="btn btn-primary text-white p-2" data-bs-toggle="modal" data-bs-target="#modal_edit_orders">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <path opacity="0.5" d="M4 22H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M14.6296 2.92142L13.8881 3.66293L7.07106 10.4799C6.60933 10.9416 6.37846 11.1725 6.17992 11.4271C5.94571 11.7273 5.74491 12.0522 5.58107 12.396C5.44219 12.6874 5.33894 12.9972 5.13245 13.6167L4.25745 16.2417L4.04356 16.8833C3.94194 17.1882 4.02128 17.5243 4.2485 17.7515C4.47573 17.9787 4.81182 18.0581 5.11667 17.9564L5.75834 17.7426L8.38334 16.8675L8.3834 16.8675C9.00284 16.6611 9.31256 16.5578 9.60398 16.4189C9.94775 16.2551 10.2727 16.0543 10.5729 15.8201C10.8275 15.6215 11.0583 15.3907 11.5201 14.929L11.5201 14.9289L18.3371 8.11195L19.0786 7.37044C20.3071 6.14188 20.3071 4.14999 19.0786 2.92142C17.85 1.69286 15.8581 1.69286 14.6296 2.92142Z" stroke="white" stroke-width="1.5"/>
                                <path opacity="0.5" d="M13.8879 3.66406C13.8879 3.66406 13.9806 5.23976 15.3709 6.63008C16.7613 8.0204 18.337 8.11308 18.337 8.11308M5.75821 17.7437L4.25732 16.2428" stroke="white" stroke-width="1.5"/>
                            </svg>
                            Sửa
                        </button>
                        <button type="button" class="btn btn-danger text-white p-2 js-remove-list-item" data-url="<?= $uri ?>" data-table="#orders_table">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 ltr:mr-1.5 rtl:ml-1.5">
                                <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M20.5001 6H3.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M9.5 11L10 16" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                <path opacity="0.5" d="M14.5 11L14 16" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            Xóa
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="orders_table" data-url="<?= $uri ?>">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-checkbox">
                                    </th>
                                    <th>ID</th>
                                    <th>Seeding UID</th>
                                    <th>Order Amount</th>
                                    <th>Start Num</th>
                                    <th>Seeding Num</th>
                                    <th>Reaction</th>
                                    <th>Status</th>
                                    <th>Bảo hành</th>
                                    <th>Note</th>
                                    <th>Note Extra</th>
                                    <th>Ngày tạo</th>
                                    <th>Người tạo</th>
                                    <th>Nhóm</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

