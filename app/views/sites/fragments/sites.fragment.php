<div class="animate__animated p-6">
    <div x-data="custom">
        <div class="space-y-6">
            <div class="panel items-center p-2">
                <div class="flex items-center">
                    <span class="ltr:mr-3 rtl:ml-3 w-full">Danh sách Site</span>
                    <div class="ml-auto flex space-x-2">
                    <a type="button" class="btn btn-success text-white p-2" href="<?= APPURL . "/sites/new" ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Thêm
                    </a>
                    <a type="button" class="btn btn-danger text-white p-2 js-remove-list-item" data-url="<?= APPURL . "/sites" ?>" href="javascript:void(0)" data-table="#sites_table">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    Xóa
                    </a>
                </div>
                </div>
            </div>
            <div class="panel">
                <h5 class="mb-5 text-lg font-semibold dark:text-white-light md:absolute md:top-[25px] md:mb-0">Bảng danh sách</h5>
                <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                    <div class="dataTable-top">
                        <div class="dataTable-search">
                            <input class="dataTable-input" placeholder="Search..." type="text">
                        </div>
                    </div>
                    <div class="dataTable-container">
                        <table id="myTable" class="table-checkbox whitespace-nowrap dataTable-table">
                            <thead>
                                <tr>
                                    <th data-sortable="false" style="width: 3.7299%;">
                                        <input type="checkbox" class="form-checkbox" :value="ids.length === tableData.length" @change="checkAll($event.target.checked)">
                                    </th>
                                    <th data-sortable="" style="width: 9.83923%;"><a href="#" class="dataTable-sorter">ID</a>
                                    </th>
                                    <th data-sortable="" style="width: 17.3633%;"><a href="#" class="dataTable-sorter">Tên miền</a>
                                    </th>
                                    <th data-sortable="" style="width: 17.1061%;"><a href="#" class="dataTable-sorter">Trạng thái</a>
                                    </th>
                                    <th data-sortable="" style="width: 31.5756%;"><a href="#" class="dataTable-sorter">Admin</a>
                                    </th>
                                    <th data-sortable="" style="width: 20.3859%;"><a href="#" class="dataTable-sorter">Số Users</a>
                                    </th>
                                    <th data-sortable="" style="width: 20.3859%;"><a href="#" class="dataTable-sorter">Cập nhật</a>
                                    </th>
                                    <th data-sortable="" style="width: 20.3859%;"><a href="#" class="dataTable-sorter">Hành động </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-checkbox" x-model="ids" :value="1" value="1">
                                    </td>
                                    <td>1</td>
                                    <td>Caroline</td>
                                    <td>Jensen</td>
                                    <td>carolinejensen@zidant.com</td>
                                    <td>+1 (821) 447-3782</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="dataTable-bottom">
                        <div class="dataTable-info">Showing 1 to 10 of 25 entries</div>
                        <div class="dataTable-dropdown">
                            <label>
                                <select class="dataTable-selector">
                                    <option value="10" selected="">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                        </div>
                        <nav class="dataTable-pagination">
                            <ul class="dataTable-pagination-list">
                                <li class="pager">
                                    <a href="#" data-page="1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="active"><a href="#" data-page="1">1</a>
                                </li>
                                <li class=""><a href="#" data-page="2">2</a>
                                </li>
                                <li class=""><a href="#" data-page="3">3</a>
                                </li>
                                <li class="pager">
                                    <a href="#" data-page="2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="pager">
                                    <a href="#" data-page="3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                            <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>