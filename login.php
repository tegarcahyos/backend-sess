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

    // Fungsi Login
    public function authenticate($tablename)
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $data->username;
        $password = $data->password;

        $query = "SELECT * FROM $tablename WHERE username = '$username' LIMIT 1";
        // die($query);
        $result_user = $this->db->execute($query);
        $num = $result_user->rowCount();
        if ($num > 0) {
            $row_get = $result_user->fetchRow();
            if (!empty($row_get['password'])) {
                $msg = $this->data_user($result_user, $username, $password);
            } else {
                $msg = $this->LDAPLogin($username, $password, $row_get['id']);
            }
        } else {
            $msg = "203";
        }

        return $msg;
    }

    // Detail User
    private function data_user($result, $username, $password)
    {
        while ($row = $result->fetchRow()) {
            $user_id = $row['id'];
            $name = $row['name'];
            $password2 = $row['password'];
            $employee_id = $row['employee_id'];
        }
        die($password + " " + $password2);
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
                )
            );

            $jwt = JWT::encode($token, $secret_key);
            $insert_token = "UPDATE users SET token = '$jwt', expire_at = '$expire_claim' WHERE id = '$user_id'";
            // die($insert_token);
            $this->db->execute($insert_token);
            $update_expireAt = "UPDATE users SET expire_at = '$expire_claim' WHERE id = '$user_id'";
            $this->db->execute($update_expireAt);

            // GET USER UNIT
            $query2 = "SELECT * FROM user_detail WHERE user_id = '$user_id'";
            $result = $this->db->execute($query2);
            $row = $result->fetchRow();
            if (is_bool($row)) {
                $unit_id = null;
                $role_id = null;
            } else {
                extract($row);
                $unit_id = $row['unit_id'];
                $role_id = $row['role_id'];
            }

            $msg = array(
                "message" => "Successful login.",
                "id" => $user_id,
                "employee_id" => $employee_id,
                "name" => $name,
                "username" => $username,
                "password" => $password,
                "expireAt" => $expire_claim,
                "role_id" => $role_id,
                "unit_id" => $unit_id,
                "token" => $jwt,
            );
        } else {
            $msg = "506";
        }
        return $msg;
    }

    public function LDAPLogin($username, $password, $id)
    {
        // $data = json_decode(file_get_contents("php://input"));

        // $username = $data->username;
        // $password = $data->password;

        $data_array = array(
            "account" => $username,
            "privatekey" => $password,
        );
        $login = $this->callAPI('POST', 'https://auth.telkom.co.id/account/validate', json_encode($data_array));
        $response = json_decode($login);
        // die(print_r($response));
        $result_user;
        if ($response->login != 0) {
            // $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
            // // die($query);
            // $result_get = $this->db->execute($query);
            // $num = $result_get->rowCount();
            // if ($num > 0) {
            // $row_get = $result_get->fetchRow();
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $insert_password = "UPDATE users SET password = '$password_hash' WHERE id = '$id'";
            $this->db->execute($insert_password);
            // LOGIN
            $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1 ";
            $result_user = $this->db->execute($query);
            $msg = $this->data_user($result_user, $username, $password);
        }
        // } else {
        //     $msg = "203";
        // }

        return $msg;
    }

    public function callAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            // 'x-authorization: ' . $this->apiKey,
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        // die("ini token" . $this->apiKey);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }
}
