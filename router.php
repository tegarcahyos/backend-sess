<?php
include "adodb/adodb.inc.php";
include "models/organization.php";
include "models/organization_role.php";
include "models/unit.php";
include "models/app.php";
include "models/page_data.php";
include "models/button_action.php";
include "models/config_page.php";
include "models/config_table.php";
include "models/config_list.php";
include "models/config_gann.php";
include "models/config_alignment.php";
include "models/metric.php";
include "models/object.php";
include "models/object_data.php";
include "models/user.php";
include "models/form.php";
include "models/config_form.php";
include "models/form_page.php";
include "models/user_role.php";
include "models/user_unit.php";
include "models/role.php";
include "login.php";
if (file_exists('settings.php')) {
    include 'settings.php';
} else {
    define('db_username', 'pmo');
    define('db_password', 'pass4pmo');
    define('db_name1', "core");
    define("db_host", "localhost");
    define("db_port", "5432");
}

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
    // CONNECT TO DB
    public function core_connect()
    {
        $this->db = newADOConnection('pgsql');
        $this->db->connect(db_host, db_username, db_password, db_name1);
        // die($this->db);
        return $this->db;
    }
    // CHECK IF TABLENAME EXISTS FOR OBJECT QUERY
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
    // MESSAGES
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
                'type' => $type,
                'keterangan' => $keterangan . '',
                'error-msg' => $msg,
                'status' => $status,
            );
            echo json_encode($array);
        } else {
            return "kosong";
        }
    }

    public function check_token($id){
        $tempDb = $this->core_connect();
        $query = "SELECT * FROM users WHERE id = $id";
        $tempDb->execute($query);
        $row = $result->fetchRow();
                if (is_bool($row)) {
                    
                } else {
                    extract($row);
                    $expireAt = $row['expireAt'];
                }
        // if($expireAt )
    }

    // REQUEST
    public function request()
    {
        header("Access-Control-Allow-Origin: * ");
        $explodeUrl = explode('/', $this->url);
        $explodeUrl = array_slice($explodeUrl, 3);
        $result = "";

        // Explode Url digunakan untuk melihat controller dan fungsi yang digunakan
        //explode[0] : Controller
        //explode[1] : Object
        //explode[2] : Fungsi
        // POST - UPDATE / INSERT
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($explodeUrl[0] == 'organization') {
                $db = new Organization($this->core_connect());
            } else if ($explodeUrl[0] == 'organization_role') {
                $db = new OrganizationRole($this->core_connect());
            } else if ($explodeUrl[0] == 'unit') {
                $db = new Unit($this->core_connect());
            } else if ($explodeUrl[0] == 'app') {
                $db = new App($this->core_connect());
                if ($explodeUrl[1] == "add_page") {
                    $id = $explodeUrl[2];
                    $result = $db->addPage($id, $explodeUrl[0]);
                }
            } else if ($explodeUrl[0] == 'page') {
                $db = new Page($this->core_connect());
            } else if ($explodeUrl[0] == 'form') {
                $db = new Form($this->core_connect());
            } else if ($explodeUrl[0] == 'metric') {
                $db = new Metric($this->core_connect());

            } else if ($explodeUrl[0] == 'users') {
                $db = new User($this->core_connect());
                if ($explodeUrl[1] == "login") {
                    $db = new Login($this->core_connect());
                    $result = $db->authenticate($explodeUrl[0]);
                }
            } else if ($explodeUrl[0] == 'user_unit') {
                $db = new UserUnit($this->core_connect());
            } else if ($explodeUrl[0] == 'role') {
                $db = new Role($this->core_connect());
            } else if ($explodeUrl[0] == 'user_role') {
                $db = new UserRole($this->core_connect());
            } else if ($explodeUrl[0] == 'config_table') {
                $db = new ConfigTable($this->core_connect());
            } else if ($explodeUrl[0] == 'config_list') {
                $db = new ConfigList($this->core_connect());
            } else if ($explodeUrl[0] == 'config_page_layout') {
                $db = new ConfigPage($this->core_connect());
                if ($explodeUrl[1] == "insert_page_data") {
                    $result = $db->insertPageData($explodeUrl[0]);
                } else if ($explodeUrl[1] == "insert_page_layout") {
                    $id_page = $explodeUrl[2];
                    $result = $db->insertLayout($explodeUrl[0], $id_page);
                }
            } else if ($explodeUrl[0] == 'config_form_layout') {
                $db = new ConfigForm($this->core_connect());
                if ($explodeUrl[1] == "insert_form_data") {
                    $result = $db->insertFormData($explodeUrl[0]);
                } else if ($explodeUrl[1] == "insert_form_layout") {
                    $id_form = $explodeUrl[2];
                    $result = $db->insertLayout($explodeUrl[0], $id_form);
                }
            } else if ($explodeUrl[0] == 'config_gann') {
                $db = new ConfigGann($this->core_connect());
                if ($explodeUrl[1] == "insert_gann") {
                    $result = $db->insertGann($explodeUrl[0]);
                } else if ($explodeUrl[1] == "insert_gann_data") {
                    $id = $explodeUrl[2];
                    $result = $db->insertGannData($explodeUrl[0], $id);
                }
            } else if ($explodeUrl[0] == 'form_page') {
                $db = new FormPage($this->core_connect());
            } else if ($explodeUrl[0] == 'config_alignment') {
                $db = new ConfigAlignment($this->core_connect());
                if ($explodeUrl[1] == "insert_alignment") {
                    $result = $db->insertAlignData($explodeUrl[0]);
                } else if ($explodeUrl[1] == "insert_data_alignment") {
                    $id = $explodeUrl[2];
                    $result = $db->insertData($explodeUrl[0], $id);
                }
            } else if ($explodeUrl[0] == 'button_action') {
                $db = new ButtonAction($this->core_connect());
                if ($explodeUrl[1] == "insert_button") {
                    $result = $db->insertButton($explodeUrl[0]);
                } else if ($explodeUrl[1] == "insert_button_action") {
                    $id = $explodeUrl[2];
                    $result = $db->insertAction($explodeUrl[0], $id);
                }
            } else if ($explodeUrl[0] == 'object') {
                $db = new Objects($this->core_connect());
                if ($explodeUrl[1] == "insert_object") {
                    $result = $db->insert($explodeUrl[0]);
                    $result = $db->create_table();
                } else if ($explodeUrl[1] == "update_object") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->updateObject($id, $tablename);
                }
            } else if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {
                $db = new ObjectData($this->core_connect());
                if ($explodeUrl[1] == "insert_object") {
                    $result = $db->insert($explodeUrl[0]);
                    // } else if ($explodeUrl[1] == "update_attr_value") {
                    //     $tablename = $explodeUrl[1];
                    //     $attr = $explodeUrl[3];
                    //     $value = $explodeUrl[4];
                    //     $result = $db->update_all($attr, $value, $tablename);
                } else if ($explodeUrl[1] == "update_by_id") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->update_id($id, $tablename);
                } else if ($explodeUrl[1] == "update_where") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db->update_where($attr, $value, $tablename);
                }
            }

            // Normal POST Action
            if ($explodeUrl[1] == "insert") {
                $result = $db->insert($explodeUrl[0]);
            } else if ($explodeUrl[1] == "update") {
                $tablename = $explodeUrl[0];
                $id = $explodeUrl[2];
                $result = $db->update($id, $tablename);
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

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
                } else if ($explodeUrl[1] == "get_leaf_unit") {
                    $result = $db->getLeafUnit($explodeUrl[0]);
                } else if ($explodeUrl[1] == "get_by_organization") {
                    $org_id = $explodeUrl[2];
                    $result = $db->getUnitByOrgId($explodeUrl[0], $org_id);
                }
            } else if ($explodeUrl[0] == 'app') {
                $db = new App($this->core_connect());
            } else if ($explodeUrl[0] == 'page') {
                $db = new Page($this->core_connect());
            } else if ($explodeUrl[0] == 'page_layout') {
                $db = new PageLayout($this->core_connect());
            } else if ($explodeUrl[0] == 'config_table') {
                $db = new ConfigTable($this->core_connect());
                if ($explodeUrl[1] == 'get_layout') {
                    $result = $db->get_layout($explodeUrl[2]);
                }
            } else if ($explodeUrl[0] == 'config_list') {
                $db = new ConfigList($this->core_connect());
                if ($explodeUrl[1] == 'get_layout') {
                    $result = $db->get_layout($explodeUrl[2]);
                }
            } else if ($explodeUrl[0] == 'config_form_layout') {
                $db = new ConfigForm($this->core_connect());
            } else if ($explodeUrl[0] == 'config_gann') {
                $db = new ConfigGann($this->core_connect());
            } else if ($explodeUrl[0] == 'config_alignment') {
                $db = new ConfigAlignment($this->core_connect());
            } else if ($explodeUrl[0] == 'metric') {
                $db = new Metric($this->core_connect());
            } else if ($explodeUrl[0] == 'object') {
                $db = new Objects($this->core_connect());
                if ($explodeUrl[1] == "delete_object") {
                    $id = $explodeUrl[2];
                    $result = $db->delete($id, $explodeUrl[0]);
                }
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
                if ($explodeUrl[1] == "get_by_user") {
                    $user_id = $explodeUrl[2];
                    $result = $db->getByUserId($explodeUrl[0], $user_id);
                } else if ($explodeUrl[1] == "get_by_parent_unit_id") {
                    $parent_id = $explodeUrl[2];
                    $result = $db->getByParentUnitId($explodeUrl[0], $parent_id);
                }
            } else if ($explodeUrl[0] == 'role') {
                $db = new Role($this->core_connect());
            } else if ($explodeUrl[0] == 'config_page_layout') {
                $db = new ConfigPage($this->core_connect());
            } else if ($explodeUrl[0] == 'user_role') {
                $db = new UserRole($this->core_connect());
                if ($explodeUrl[1] == "get_by_user_id") {
                    $user_id = $explodeUrl[2];
                    $result = $db->findByUserId($user_id, $explodeUrl[0]);
                }
            } else if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {
                $db = new ObjectData($this->core_connect());
                if ($explodeUrl[1] == "select_all_get") {
                    $tablename = $explodeUrl[0];
                    $result = $db->select_all_get($tablename);
                } else if ($explodeUrl[1] == "select_id_get") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->select_id_get($id, $tablename);
                } else if ($explodeUrl[1] == "select_where_get") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db->select_where_get($attr, $value, $tablename);
                } else if ($explodeUrl[1] == "select_or_where_get") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db->select_or_where_get($attr, $value, $tablename);
                } else if ($explodeUrl[1] == "select_where_like_get") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db->select_where_like_get($attr, $value, $tablename);
                } else if ($explodeUrl[1] == "delete_all_get") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->delete_all_get($id, $tablename);
                } else if ($explodeUrl[1] == "delete_where_get") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db->delete_where_get($attr, $value, $tablename);
                } else if ($explodeUrl[1] == 'delete_value_on_attribute') {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $attr = $explodeUrl[3];
                    $result = $db->delete_values_on_attribute($id, $attr, $tablename);
                } else if ($explodeUrl[1] == 'delete_attr_by_id') {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $attr = $explodeUrl[3];
                    $result = $db->delete_attr_by_id($id, $attr, $tablename);
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

        }
        try {
            if ($result == [] || $result == 'Data Kosong') {
                $this->msg(200, $result, "berhasil", 0);
            } else {
                $this->msg(200, $result, "berhasil", 1);
            }

        } catch (\Throwable $th) {
            $this->msg(203, $th, "Terjadi Kesalahan");
        }
    }
}
