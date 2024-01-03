<?php

/**
 * Role Controller
 */
class RoleController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $Route = $this->getVariable("Route");
        $AuthUser = $this->getVariable("AuthUser");

        // Auth
        if (!$AuthUser || !$AuthUser->isAdmin()) {
            header("Location: " . APPURL . "/login");
            exit;
        }

        $Role = Controller::model("Role");
        if (isset($Route->params->id)) {
            $Role->select($Route->params->id);

            if (!$Role->isAvailable()) {
                header("Location: " . APPURL . "/roles");
                exit;
            }
        }

        $this->setVariable("Role", $Role);

        if (Input::post("action") == "save") {
            $this->save();
        }

        $this->view("role");
    }


    /**
     * Save (new|edit) role
     * @return void 
     */
    private function save()
    {
        $this->resp->result = 0;
        $Role = $this->getVariable("Role");
        $AuthSite = $this->getVariable("AuthSite");

        // Check if this is new or not
        $is_new = !$Role->isAvailable();

        // Check required fields
        $required_fields = ["idname", "title", "color", "amount"];

        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
                $this->jsonecho();
            }
        }

        if ($is_new) {
            $Role->set("site_id", $AuthSite->get("id"));
        }
        // Start setting data
        $Role->set("idname", Input::post("idname"))
            ->set("title", Input::post("title"))
            ->set("color", Input::post("color"))
            ->set("amount", intval(Input::post("amount")))
            ->save();


        $this->resp->result = 1;
        if ($is_new) {
            $this->resp->msg = "Đã thêm vai trò thành công! Vui lòng làm mới trang.";
            $this->resp->reset = true;
        } else {
            $this->resp->msg = "Đã lưu thay đổi";
        }
        $this->jsonecho();
    }
}
