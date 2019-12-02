<?php
// require "../vendor/autoload.php";
// use \Firebase\JWT\JWT;

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

        $query = "SELECT * FROM " . $tablename . " WHERE username = '$username' LIMIT 1";

        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {
            while ($row = $result->fetchRow()) {
                $name = $row['name'];
                $password2 = $row['password'];
            }
            die($password2);
            if (password_verify($password, $password2)) {
                $secret_key = "YOUR_SECRET_KEY";
                $issuer_claim = "THE_ISSUER"; // this can be the servername
                $audience_claim = "THE_AUDIENCE";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 60; // expire time in seconds
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

                http_response_code(200);

                $jwt = JWT::encode($token, $secret_key);

                $msg = array(
                    "message" => "Successful login.",
                    "token" => $jwt,
                    "username" => $username,
                    "expireAt" => $expire_claim,
                );

            } else {

                http_response_code(401);
                $msg = array("message" => "Login failed.", "password" => $password);
            }
            return $msg;
        }
    }
}
