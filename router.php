<?php
include "adodb/adodb.inc.php";
include "models/organization.php";
include "models/organization_unit.php";
include "models/app.php";
include "models/metric.php";
include "models/user.php";
include "models/user_organization.php";
include "models/user_organization_unit.php";
include "models/user_role.php";
if (file_exists('settings.php')) {
    include 'settings.php';
} else {
    define('db_username', 'pmo');
    define('db_password', 'pass4pmo');
    define('db_name1', "core");
    define("db_host", "localhost");
    define("db_port", "5432");
}

// require __DIR__ . '/vendor/autoload.php';

class Router
{
    public $url;
    public $db;
    public $db_config;

    public function __construct()
    {
        // url digunakan untuk menyimpan url saat ini
        $link = "";
        $link .= $_SERVER['HTTP_HOST'];
        $link .= $_SERVER['REQUEST_URI'];
        $this->url = $link;
    }

    public function db_connect()
    {
        $this->db = newADOConnection('pgsql');
        $this->db->connect(db_host, db_username, db_password, db_name1);
        // die($this->db);
        return $this->db;
    }

    public function get_table_db()
    {
        $tempDb = $this->db_connect();
        $query = "SELECT
        tablename
            FROM
                pg_catalog.pg_tables
            WHERE
                schemaname != 'pg_catalog'
            AND schemaname != 'information_schema'";
        $result = $tempDb->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {
            $table = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $table_name = array(
                    'tablename' => $tablename,
                );

                array_push($table, $table_name);
            }

            return $table;
        }
    }

    public function msg($type = null, $msg, $keterangan, $status)
    {
        if ($type == 200) {
            $array = array(
                'status' => $status,
                'type' => $type,
                'keterangan' => $keterangan . '',
                'msg' => $msg,
            );
            echo json_encode($array);
        } else if ($type == 203) {
            $array = array(
                // 'type' => $type,
                // 'keterangan' => $keterangan . '',
                'error-msg' => $msg,
                'status' => $status,
            );
            echo json_encode($array);
        } else {
            return "kosong";
        }
    }

    public function request()
    {
        $explodeUrl = explode('/', $this->url);
        $explodeUrl = array_slice($explodeUrl, 3);

        $result = "";

        // Explode Url digunakan untuk melihat controller dan fungsi yang digunakan
        //explode[0] : Controller
        //explode[1] : Object
        //explode[2] : Fungsi
        // POST - UPDATE / INSERT
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {

                if ($explodeUrl[0] == 'organization') {
                    $db = new Organization($this->db_connect());
                } else if ($explodeUrl[0] == 'organization_unit') {
                    $db = new OrganizationUnit($this->db_connect());
                } else if ($explodeUrl[0] == 'app') {
                    $db = new App($this->db_connect());

                } else if ($explodeUrl[0] == 'metric') {
                    $db = new Metric($this->db_connect());

                } else if ($explodeUrl[0] == 'users') {
                    $db = new User($this->db_connect());
                } else if ($explodeUrl[0] == 'user_organization') {
                    $db = new UserOrganization($this->db_connect());
                } else if ($explodeUrl[0] == 'user_organization_unit') {
                    $db = new UserOrganizationUnit($this->db_connect());
                } else if ($explodeUrl[0] == 'user_role') {
                    $db = new UserRole($this->db_connect());
                }
                if ($explodeUrl[1] == "insert") {
                    // echo "Helo";
                    $result = $db->insert($explodeUrl[0]);
                } else if ($explodeUrl[1] == "update") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->update($id, $tablename);
                }
            } else {
                die("Table Not Found");
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {
                if ($explodeUrl[0] == 'organization') {
                    $db = new Organization($this->db_connect());
                } else if ($explodeUrl[0] == 'organization_unit') {
                    $db = new OrganizationUnit($this->db_connect());
                } else if ($explodeUrl[0] == 'app') {
                    $db = new App($this->db_connect());

                } else if ($explodeUrl[0] == 'metric') {
                    $db = new Metric($this->db_connect());

                } else if ($explodeUrl[0] == 'users') {
                    $db = new User($this->db_connect());
                    if ($explodeUrl[1] == "find_by_email") {
                        $email = $explodeUrl[2];
                        $result = $db->findByEmail($email, $explodeUrl[0]);
                    } else if ($explodeUrl[1] == "find_by_username") {
                        $username = $explodeUrl[2];
                        $result = $db->findByUsername($username, $explodeUrl[0]);
                    }
                } else if ($explodeUrl[0] == 'user_organization') {
                    $db = new UserOrganization($this->db_connect());
                } else if ($explodeUrl[0] == 'user_organization_unit') {
                    $db = new UserOrganizationUnit($this->db_connect());
                } else if ($explodeUrl[0] == 'user_role') {
                    $db = new UserRole($this->db_connect());
                }
                if ($explodeUrl[1] == "get") {
                    $result = $db->get($explodeUrl[0]);
                } else if ($explodeUrl[1] == "find_id") {
                    $id = $explodeUrl[2];
                    $result = $db->findById($id, $explodeUrl[0]);
                } else if ($explodeUrl[1] == "delete") {
                    $id = $explodeUrl[2];
                    $result = $db->delete($id, $explodeUrl[0]);
                }
            } else {
                die("Table Not Found");
            }
        } else {
            echo json_encode("Now DB");
        }

        try {
            // echo json_encode($result);
            if ($result == 'Data Kosong') {
                $this->msg(200, $result, "berhasil", 0);
            } else {
                $this->msg(200, $result, "berhasil", 1);
            }

        } catch (\Throwable $th) {
            $this->msg(203, $th, "Terjadi Kesalahan");
            // echo json_encode("terjadi kesalahan");
        }
    }
}
