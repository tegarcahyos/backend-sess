<?php
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

        $query = "SELECT * FROM $tablename WHERE username = '$username' LIMIT 1 ";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {
            while ($row = $result->fetchRow()) {
                $user_id = $row['id'];
                $name = $row['name'];
                $email = $row['email'];
                $phone = $row['phone'];
                $password2 = $row['password'];
            }
            // die($password2);
            if (password_verify($password, $password2)) {
                $secret_key = "YOUR_SECRET_KEY";
                $issuer_claim = "THE_ISSUER"; // this can be the servername
                $audience_claim = "THE_AUDIENCE";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 36000; // expire time in seconds
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
                $insert_token = "UPDATE users SET token = '$jwt', expire_at = '$expire_claim' WHERE id = '$user_id'";
                // die($insert_token);
                $this->db->execute($insert_token);
                $update_expireAt = "UPDATE users SET expire_at = '$expire_claim' WHERE id = '$user_id'";
                $this->db->execute($update_expireAt);

                // GET USER UNIT
                $query2 = "SELECT * FROM user_detail WHERE user_id = '$user_id'";
                // die($query2);
                $result = $this->db->execute($query2);
                $row = $result->fetchRow();
                if (is_bool($row)) {
                    $unit_id = null;
                    $unit_code = null;
                    $unit_name = null;
                    $role_id = null;
                    $role_name = null;
                } else {
                    extract($row);
                    $unit_id = $row['unit_id'];
                    $role_id = $row['role_id'];
                }

                $msg = array(
                    "message" => "Successful login.",
                    "id" => $user_id,
                    "name" => $name,
                    "username" => $username,
                    "password" => $password,
                    "email" => $email,
                    "phone" => $phone,
                    "expireAt" => $expire_claim,
                    "role_id" => $role_id,
                    "unit_id" => $unit_id,
                    "token" => $jwt,
                );

            } else {

                // http_response_code(401);
                $msg = "404";
            }
        } else {
            $msg = "404";
        }
        return $msg;
    }

    public function apiFactory()
    {
        $url = 'https://apifactory.telkom.co.id:8243/hcm/auth/v1/token';
        $data = array('username' => $username, 'password' => $password);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === false) { /* Handle error */}

        die(print_r($result));
    }
}
