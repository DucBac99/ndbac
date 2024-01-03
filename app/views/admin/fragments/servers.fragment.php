<div class="animate__animated p-6">
    <div x-data="custom">
        <div class="space-y-6">
            <div class="panel items-center p-2">
                <div class="flex items-center">
                    <span class="ltr:mr-3 rtl:ml-3 w-full">Danh sách servers</span>
                    <div class="ml-auto flex space-x-2">
                    <a type="button" class="btn btn-success text-white p-2" href="<?= APPURL . "/servers/new" ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Thêm
                    </a>
                    <a class="btn btn-danger text-white p-2 js-remove-list-item" data-url="<?= APPURL . "/servers" ?>" href="javascript:void(0)" data-table="#servers_table">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.1709 4C9.58273 2.83481 10.694 2 12.0002 2C13.3064 2 14.4177 2.83481 14.8295 4" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M20.5001 6H3.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M9.5 11L10 16" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M14.5 11L14 16" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Xoá</a>
                </div>
                </div>
            </div>
            <div class="panel">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="servers_table" data-url="<?= APPURL . "/servers" ?>">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-checkbox">
                                    </th>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>API URL</th>
                                    <th>Ngày cập nhật</th>
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

