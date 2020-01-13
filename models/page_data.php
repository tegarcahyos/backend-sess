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
                    'user_id' => $user_id,

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
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'page_id' => $page_id,
                'page_name' => $page_name,
                'data' => json_decode($data),
                'role_user' => $role_user,
                'unit_user' => $unit_user,
                'user_id' => $user_id,
            );
            return $data_item;
        }
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
                    'user_id' => $user_id,
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
        $data = json_encode($request[0]->data);
        $role_user = $request[0]->role_user;
        $unit_user = $request[0]->unit_user;
        $user_id = $request[0]->user_id;
        $query = "INSERT INTO $tablename (page_id, page_name, data, role_user, unit_user, user_id)";
        $query .= " VALUES ('$page_id','$page_name','$data', '$role_user', '$unit_user', '$user_id') RETURNING id";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        // jika ada hasil
        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                // Push to data_arr

                $data_item = array(
                    'id' => $id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;
        $data = json_encode($request[0]->data);
        $role_user = $request[0]->role_user;
        $unit_user = $request[0]->unit_user;
        $user_id = $request[0]->user_id;
        $query = "UPDATE $tablename SET data = '$data', page_id = '$page_id', page_name = '$page_name', role_user = '$role_user', unit_user = '$unit_user', user_id = '$user_id' WHERE id = '$id' RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        // jika ada hasil
        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                // Push to data_arr

                $data_item = array(
                    'id' => $id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
    }
}
