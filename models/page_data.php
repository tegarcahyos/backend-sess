<?php

class PageData
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename
          ORDER BY
            id ASC";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'page_id' => $page_id,
                    'page_name' => $page_name,
                    'data' => json_decode($data),
                    'role_user' => $role_user,
                    'unit_user' => $unit_user,

                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function findById($id, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE id = '$id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'page_id' => $page_id,
            'page_name' => $page_name,
            'data' => json_decode($data),
            'role_user' => $role_user,
            'unit_user' => $unit_user,
        );
        return $data_item;
    }

    public function findByPageId($page_id, $tablename)
    {
        $query = "SELECT * FROM  $tablename WHERE page_id = '$page_id'";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'page_id' => $page_id,
                    'page_name' => $page_name,
                    'data' => json_decode($data),
                    'role_user' => $role_user,
                    'unit_user' => $unit_user,

                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;
        $unit_user = $request[0]->unit_user;
        $role_user = $request[0]->role_user;
        // Input Code
        $unit_code;
        $unit_name;
        $unit_id;
        $role_id;
        $role_name;
        // Input Code
        $data = $request[0]->data;
        $data = json_encode($data);
        $query = "INSERT INTO $tablename (page_id, page_name, data, unit_user, role_user)";
        $query .= " VALUES ('$page_id','$page_name','$data', '$unit_user', '$role_user')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;
        $data = $request[0]->data[0]->data;
        $unit_user = $request[0]->unit_user;
        $role_user = $request[0]->role_user;
        $query = "UPDATE $tablename SET data = $data, page_id = '$page_id', page_name = '$page_name', unit_user = '$unit_user', role_user = '$role_user' WHERE id = " . $id;
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }
}
