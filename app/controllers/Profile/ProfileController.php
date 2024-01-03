<?php
namespace Profile;
use Controller;
use Input;
/**
 * Profile Controller
 */
class ProfileController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");

        if (!$AuthUser){
            // Auth
            header("Location: ".APPURL."/login");
            exit;
        }

        if (Input::post("action") == "save") {
            $this->save();
        } else if (Input::post("action") == "resend-email") {
            $this->resendVerificationEmail();
        } 
        $this->view("profile/profile");
    }


    /**
     * Save changes
     * @return void 
     */
    private function save()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");


        // Check required fields
        $required_fields = ["firstname", "lastname"];

        foreach ($required_fields as $field) {
            if (!Input::post($field)) {
                $this->resp->msg = __("Missing some of required data.");
                $this->jsonecho();
            }
        }

        // Check pass.
        if (mb_strlen(Input::post("password")) > 0) {
            if (mb_strlen(Input::post("password")) < 6) {
                $this->resp->msg = "Mật khẩu phải dài ít nhất 6 ký tự!";
                $this->jsonecho();
            } 

            if (Input::post("password-confirm") != Input::post("password")) {
                $this->resp->msg = "Xác nhận mật khẩu không khớp!";
                $this->jsonecho();
            }
        }

        // Start setting data
        $AuthUser->set("firstname", Input::post("firstname"))
                 ->set("lastname", Input::post("lastname"));


        if (mb_strlen(Input::post("password")) > 0) {
            $passhash = password_hash(Input::post("password"), PASSWORD_DEFAULT);
            $AuthUser->set("password", $passhash);
        }

        $AuthUser->save();

        // update cookies
        setcookie("nplh", $AuthUser->get("id").".".md5($AuthUser->get("password")), 0, "/");

         $this->resp->result = true;
        $this->resp->msg = "Đã lưu thay đổi";
        $this->jsonecho();
    }



    /**
     * Resend the email to verify the email
     * @return void 
     */
    private function resendVerificationEmail()
    {
        $this->resp->result = 0;
        $AuthUser = $this->getVariable("AuthUser");
        $AuthSite = $this->getVariable("AuthSite");

        if ($AuthSite->get("email_settings.email_verification")) {
            // Don't force to create new hash for the verification
            // Send the link with same hash if the hash is available
            $AuthUser->sendVerificationEmail($AuthSite, false);
        }

        $this->resp->result = 1;
        $this->resp->msg = "Đã gửi email xác minh";
        $this->jsonecho();
    }
}
