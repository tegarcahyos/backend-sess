<?php
// require "vendor/autoload.php";
include "vendor/autoload.php";
use \Firebase\JWT\JWT;

class Login
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function authenticate($tablename)
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $data->username;
        $password = $data->password;

        $query = "SELECT * FROM " . $tablename . " WHERE username = '$username' LIMIT 1 ";

        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {
            while ($row = $result->fetchRow()) {
                $user_id = $row['id'];
                $name = $row['name'];
                $password2 = $row['password'];
                $user_token = $row['token'];
            }
            // die($password2);
            if (password_verify($password, $password2)) {
                $secret_key = "YOUR_SECRET_KEY";
                $issuer_claim = "THE_ISSUER"; // this can be the servername
                $audience_claim = "THE_AUDIENCE";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 600; // expire time in seconds
                $token = array(
                    "iss" => $issuer_claim,
                    "aud" => $audience_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "name" => $name,
                        "username" => $username,
                    ));

                $jwt = JWT::encode($token, $secret_key);
                if ($user_token == null) {
                    $insert_token = "UPDATE users SET token = '$jwt', expireAt = '$expireAt' WHERE id = $user_id";
                    // die($insert_token);
                    $this->db->execute($insert_token);
                } else {
                    $update_expireAt = "UPDATE users SET expire_at = '$expireAt' WHERE id = $user_id";
                    $this->db->execute($update_expireAt);
                }

                // GET USER ROLE
                $query = "SELECT * FROM user_role WHERE user_id = $user_id";
                $result = $this->db->execute($query);
                // extract($row);
                $row = $result->fetchRow();
                if (is_bool($row)) {
                    $role_id = null;
                    $role_name = null;
                } else {
                    extract($row);
                    $role_id = $row['role_id'];
                    $role_name = $row['role_name'];
                }

                // GET USER UNIT
                $query2 = "SELECT * FROM user_unit WHERE user_id = $user_id";
                // die($query2);
                $result = $this->db->execute($query2);
                $row = $result->fetchRow();
                if (is_bool($row)) {
                    $unit_id = null;
                    $unit_code = null;
                    $unit_name = null;
                } else {
                    extract($row);
                    $unit_id = $row['unit_id'];
                    $unit_code = $row['unit_code'];
                    $unit_name = $row['unit_name'];
                }

                $msg = array(
                    "code" => 200,
                    "message" => "Successful login.",
                    "name" => $name,
                    "username" => $username,
                    "expireAt" => $expireAt,
                    "role_id" => $role_id,
                    "role_name" => $role_name,
                    "unit_id" => $unit_id,
                    "unit_code" => $unit_code,
                    "unit_name" => $unit_name,
                    "token" => $user_token,
                );

            } else {

                // http_response_code(401);
                $msg = array("message" => "Login failed Wrong Password.", "code" => 401);
            }
        } else {
            $msg = array("message" => 'User Tidak Ditemukan', "code" => 400);
        }
        return $msg;
    }
}
