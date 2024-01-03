<?php

namespace Profile;

use Controller;
use Input;

class TopupController extends Controller
{
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");
        $Route = $this->getVariable("Route");

        if (!$AuthUser) {
            header("Location: " . APPURL . "/login");
            exit;
        }
        $this->view("profile/topup");
    }
}
