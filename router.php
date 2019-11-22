<?php
include "adodb/adodb.inc.php";
include "models/organization.php";
include "models/organization_role.php";
include "models/unit.php";
include "models/app.php";
include "models/page.php";
include "models/page_layout.php";
include "models/metric.php";
include "models/object.php";
include "models/user.php";
include "models/form.php";
include "models/config_form.php";
include "models/form_page.php";
include "models/user_role.php";
include "models/user_unit.php";
include "models/role.php";
if (file_exists('settings.php')) {
    include 'settings.php';
} else {
    define('db_username', 'pmo');
    define('db_password', 'pass4pmo');
    define('db_name1', "core");
    define('db_name2', "dbobject");
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

    public function core_connect()
    {
        $this->db = newADOConnection('pgsql');
        $this->db->connect(db_host, db_username, db_password, db_name1);
        // die($this->db);
        return $this->db;
    }

    public function object_connect()
    {
        $this->db = newADOConnection('pgsql');
        $this->db->connect(db_host, db_username, db_password, db_name2);
        // die($this->db);
        return $this->db;
    }

    public function get_table_db()
    {
        $tempDb = $this->core_connect();
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
                'data' => $msg,
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
                    $db = new Organization($this->core_connect());
                } else if ($explodeUrl[0] == 'organization_role') {
                    $db = new OrganizationRole($this->core_connect());
                } else if ($explodeUrl[0] == 'unit') {
                    $db = new Unit($this->core_connect());
                } else if ($explodeUrl[0] == 'app') {
                    $db = new App($this->core_connect());
                } else if ($explodeUrl[0] == 'page') {
                    $db = new Page($this->core_connect());
                } else if ($explodeUrl[0] == 'page_layout') {
                    $db = new PageLayout($this->core_connect());
                } else if ($explodeUrl[0] == 'form') {
                    $db = new Form($this->core_connect());
                } else if ($explodeUrl[0] == 'config_form_layout') {
                    $db = new ConfigForm($this->core_connect());
                } else if ($explodeUrl[0] == 'form_page') {
                    $db = new FormPage($this->core_connect());
                } else if ($explodeUrl[0] == 'metric') {
                    $db = new Metric($this->core_connect());

                } else if ($explodeUrl[0] == 'object') {
                    if ($explodeUrl[1] == "insert_object") {
                        $db = new Objects($this->core_connect());
                        $result = $db->insert($explodeUrl[0]);
                        $result = $db->create_table();
                    }
                } else if ($explodeUrl[0] == 'users') {
                    $db = new User($this->core_connect());
                } else if ($explodeUrl[0] == 'user_unit') {
                    $db = new UserUnit($this->core_connect());
                } else if ($explodeUrl[0] == 'role') {
                    $db = new Role($this->core_connect());
                } else if ($explodeUrl[0] == 'user_role') {
                    $db = new UserRole($this->core_connect());
                }
                if ($explodeUrl[1] == "insert") {
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
                    $db = new Organization($this->core_connect());
                } else if ($explodeUrl[0] == 'organization_role') {
                    $db = new OrganizationRole($this->core_connect());
                    if ($explodeUrl[1] == "get_by_organization_id") {
                        $organization_id = $explodeUrl[2];
                        $result = $db->findByOrgId($explodeUrl[0], $organization_id);
                    }
                } else if ($explodeUrl[0] == 'unit') {
                    $db = new Unit($this->core_connect());
                    if ($explodeUrl[1] == "get_by_parent") {
                        $parent_id = $explodeUrl[2];
                        $result = $db->getByParent($explodeUrl[0], $parent_id);
                    }
                } else if ($explodeUrl[0] == 'app') {
                    $db = new App($this->core_connect());
                } else if ($explodeUrl[0] == 'page') {
                    $db = new Page($this->core_connect());
                } else if ($explodeUrl[0] == 'page_layout') {
                    $db = new PageLayout($this->core_connect());
                } else if ($explodeUrl[0] == 'form') {
                    $db = new Form($this->core_connect());
                } else if ($explodeUrl[0] == 'config_form') {
                    $db = new ConfigForm($this->core_connect());
                } else if ($explodeUrl[0] == 'form_page') {
                    $db = new FormPage($this->core_connect());
                    if ($explodeUrl[1] == "get_by_page_id") {
                        $page_id = $explodeUrl[2];
                        $result = $db->findByOrgId($explodeUrl[0], $page_id);
                    }
                } else if ($explodeUrl[0] == 'metric') {
                    $db = new Metric($this->core_connect());
                } else if ($explodeUrl[0] == 'object') {
                    $db = new Objects($this->core_connect());
                } else if ($explodeUrl[0] == 'users') {
                    $db = new User($this->core_connect());
                    if ($explodeUrl[1] == "find_by_email") {
                        $email = $explodeUrl[2];
                        $result = $db->findByEmail($email, $explodeUrl[0]);
                    } else if ($explodeUrl[1] == "find_by_username") {
                        $username = $explodeUrl[2];
                        $result = $db->findByUsername($username, $explodeUrl[0]);
                    }
                } else if ($explodeUrl[0] == 'user_unit') {
                    $db = new UserUnit($this->core_connect());
                } else if ($explodeUrl[0] == 'role') {
                    $db = new Role($this->core_connect());

                } else if ($explodeUrl[0] == 'user_role') {
                    $db = new UserRole($this->core_connect());
                    if ($explodeUrl[1] == "get_by_user_id") {
                        $user_id = $explodeUrl[2];
                        $result = $db->findByUserId($user_id, $explodeUrl[0]);
                    }
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
            if ($result == [] || $result == 'Data Kosong') {
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
