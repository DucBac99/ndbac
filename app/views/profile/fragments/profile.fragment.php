<div class="animate__animated p-6">

<div>
    <form class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]" class="js-ajax-form" action="<?= APPURL . "/profile" ?>" method="POST">
    <input type="hidden" name="action" value="save">
        <h6 class="mb-5 text-lg font-bold">Thông tin người dùng</h6>
        <div class="flex flex-col sm:flex-row">
            <div class="mb-5 w-full sm:w-2/12 ltr:sm:mr-4 rtl:sm:ml-4">
                <img src="assets//images/profile-34.jpeg" alt="image" class="mx-auto h-20 w-20 rounded-full object-cover md:h-32 md:w-32">
            </div>
            <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name">Tên đầu</label>
                    <input id="name" type="text" name="firstname" value="<?= $AuthUser->get("firstname") ?>" class="form-input">
                </div>
                <div>
                    <label for="profession">Tên cuối</label>
                    <input id="profession" type="text" name="lastname" value="<?= $AuthUser->get("lastname") ?>" class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label for="location">Email</label>
                    <input id="location" type="text" readonly="readonly" value="<?= $AuthUser->get("email") ?>" class="form-input">
                </div>
                <div>
                    <label for="phone">Mật khẩu mới</label>
                    <input id="phone" type="password" name="password" class="form-input">
                    <p class="form-text text-muted">
                     Nếu bạn không muốn thay đổi mật khẩu thì hãy để trống các trường mật khẩu này!
                   </p>
                </div>
                <div>
                    <label for="email">Xác nhận mật khẩu</label>
                    <input id="email" type="password" name="password-confirm" class="form-input">
                </div>
                <div class="mt-3 sm:col-span-2">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
