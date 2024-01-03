<?php

/**
 * Roles Controller
 */
class RolesController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }
    if (Input::get("draw")) {
      $this->getRoles();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    }

    $Sites = Controller::model("Sites");
    $Sites->where("id", ">", 0)
      ->fetchData();
    $this->setVariable("Sites", $Sites);
    $this->view("roles");
  }


  /**
   * Remove Role
   * @return void 
   */
  private function remove()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!Input::post("id")) {
      $this->resp->msg = "Cần nhập ID!";
      $this->jsonecho();
    }

    $Role = Controller::model("Role", Input::post("id"));

    if (!$Role->isAvailable()) {
      $this->resp->msg = "Vai trò không tồn tại!";
      $this->jsonecho();
    }

    $checkUser = DB::table(TABLE_PREFIX . TABLE_USERS)
      ->where("role_id", "=", $Role->get("id"))
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($checkUser[0]->total > 0) {
      $this->resp->msg = "Vai trò đã có người dùng, không thể xoá!";
      $this->jsonecho();
    }
    $Role->delete();

    $this->resp->result = 1;
    $this->jsonecho();
  }

  /** 
   * Get Roles
   * @return void
   */
  private function getRoles()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order = Input::get("order");
    $search = Input::get("search");
    $start = (int)Input::get("start");
    $draw = (int)Input::get("draw");
    $length = (int)Input::get("length");
    $site_id = (int)Input::get("site_id");

    if ($draw) {
      $this->resp->draw = $draw;
    }

    $data = [];

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_ROLES)
        ->where(TABLE_PREFIX . TABLE_ROLES . ".id", ">", 3)
        ->leftJoin(
          TABLE_PREFIX . TABLE_SITES,
          TABLE_PREFIX . TABLE_ROLES . ".site_id",
          "=",
          TABLE_PREFIX . TABLE_SITES . ".id"
        );

      if ($site_id) {
        $query->where(TABLE_PREFIX . TABLE_ROLES . ".site_id", "=", $site_id);
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_ROLES . ".idname", 'LIKE', $search_query . '%')
            ->orWhere(TABLE_PREFIX . TABLE_ROLES . ".title", 'LIKE', $search_query . '%');
        });
      }

      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_ROLES . "`.`" . $column_name . "` AS unsigned)"), $sort);
        } else if (strpos($column_name, ":") !== false) {
          $column_name = str_replace(":", "`.`", $column_name);
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . $column_name . "` "), $sort);
        } else if (strpos($column_name, ".") !== false) {
          $column_name = explode(".", $column_name);
          $table = array_shift($column_name);
          $path_json = [];
          foreach ($column_name as $f) {
            $path_json[] = $f;
          }
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_ROLES . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_ROLES . ".id", "DESC");
      }


      $query->select([
        TABLE_PREFIX . TABLE_ROLES . ".id",
        TABLE_PREFIX . TABLE_ROLES . ".idname",
        TABLE_PREFIX . TABLE_ROLES . ".title",
        TABLE_PREFIX . TABLE_ROLES . ".color",
        TABLE_PREFIX . TABLE_ROLES . ".amount",
        TABLE_PREFIX . TABLE_ROLES . ".updated_at",
        TABLE_PREFIX . TABLE_SITES . ".domain",
      ])->limit($length)
        ->offset($start);

      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      //$this->resp->sql = $query;
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }
}
