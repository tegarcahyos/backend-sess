<?php

class UserDetail
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($tablename)
    {
        $query = "SELECT * FROM  $tablename ";
        // die($query);
        $result = $this->db->execute($query);
        // hitung result
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'unit_id' => $unit_id,
                    'user_code' => $user_code,
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
        $query = "SELECT * FROM $tablename WHERE id = '$id'";
        $result = $this->db->execute($query);
        if (empty($result)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            $row = $result->fetchRow();
            extract($row);

            // Push to data_arr

            $data_item = array(
                'id' => $id,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'unit_id' => $unit_id,
                'user_code' => $user_code,
                'user_name' => $user_name,
                'role_id' => $role_id,
                'role_name' => $role_name,
            );

            $msg = $data_item;
            return $msg;
        }
    }

    public function insert($tablename)
    {
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;
        $unit_id = $request[0]->unit_id;
        $unit_code = $request[0]->unit_code;
        $unit_name = $request[0]->unit_name;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = 'INSERT INTO ' . $tablename . ' (user_id,
        user_name,
        unit_id,
        unit_code,
        unit_name,
        role_id,
        role_name) ';
        $query .= "VALUES ('$user_id',
        '$user_name',
        '$unit_id',
        '$unit_code',
        '$unit_name',
        '$role_id',
        '$role_name') RETURNING *";
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
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'unit_id' => $unit_id,
                    'user_code' => $user_code,
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

    public function update($id, $tablename)
    {
        // init attribute dan values

        $data = file_get_contents("php://input");

        $request = json_decode($data);
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;
        $unit_id = $request[0]->unit_id;
        $unit_code = $request[0]->unit_code;
        $unit_name = $request[0]->unit_name;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = "UPDATE $tablename SET user_id = '$user_id', user_name = '$user_name', unit_id = '$unit_id', unit_code = '$unit_code', unit_name = '$unit_name', role_id = '$role_id', role_name = '$role_name' WHERE id = '$id' RETURNING *";

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
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'unit_id' => $unit_id,
                    'user_code' => $user_code,
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

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";

        $result = $this->db->execute($query);
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
    }

    public function deleteByValues($attr, $val, $tablename)
    {

        $condition_values = explode('AND', $val);
        $condition_attr = explode('AND', $attr);
        $query = "DELETE FROM  $tablename";
        for ($i = 0; $i < count($condition_attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "values @> '{\"" . $condition_attr[$i] . "\": \"" . $condition_values[$i] . "\"}'";

        }
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        return $this->db->execute($query_real);
    }

}
