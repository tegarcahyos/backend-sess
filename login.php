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
        $result = $this->db->execute($query);
        $num = $result->rowCount();
        // $aduh = $result->fetchRow();
        // $get_user_request = "SELECT * FROM $tablename WHERE username = '$username' AND password is null LIMIT 1 ";
        // // die($get_user_request);
        // $result_req = $this->db->execute($get_user_request);
        // $num_req = $result_req->rowCount();
        if ($num > 0) {
            $msg = $this->data_user($result, $username, $password);
        } else if (empty($aduh['password'])) {

            // // Jika data tidak ditemukan
            // $check = "SELECT DISTINCT * FROM employee  WHERE n_nik = '$username'";
            // // die($check);
            // $result = $this->db->execute($check);
            // $row = $result->fetchRow();
            // if (is_bool($row)) {
            //     $msg = "203";
            // } else {
            //     // MIGRATE EMP TO USR
            //     $password_hash = password_hash($password, PASSWORD_BCRYPT);
            //     $migrate_user = "INSERT INTO request_account (name, username, password, employee_id) VALUES ('" . $row['v_nama_karyawan'] . "', '" . $row['n_nik'] . "', '$password_hash','" . $row['id'] . "') RETURNING *";
            //     $resultUser = $this->db->execute($migrate_user);
            //     $user = $resultUser->fetchRow();

            //     $get_unit = "SELECT * FROM unit WHERE LOWER(code) = LOWER('" . $row['c_kode_unit'] . "')";
            //     $result = $this->db->execute($get_unit);
            //     $unit = $result->fetchRow();

            //     $insert_detail = "INSERT INTO user_detail (user_id, unit_id) VALUES ('" . $user['id'] . "', '" . $unit['id'] . "')";
            //     $result = $this->db->execute($insert_detail);
            //     // LOGIN
            //     $query = "SELECT * FROM $tablename WHERE username = '$username' LIMIT 1 ";
            //     $result = $this->db->execute($query);
            //     $msg = $this->data_user($result, $username, $password);
            // }
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
        if (empty($password2)) {
            echo "goblok";
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $insert_password = "UPDATE users SET password = $password_hash WHERE id = '" . $row['id'] . "'";
            // die($insert_password);
            $this->db->execute($insert_password);
            // LOGIN
            $query = "SELECT * FROM $tablename WHERE username = '$username' LIMIT 1 ";
            $result = $this->db->execute($query);
            $msg = $this->data_user($result, $username, $password);
        } else {
            // die($password2);
            // die(password_verify($password, $password2));
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

                // http_response_code(401);
                $msg = "506";
            }
        }

        return $msg;
    }

    public function LDAPLogin()
    {
        $data = json_decode(file_get_contents("php://input"));

        $username = $data->username;
        $password = $data->password;

        $data_array = array(
            "account" => $username,
            "privatekey" => $password,
        );
        $login = $this->callAPI('POST', 'https://auth.telkom.co.id/account/validate', json_encode($data_array));
        $response = json_decode($login);
        // die(print_r($response));

        if ($response->login != 0) {
            $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1 ";
            // die($query);
            $result = $this->db->execute($query);
            $msg = $this->data_user($result, $username, $password);
        } else {
            $msg = "203";
        }

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
        if (!$result) {die("Connection Failure");}
        curl_close($curl);
        return $result;
    }
}
