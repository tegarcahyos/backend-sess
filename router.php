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
// include "modules/user_login.php";
include "models/form.php";
include "models/config_form.php";
include "models/form_page.php";
include "models/user_role.php";
include "models/user_unit.php";
include "models/role.php";
include "models/group_chat.php";
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

    public function check_token($token)
    {
        $tempDb = $this->core_connect();
        $query = "SELECT * FROM users WHERE token = '$token'";
        // die($query);
        $result = $tempDb->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {

        } else {
            extract($row);
            $expireAt = $row['expire_at'];
        }
        if (strtotime('now') < $expireAt) {
            return 'true';
        } else {
            return 'false';
            // return http_response_code(101);
        }
    }

    // REQUEST
    public function request()
    {
        $getHeader = getallheaders();

        foreach ($getHeader as $key => $value) {
            if ($key == 'Authorization') {
                $token = $value;
            }
        }

        // $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

        //     // --- LOGIN ---
        //     $r->post('/nowdb-api-telkom/login', 'Login/authenticate');
        //     // --- CHECK TOKEN ---
        //     // if (!empty($token)) {
        //     //     $passed = $this->check_token($token);
        //     //     if ($passed == 'true') {

        //     // --- USER ---
        //     $r->get('/nowdb-api-telkom/users/get', 'User/get');
        //     $r->get('/nowdb-api-telkom/users/find_id/{id}', 'User/findById');
        //     $r->get('/nowdb-api-telkom/users/delete/{id}', 'User/delete');
        //     $r->post('/nowdb-api-telkom/users/insert', 'User/insert');
        //     $r->post('/nowdb-api-telkom/users/update/{id}', 'User/update');

        //     // --- APP ---
        //     $r->get('/nowdb-api-telkom/app/get', 'App/get');
        //     $r->get('/nowdb-api-telkom/app/find_id/{id}', 'App/findById');
        //     $r->get('/nowdb-api-telkom/app/delete/{id}', 'App/delete');
        //     $r->post('/nowdb-api-telkom/app/insert', 'App/insert');
        //     $r->post('/nowdb-api-telkom/app/addPage/{id}', 'App/addPage');
        //     $r->post('/nowdb-api-telkom/app/update/{id}', 'App/update');

        //     // --- CONFIG --
        //     // // --- ALIGNMENT ---
        //     $r->get('/nowdb-api-telkom/config_alignment/get', 'ConfigAlignment/get');
        //     $r->get('/nowdb-api-telkom/config_alignment/find_id/{id}', 'ConfigAlignment/findById');
        //     $r->get('/nowdb-api-telkom/config_alignment/delete/{id}', 'ConfigAlignment/delete');
        //     $r->post('/nowdb-api-telkom/config_alignment/insert_alignment', 'ConfigAlignment/insertAlignData');
        //     $r->post('/nowdb-api-telkom/config_alignment/insert_data_alignment/{id}', 'ConfigAlignment/insertData');
        //     $r->post('/nowdb-api-telkom/config_alignment/update/{id}', 'ConfigAlignment/update');

        //     // // --- FORM ---
        //     $r->get('/nowdb-api-telkom/config_form_layout/get', 'ConfigForm/get');
        //     $r->get('/nowdb-api-telkom/config_form_layout/find_id/{id}', 'ConfigForm/findById');
        //     $r->get('/nowdb-api-telkom/config_form_layout/delete/{id}', 'ConfigForm/delete');
        //     $r->post('/nowdb-api-telkom/config_form_layout/insert_form_data', 'ConfigForm/insertFormData');
        //     $r->post('/nowdb-api-telkom/config_form_layout/insert_form_layout/{id}', 'ConfigForm/insertLayout');
        //     $r->post('/nowdb-api-telkom/config_form_layout/update/{id}', 'ConfigForm/update');

        //     // // --- GANN ---
        //     $r->get('/nowdb-api-telkom/config_gann/get', 'ConfigGann/get');
        //     $r->get('/nowdb-api-telkom/config_gann/find_id/{id}', 'ConfigGann/findById');
        //     $r->get('/nowdb-api-telkom/config_gann/delete/{id}', 'ConfigGann/delete');
        //     $r->post('/nowdb-api-telkom/config_gann/insert_gann', 'ConfigGann/insertGann');
        //     $r->post('/nowdb-api-telkom/config_gann/insert_gann_data/{id}', 'ConfigGann/insertGannData');
        //     $r->post('/nowdb-api-telkom/config_gann/update/{id}', 'ConfigGann/update');

        //     // // --- LIST ---
        //     $r->get('/nowdb-api-telkom/config_list/get', 'ConfigList/get');
        //     $r->get('/nowdb-api-telkom/config_list/find_id/{id}', 'ConfigList/findById');
        //     $r->get('/nowdb-api-telkom/config_list/delete/{id}', 'ConfigList/delete');
        //     $r->post('/nowdb-api-telkom/config_list/insert', 'ConfigList/insert');
        //     $r->post('/nowdb-api-telkom/config_list/update/{id}', 'ConfigList/update');

        //     // // --- PAGE ---
        //     $r->get('/nowdb-api-telkom/config_page_layout/get', 'ConfigPage/get');
        //     $r->get('/nowdb-api-telkom/config_page_layout/find_id/{id}', 'ConfigPage/findById');
        //     $r->get('/nowdb-api-telkom/config_page_layout/delete/{id}', 'ConfigPage/delete');
        //     $r->post('/nowdb-api-telkom/config_page_layout/insert_page_data', 'ConfigGann/insertPageData');
        //     $r->post('/nowdb-api-telkom/config_page_layout/insert_page_layout/{id}', 'ConfigGann/insertLayout');
        //     $r->post('/nowdb-api-telkom/config_page_layout/update/{id}', 'ConfigPage/update');

        //     // // --- TABLE ---
        //     $r->get('/nowdb-api-telkom/config_table/get', 'ConfigTable/get');
        //     $r->get('/nowdb-api-telkom/config_table/find_id/{id}', 'ConfigTable/findById');
        //     $r->get('/nowdb-api-telkom/config_table/delete/{id}', 'ConfigTable/delete');
        //     $r->post('/nowdb-api-telkom/config_table/insert', 'ConfigTable/insert');
        //     $r->post('/nowdb-api-telkom/config_table/update/{id}', 'ConfigTable/update');

        //     // FORM PAGE
        //     $r->get('/nowdb-api-telkom/form_page/get', 'FormPage/get');
        //     $r->get('/nowdb-api-telkom/form_page/find_id/{id}', 'FormPage/findById');
        //     $r->get('/nowdb-api-telkom/form_page/delete/{id}', 'FormPage/delete');
        //     $r->post('/nowdb-api-telkom/form_page/insert', 'FormPage/insert');
        //     $r->post('/nowdb-api-telkom/form_page/update/{id}', 'FormPage/update');

        //     // FORM
        //     $r->get('/nowdb-api-telkom/form/get', 'Form/get');
        //     $r->get('/nowdb-api-telkom/form/find_id/{id}', 'Form/findById');
        //     $r->get('/nowdb-api-telkom/form/delete/{id}', 'Form/delete');
        //     $r->post('/nowdb-api-telkom/form/insert', 'Form/insert');
        //     $r->post('/nowdb-api-telkom/form/update/{id}', 'Form/update');

        //     // METRIC
        //     $r->get('/nowdb-api-telkom/metric/get', 'Metric/get');
        //     $r->get('/nowdb-api-telkom/metric/find_id/{id}', 'Metric/findById');
        //     $r->get('/nowdb-api-telkom/metric/delete/{id}', 'Metric/delete');
        //     $r->post('/nowdb-api-telkom/metric/insert', 'Metric/insert');
        //     $r->post('/nowdb-api-telkom/metric/update/{id}', 'Metric/update');

        //     // OBJECT
        //     $r->get('/nowdb-api-telkom/object/get', 'Object/get');
        //     $r->get('/nowdb-api-telkom/object/find_id/{id}', 'Object/findById');
        //     $r->get('/nowdb-api-telkom/object/delete/{id}', 'Object/delete');
        //     $r->post('/nowdb-api-telkom/object/insert_object', 'Object/insert');
        //     $r->post('/nowdb-api-telkom/object/update_object/{id}', 'Object/updateObject');

        //     // ROLE
        //     $r->get('/nowdb-api-telkom/role/get', 'Role/get');
        //     $r->get('/nowdb-api-telkom/role/find_id/{id}', 'Role/findById');
        //     $r->get('/nowdb-api-telkom/role/delete/{id}', 'Role/delete');
        //     $r->post('/nowdb-api-telkom/role/insert', 'Role/insert');
        //     $r->post('/nowdb-api-telkom/role/update/{id}', 'Role/update');

        //     // ORGANIZATION
        //     $r->get('/nowdb-api-telkom/organization/get', 'Organization/get');
        //     $r->get('/nowdb-api-telkom/organization/find_id/{id}', 'Organization/findById');
        //     $r->get('/nowdb-api-telkom/organization/delete/{id}', 'Organization/delete');
        //     $r->post('/nowdb-api-telkom/organization/insert', 'Organization/insert');
        //     $r->post('/nowdb-api-telkom/organization/update/{id}', 'Organization/update');

        //     // UNIT
        //     $r->get('/nowdb-api-telkom/unit/get', 'Unit/get');
        //     $r->get('/nowdb-api-telkom/unit/get_leaf_unit/', 'Unit/getLeafUnit');
        //     $r->get('/nowdb-api-telkom/unit/find_id/{id}', 'Unit/findById');
        //     $r->get('/nowdb-api-telkom/unit/get_by_parent/{parent_id}', 'Unit/getByParent');
        //     $r->get('/nowdb-api-telkom/unit/get_by_organization/{org_id}', 'Unit/getUnitByOrgId');
        //     $r->get('/nowdb-api-telkom/unit/delete/{id}', 'Unit/delete');
        //     $r->post('/nowdb-api-telkom/unit/insert', 'Unit/insert');
        //     $r->post('/nowdb-api-telkom/unit/update/{id}', 'Unit/update');

        //     //
        //     //     } else {
        //     //         die('token expired');
        //     //     }
        //     // }

        // });

        // // Fetch method and URI from somewhere
        // $httpMethod = $_SERVER['REQUEST_METHOD'];
        // $uri = $_SERVER['REQUEST_URI'];
        // // Strip query string (?foo=bar) and decode URI
        // if (false !== $pos = strpos($uri, '?')) {
        //     $uri = substr($uri, 0, $pos);
        // }

        // $uri = rawurldecode($uri);
        // $explodeUri = explode("/", $uri);

        // $connection = $this->db_connect();
        // $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        // switch ($routeInfo[0]) {
        //     case FastRoute\Dispatcher::NOT_FOUND:
        //         // ... 404 Not Found
        //         break;
        //     case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        //         $allowedMethods = $routeInfo[1];
        //         // ... 405 Method Not Allowed
        //         break;
        //     case FastRoute\Dispatcher::FOUND:
        //         $handler = $routeInfo[1];
        //         $vars = $routeInfo[2];

        //         list($class, $method) = explode("/", $handler, 2);

        //         if ($explodeUri[2] == 'login') {
        //             $result = call_user_func_array(array(new $class($connection), $method), array('users'));
        //         } else if ($explodeUri[3] == "find_id" || $explodeUri[3] == "update") {
        //             $result = call_user_func_array(array(new $class($connection), $method), array($vars['id'], $explodeUri[2]));
        //         } else if ($explodeUri[3] == "get" || $explodeUri[3] == "insert") {
        //             $result = call_user_func_array(array(new $class($connection), $method), array($explodeUri[2]));
        //         }
        //         break;
        // }

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
            if ($explodeUrl[0] == 'login') {
                $db = new Login($this->core_connect());
                $result = $db->authenticate("users");
                // CORE DATA
            } else if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {

                // $passed = $this->check_token($data->token);
                // if ($passed == 'true') {
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
                } else if ($explodeUrl[0] == 'page_data') {
                    $db = new PageData($this->core_connect());
                } else if ($explodeUrl[0] == 'users') {
                    $db = new User($this->core_connect());
                    if ($explodeUrl[1] == 'upload_photo') {
                        $id = $explodeUrl[2];
                        $result = $db->updatePhotoProfile($id, $explodeUrl[0]);
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
                }
                // Normal POST Action
                if ($explodeUrl[1] == "insert") {
                    $result = $db->insert($explodeUrl[0]);
                } else if ($explodeUrl[1] == "update") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db->update($id, $tablename);
                }

                //
                $db_object = new ObjectData($this->core_connect());
                if ($explodeUrl[1] == "insert_object") {
                    $result = $db_object->insert($explodeUrl[0]);
                } else if ($explodeUrl[1] == "update_by_id") {
                    $tablename = $explodeUrl[0];
                    $id = $explodeUrl[2];
                    $result = $db_object->update_id($id, $tablename);
                } else if ($explodeUrl[1] == "update_where") {
                    $tablename = $explodeUrl[0];
                    $attr = $explodeUrl[2];
                    $value = $explodeUrl[3];
                    $result = $db_object->update_where($attr, $value, $tablename);
                }
                // }
            }
            // OBJECT DATA
            // if (in_array($explodeUrl[0], array_column($this->get_table_db(), 'tablename'))) {
            //     $data = json_decode(file_get_contents("php://input"));

            //     $passed = $this->check_token($data->token);
            //     if ($passed == 'true') {
            //         $db = new ObjectData($this->core_connect());
            //         if ($explodeUrl[1] == "insert_object") {
            //             $result = $db->insert($explodeUrl[0]);
            //             // } else if ($explodeUrl[1] == "update_attr_value") {
            //             //     $tablename = $explodeUrl[1];
            //             //     $attr = $explodeUrl[3];
            //             //     $value = $explodeUrl[4];
            //             //     $result = $db->update_all($attr, $value, $tablename);
            //         } else if ($explodeUrl[1] == "update_by_id") {
            //             $tablename = $explodeUrl[0];
            //             $id = $explodeUrl[2];
            //             $result = $db->update_id($id, $tablename);
            //         } else if ($explodeUrl[1] == "update_where") {
            //             $tablename = $explodeUrl[0];
            //             $attr = $explodeUrl[2];
            //             $value = $explodeUrl[3];
            //             $result = $db->update_where($attr, $value, $tablename);
            //         }
            //     }
            // }

            // }
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            // if (!empty($token)) {
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
            } else if ($explodeUrl[0] == 'page_data') {
                $db = new PageData($this->core_connect());
                if ($explodeUrl[1] == 'find_by_page_id') {
                    $page_id = $explodeUrl[2];
                    $result = $db->findByPageId($page_id, $explodeUrl[0]);
                }
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

            // }

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
