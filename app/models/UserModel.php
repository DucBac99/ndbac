<?php

/**
 * User Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class UserModel extends DataEntry
{
  /**
   * Extend parents constructor and select entry
   * @param mixed $uniqid Value of the unique identifier
   */
  public function __construct($uniqid = 0)
  {
    parent::__construct();
    $this->select($uniqid);
  }



  /**
   * Select entry with uniqid
   * @param  int|string $uniqid Value of the any unique field
   * @return self       
   */
  public function select($uniqid)
  {
    if (is_int($uniqid) || ctype_digit($uniqid)) {
      $col = $uniqid > 0 ? "id" : null;
    } else if (filter_var($uniqid, FILTER_VALIDATE_EMAIL)) {
      $col = "email";
    } else {
      $col = null;
    }


    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_USERS)
        ->where(TABLE_PREFIX . TABLE_USERS . "." . $col, "=", $uniqid)
        ->leftJoin(
          TABLE_PREFIX . TABLE_ROLES,
          TABLE_PREFIX . TABLE_USERS . ".role_id",
          "=",
          TABLE_PREFIX . TABLE_ROLES . ".id"
        )
        ->limit(1)
        ->select(TABLE_PREFIX . TABLE_USERS . ".*")
        ->select([TABLE_PREFIX . TABLE_ROLES . ".title", TABLE_PREFIX . TABLE_ROLES . ".idname", TABLE_PREFIX . TABLE_ROLES . ".color"]);
      if ($query->count() == 1) {
        $resp = $query->get();
        $r = $resp[0];

        foreach ($r as $field => $value)
          $this->set($field, $value);

        $this->is_available = true;
      } else {
        $this->data = array();
        $this->is_available = false;
      }
    }

    return $this;
  }


  /**
   * Extend default values
   * @return self
   */
  public function extendDefaults()
  {
    $defaults = array(
      "account_type" => "member",
      "role_id" => 3,
      "email" => uniqid() . "@sabosoft.com",
      "password" => uniqid(),
      "firstname" => "",
      "lastname" => "",
      "balance" => 0,
      "total_deposit" => 0,
      "site_id" => 0,
      "is_active" => 0,
      "has_site" => 0,
      "has_analytics" => 0,
      "is_viewer" => 0,
      "api_key" => "",
      "created_at" => date("Y-m-d H:i:s"),
      "updated_at" => date("Y-m-d H:i:s"),
      "data" => '{}',
    );


    foreach ($defaults as $field => $value) {
      if (is_null($this->get($field)))
        $this->set($field, $value);
    }
  }


  /**
   * Insert Data as new entry
   */
  public function insert()
  {
    if ($this->isAvailable())
      return false;

    $this->extendDefaults();

    $id = DB::table(TABLE_PREFIX . TABLE_USERS)
      ->insert(array(
        "id" => null,
        "account_type" => $this->get("account_type"),
        "role_id" => $this->get("role_id"),
        "email" => $this->get("email"),
        "password" => $this->get("password"),
        "firstname" => $this->get("firstname"),
        "lastname" => $this->get("lastname"),
        "balance" => $this->get("balance"),
        "total_deposit" => $this->get("total_deposit"),
        "site_id" => $this->get("site_id"),
        "is_active" => $this->get("is_active"),
        "has_site" => $this->get("has_site"),
        "has_analytics" => $this->get("has_analytics"),
        "is_viewer" => $this->get("is_viewer"),
        "api_key" => $this->get("api_key"),
        "created_at" => $this->get("created_at"),
        "updated_at" => date("Y-m-d H:i:s"),
        "data" => $this->get("data"),
      ));

    $this->set("id", $id);
    $this->markAsAvailable();
    return $this->get("id");
  }


  /**
   * Update selected entry with Data
   */
  public function update()
  {
    if (!$this->isAvailable())
      return false;

    $this->extendDefaults();

    $id = DB::table(TABLE_PREFIX . TABLE_USERS)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "account_type" => $this->get("account_type"),
        "role_id" => $this->get("role_id"),
        "email" => $this->get("email"),
        "password" => $this->get("password"),
        "firstname" => $this->get("firstname"),
        "lastname" => $this->get("lastname"),
        "balance" => $this->get("balance"),
        "total_deposit" => $this->get("total_deposit"),
        "site_id" => $this->get("site_id"),
        "is_active" => $this->get("is_active"),
        "has_site" => $this->get("has_site"),
        "has_analytics" => $this->get("has_analytics"),
        "is_viewer" => $this->get("is_viewer"),
        "api_key" => $this->get("api_key"),
        "created_at" => $this->get("created_at"),
        "updated_at" => date("Y-m-d H:i:s"),
        "data" => $this->get("data"),
      ));

    return $this;
  }


  /**
   * Remove selected entry from database
   */
  public function delete()
  {
    if (!$this->isAvailable())
      return false;

    DB::table(TABLE_PREFIX . TABLE_USERS)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }


  /**
   * Check if account has administrative privilages
   * @return boolean 
   */
  public function isAdmin()
  {
    if ($this->isAvailable() && in_array($this->get("role_id"), array(1, 2))) {
      return true;
    }

    return false;
  }

  /**
   * Check if account has viwer interactive privilages
   * @return boolean 
   */
  public function isViewer()
  {
    if ($this->isAvailable() && $this->get("is_viewer") == 1) {
      return true;
    }
    return false;
  }


  /**
   * Checks if this user can edit another user's data
   * 
   * @param  UserModel $User Another user
   * @return boolean          
   */
  public function canEdit(UserModel $User)
  {
    if ($this->isAvailable()) {
      if ($this->get("role_id") == 1 || $this->get("id") == $User->get("id")) {
        return true;
      }

      if (
        $this->get("role_id") == 2 &&
        (in_array($User->get("role_id"), array(2, 3)) ||
          !$User->isAvailable() // New User
        )
      ) {
        return true;
      }
    }

    return false;
  }


  /**
   * Check if user's (primary) email is verified or not
   * @return boolean 
   */
  public function isEmailVerified()
  {
    if (!$this->isAvailable()) {
      return false;
    }

    if ($this->get("data.email_verification_hash")) {
      return false;
    }

    return true;
  }


  /**
   * Send verification email to the user
   * @param  SiteModel $AuthSite
   * @param  boolean $force_new Create a new hash if it's true
   * @return [bool]                  
   */
  public function sendVerificationEmail(SiteModel $AuthSite, $force_new = false)
  {
    if (!$this->isAvailable()) {
      return false;
    }

    $hash = $this->get("data.email_verification_hash");

    if (!$hash || $force_new) {
      $hash = sha1(uniqid(readableRandomString(10), true));
    }


    // Send mail
    $mail = new \Email;
    $mail->addAddress($this->get("email"));
    $mail->Subject = __("{site_name} Account Activation", [
      "{site_name}" => $AuthSite->get("settings.site_name")
    ]);

    $body = "<p> Xin chào " . htmlchars($this->get("firstname")) . ", </p>"
      . "<p>Vui lòng xác minh địa chỉ email <strong>" . $this->get("email") . "</strong> của bạn. Để xác minh, hãy nhấp vào nút bên dưới."
      . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
      . "<a style='display: inline-block; background-color: #3b7cff; color: #fff; font-size: 14px; line-height: 24px; text-decoration: none; padding: 6px 12px; border-radius: 4px;' href='" . APPURL . "/verification/email/" . $this->get("id") . "." . $hash . "'>Xác Minh Email</a>"
      . "</div>";
    $mail->sendmail($body);

    // Save (new) hash
    $this->set("data.email_verification_hash", $hash)
      ->save();

    return true;
  }


  /**
   * Set the user's (primary) email address as verified
   */
  public function setEmailAsVerified()
  {
    if (!$this->isAvailable()) {
      return false;
    }

    $data = json_decode($this->get("data"));
    if (isset($data->email_verification_hash)) {
      unset($data->email_verification_hash);
      $this->set("data", json_encode($data))
        ->update();
    }

    return true;
  }
}
