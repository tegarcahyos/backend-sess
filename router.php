<?php
include "adodb/adodb.inc.php";
include "vendor/autoload.php";
include "models/organization.php";
include "models/unit.php";
include "models/config_gann.php";
include "models/config_alignment.php";
include "models/metric.php";
include "models/user.php";
include "models/role.php";
include "models/attachment.php";
include "models/group_chat.php";
include "models/group_member.php";
include "models/group_message.php";
include "models/user_login.php";
include "models/matrix.php";
include "login.php";
if (file_exists('settings.php')) {
    include 'settings.php';
} else {
    define('db_username', 'pmo');
    define('db_password', 'pass4pmo');
    define('db_name1', "core");
    define("db_host", "10.62.161.10");
    define("db_port", "5432");
}

class Router
{
    public $url;
    public $db;
    public $db_config;
    public function core_connect()
    {

        $this->db = newADOConnection('pgsql');
        $this->db->connect(db_host, db_username, db_password, db_name1);
        return $this->db;
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
                'error-msg' => $msg,
                'keterangan' => $keterangan . '',
                'status' => $status,
            );
            echo json_encode($array);
        } else if ($type == 204) {
            $array = array(
                'type' => $type,
                'error-msg' => $msg,
                'keterangan' => $keterangan . '',
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

        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

            $getHeader = getallheaders();
            $token = "";
            foreach ($getHeader as $key => $value) {
                if ($key == 'Authorization') {
                    $token = $value;
                }
            }

            // --- LOGIN ---
            $r->post('/api/index.php/login', 'Login/authenticate');
            $r->post('/api/index.php/login_chat', 'LoginChat/authenticate');
            // --- CHECK TOKEN ---
            // die($token);
            // if (!empty($token)) {
            // $passed = $this->check_token($token);
            // if ($passed == 'true') {

            // --- USER ---
            $r->get('/api/index.php/users/get', 'User/get');
            $r->get('/api/index.php/users/find_id/{id}', 'User/findById');
            $r->get('/api/index.php/users/delete/{id}', 'User/delete');
            $r->post('/api/index.php/users/insert', 'User/insert');
            $r->post('/api/index.php/users/update/{id}', 'User/update');

            // SI
            $r->get('/api/index.php/strategic_initiative/get', 'StraIn/get');
            $r->get('/api/index.php/strategic_initiative/find_id/{id}', 'StraIn/findById');
            $r->get('/api/index.php/strategic_initiative/delete/{id}', 'StraIn/delete');
            $r->post('/api/index.php/strategic_initiative/insert', 'StraIn/insert');
            $r->post('/api/index.php/strategic_initiative/update/{id}', 'StraIn/update');

            // // --- CONFIG ALIGNMENT ---
            $r->get('/api/index.php/config_alignment/get', 'ConfigAlignment/get');
            $r->get('/api/index.php/config_alignment/find_id/{id}', 'ConfigAlignment/findById');
            $r->get('/api/index.php/config_alignment/delete/{id}', 'ConfigAlignment/delete');
            $r->post('/api/index.php/config_alignment/insert_alignment', 'ConfigAlignment/insertAlignData');
            $r->post('/api/index.php/config_alignment/insert_data_alignment/{id}', 'ConfigAlignment/insertData');
            $r->post('/api/index.php/config_alignment/update/{id}', 'ConfigAlignment/update');

            // // --- CONFIG GANN ---
            $r->get('/api/index.php/config_gann/get', 'ConfigGann/get');
            $r->get('/api/index.php/config_gann/find_id/{id}', 'ConfigGann/findById');
            $r->get('/api/index.php/config_gann/delete/{id}', 'ConfigGann/delete');
            $r->post('/api/index.php/config_gann/insert_gann', 'ConfigGann/insertGann');
            $r->post('/api/index.php/config_gann/insert_gann_data/{id}', 'ConfigGann/insertGannData');
            $r->post('/api/index.php/config_gann/update/{id}', 'ConfigGann/update');

            // METRIC
            $r->get('/api/index.php/metric/get', 'Metric/get');
            $r->get('/api/index.php/metric/find_id/{id}', 'Metric/findById');
            $r->get('/api/index.php/metric/delete/{id}', 'Metric/delete');
            $r->post('/api/index.php/metric/insert', 'Metric/insert');
            $r->post('/api/index.php/metric/update/{id}', 'Metric/update');

            // ROLE
            $r->get('/api/index.php/role/get', 'Role/get');
            $r->get('/api/index.php/role/find_id/{id}', 'Role/findById');
            $r->get('/api/index.php/role/delete/{id}', 'Role/delete');
            $r->post('/api/index.php/role/insert', 'Role/insert');
            $r->post('/api/index.php/role/update/{id}', 'Role/update');

            // ORGANIZATION
            $r->get('/api/index.php/organization/get', 'Organization/get');
            $r->get('/api/index.php/organization/find_id/{id}', 'Organization/findById');
            $r->get('/api/index.php/organization/delete/{id}', 'Organization/delete');
            $r->post('/api/index.php/organization/insert', 'Organization/insert');
            $r->post('/api/index.php/organization/update/{id}', 'Organization/update');

            // UNIT
            $r->get('/api/index.php/unit/get', 'Unit/get');
            $r->get('/api/index.php/unit/get_leaf_unit', 'Unit/getLeafUnit');
            $r->get('/api/index.php/unit/find_id/{id}', 'Unit/findById');
            $r->get('/api/index.php/unit/get_by_parent/{parent_id}', 'Unit/getByParent');
            $r->get('/api/index.php/unit/get_by_organization/{org_id}', 'Unit/findByOrgId');
            $r->get('/api/index.php/unit/delete/{id}', 'Unit/delete');
            $r->post('/api/index.php/unit/insert', 'Unit/insert');
            $r->post('/api/index.php/unit/update/{id}', 'Unit/update');

            // OBJECT DATA
            $r->get('/api/index.php/{tablename}/select_all_get', 'ObjectData/select_all_get');
            $r->get('/api/index.php/{tablename}/select_id_get/{id}', 'ObjectData/select_id_get');
            $r->get('/api/index.php/{tablename}/select_where_get/{attr}/{val}', 'ObjectData/select_where_get');
            $r->get('/api/index.php/{tablename}/delete_all_get/{id}', 'ObjectData/delete_all_get');
            $r->post('/api/index.php/{tablename}/insert_object', 'ObjectData/insert');
            $r->post('/api/index.php/{tablename}/update_id/{id}', 'ObjectData/update_id');

            // MATRIX
            $r->get('/api/index.php/matrix/get', 'Matrix/get');
            $r->get('/api/index.php/matrix/find_id/{id}', 'Matrix/findById');
            $r->get('/api/index.php/matrix/select_where_get/{attr}/{val}', 'Matrix/getByValues');
            $r->get('/api/index.php/matrix/delete/{id}', 'Matrix/delete');
            $r->post('/api/index.php/matrix/insert', 'Matrix/insert');
            $r->post('/api/index.php/matrix/update/{id}', 'Matrix/update');

            //GROUP CHAT
            $r->get('/api/index.php/group_chat/select_group_chat/{id}', 'GroupChat/findById');
            $r->get('/api/index.php/group_chat/select_all_group_chat', 'GroupChat/get');
            $r->get('/api/index.php/group_chat/delete/{id}', 'GroupChat/delete');
            $r->post('/api/index.php/group_chat/insert_group_chat', 'GroupChat/insert');
            $r->post('/api/index.php/group_chat/update/{id}', 'GroupChat/update');
            $r->get('/api/index.php/group_chat/group_member/join_chat/{user_id}/{group_id}', 'GroupChat/join_chat');
            $r->get('/api/index.php/group_chat/group_member/join_group_chat/{user_id}/{group_id}', 'GroupChat/join_group_chat');

            //USER LOGIN
            $r->post('/api/index.php/user_login/insert_user_device', 'UserLogin/insert');
            $r->get('/api/index.php/user_login/select_all_device', 'UserLogin/get');
            $r->get('/api/index.php/user_login/select_device/{device_id}', 'UserLogin/findByDeviceId');
            $r->get('/api/index.php/user_login/delete_device/{device_id}', 'UserLogin/delete');

            //ATTACHMENT
            $r->post('/api/index.php/attachment/insert_attachment', 'Attachment/insert');
            $r->get('/api/index.php/attachment/select_all_attachment', 'Attachment/get');
            $r->get('/api/index.php/attachment/select_attachment', 'Attachment/select_id');
            $r->get('/api/index.php/attachment/select_group_id/{group_id}', 'Attachment/group_id');
            $r->get('/api/index.php/attachment/select_group_message_id/{message_id}', 'Attachment/select_group_message_id');

            //GROUP MEMBER
            $r->post('/api/index.php/group_member/insert_group_member', 'GroupMember/insert');
            $r->get('/api/index.php/group_member/select_group_member/{id}', 'GroupMember/select_id');
            $r->get('/api/index.php/group_member/select_all_member', 'GroupMember/get');
            $r->get('/api/index.php/group_member/select_group_id/{group_id}', 'GroupMember/select_group_id');
            $r->get('/api/index.php/group_member/select_user_id/{user_id}', 'GroupMember/select_user_id');
            $r->get('/api/index.php/group_member/select_push_id/{push_id}', 'GroupMember/select_push_id');
            $r->post('/api/index.php/group_member/update/{id}', 'GroupMember/update');

            //GROUP MESSAGE
            $r->post('/api/index.php/group_message/insert_message', 'GroupMessage/insert');
            $r->get('/api/index.php/group_message/select_all_message', 'GroupMessage/get');
            $r->get('/api/index.php/group_message/select_message/{id}', 'GroupMessage/select_id');
            $r->get('/api/index.php/group_message/select_group_id/{group_id}', 'GroupMessage/select_group_id');
            $r->get('/api/index.php/group_message/select_user_id/{user_id}', 'GroupMessage/select_user_id');
            $r->post('/api/index.php/group_message/update/{id}', 'GroupMessage/update');

            // } else {
            //     die('token expired');
            // }
            // } else {
            //     die('token kosong');
            // }

        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        // die($uri);
        $uri = rawurldecode($uri);
        $explodeUri = explode("/", $uri);
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $connection = $this->core_connect();

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                return header("HTTP/1.0 404 Not Found");
                // return $this->msg(404, "", "Route URL Not Found", 404);
                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // return header("HTTP/1.0 405 Method Not Allowed");
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                list($class, $method) = explode("/", $handler, 2);

                if ($explodeUri[3] == 'login') {
                    $result = call_user_func_array(array(new $class($connection), $method), array('users'));

                } else if ($explodeUri[4] == "select_group_message_id") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['message_id'], $explodeUri[3]));

                } else if ($explodeUri[4] == "select_group_id") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['group_id'], $explodeUri[3]));

                } else if ($explodeUri[4] == "select_user_id") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['user_id'], $explodeUri[3]));

                } else if ($explodeUri[4] == "select_unit_id") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['unit_id'], $explodeUri[3]));

                } else if ($explodeUri[4] == "select_push_id") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['push_id'], $explodeUri[3]));

                } else if (
                    $explodeUri[4] == "select_device" ||
                    $explodeUri[4] == "delete_device") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['device_id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "select_message" ||
                    $explodeUri[4] == "select_group_member" ||
                    $explodeUri[4] == "find_id" ||
                    $explodeUri[4] == "select_group_chat" ||
                    $explodeUri[4] == "get_layout" ||
                    $explodeUri[4] == "update" ||
                    $explodeUri[4] == "insert_data_alignment" ||
                    $explodeUri[4] == "add_page" ||
                    $explodeUri[4] == "insert_form_layout" ||
                    $explodeUri[4] == "insert_gann_data" ||
                    $explodeUri[4] == "insert_page_layout" ||
                    $explodeUri[4] == "update_object" ||
                    $explodeUri[4] == "delete_all_get" ||
                    $explodeUri[4] == "delete" ||
                    $explodeUri[4] == "update_id" ||
                    $explodeUri[4] == "select_id_get"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "get_by_organization"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['org_id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "get_by_user"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['user_id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "find_by_page_id"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['page_id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "get_by_parent_unit_id" ||
                    $explodeUri[4] == "get_by_parent"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['parent_id'], $explodeUri[3]));
                } else if (
                    $explodeUri[4] == "insert_message" ||
                    $explodeUri[4] == "select_all_message" ||
                    $explodeUri[4] == "insert_group_member" ||
                    $explodeUri[4] == "select_all_member" ||
                    $explodeUri[4] == "insert_attachment" ||
                    $explodeUri[4] == "select_all_attachment" ||
                    $explodeUri[4] == "select_all_device" ||
                    $explodeUri[4] == "insert_user_device" ||
                    $explodeUri[4] == "select_all_group_chat" ||
                    $explodeUri[4] == "insert_group_chat" ||
                    $explodeUri[4] == "get" ||
                    $explodeUri[4] == "insert" ||
                    $explodeUri[4] == "insert_alignment" ||
                    $explodeUri[4] == "insert_form_data" ||
                    $explodeUri[4] == "insert_gann" ||
                    $explodeUri[4] == "insert_page_data" ||
                    $explodeUri[4] == "get_leaf_unit" ||
                    $explodeUri[4] == "insert_object" ||
                    $explodeUri[4] == "select_all_get"
                ) {
                    $result = call_user_func_array(array(new $class($connection), $method), array($explodeUri[3]));
                } else if ($explodeUri[4] == "select_where_get") {
                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['attr'], $vars['val'], $explodeUri[3]));
                } else if ($explodeUri[6] == "join_chat" ||
                    $explodeUri[6] == "join_group_chat") {

                    $result = call_user_func_array(array(new $class($connection), $method), array($vars['user_id'], $vars['group_id'], $explodeUri[3], $explodeUri[4]));

                }
                break;
        }

        // die($result);

        try {
            if ($result == [] || $result == 'Data Kosong') {
                $this->msg(204, $result, "gagal", 0);
            } else {
                $this->msg(200, $result, "berhasil", 1);
            }

        } catch (\Throwable $th) {
            $this->msg(203, $th, "Terjadi Kesalahan");
        }

    }

}
