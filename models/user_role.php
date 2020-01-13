<?php

class UserRole
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
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'role_id' => $role_id,
                    'role_name' => $role_name,
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
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'role_id' => $role_id,
            'role_name' => $role_name,
        );
        return $data_item;
    }

    public function findByUserId($user_id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE user_id = ' . $user_id . "";
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'role_id' => $role_id,
                    'role_name' => $role_name,
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
        //
        $request = json_decode($data);
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = "INSERT INTO $tablename (role_id, role_name, user_id, user_name)";
        $query .= "VALUES ('$role_id', '$role_name' , '$user_id', '$user_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = "UPDATE " . $tablename . " SET user_id = '" . $user_id . "',user_name = '" . $user_name . "', role_id = '" . $role_id . "', role_name = '" . $role_name . "' WHERE id = " . $id;
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
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
