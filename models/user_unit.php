<?php

class UserUnit
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
                    'organization_id' => $organization_id,
                    'organization_name' => $organization_name,
                    'organization_code' => $organization_code,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'unit_code' => $unit_code,
                    'user_id' => $user_id,
                    'user_name' => $user_name,

                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function find($id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'organization_id' => $organization_id,
            'organization_name' => $organization_name,
            'organization_code' => $organization_code,
            'unit_id' => $unit_id,
            'unit_name' => $unit_name,
            'unit_code' => $unit_code,
            'user_id' => $user_id,
            'user_name' => $user_name,

        );
        return $data_item;
    }

    public function getByUserId($tablename, $user_id)
    {
        $query = "SELECT
           *
          FROM
             $tablename
              WHERE user_id =  $user_id ";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'organization_name' => $organization_name,
                    'organization_code' => $organization_code,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'unit_code' => $unit_code,
                    'user_id' => $user_id,
                    'user_name' => $user_name,

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
        $organization_id = $request[0]->organization_id;
        $organization_name = $request[0]->organization_name;
        $organization_code = $request[0]->organization_code;
        $unit_id = $request[0]->unit_id;
        $unit_name = $request[0]->unit_name;
        $unit_code = $request[0]->unit_code;
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;

        $query = "INSERT INTO $tablename (organization_id, organization_name, organization_code, unit_id, unit_name, unit_code, user_id, user_name)";
        $query .= "VALUES ($organization_id , '$organization_name', '$organization_code', $unit_id , '$unit_name', '$unit_code', $user_id , '$user_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $organization_id = $request[0]->organization_id;
        $organization_name = $request[0]->organization_name;
        $organization_code = $request[0]->organization_code;
        $unit_id = $request[0]->unit_id;
        $unit_name = $request[0]->unit_name;
        $unit_code = $request[0]->unit_code;
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;

        $query = "UPDATE " . $tablename . " SET organization_id = '" . $organization_id . "', organization_code = '" . $organization_code . "', organization_name = '" . $organization_name . "', unit_id = '" . $unit_id . "', unit_code = '" . $unit_code . "', unit_name = '" . $unit_name . "', user_id = '" . $user_id . "', user_name = '" . $user_name . "'" . " WHERE id = " . $id;
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
        // die($query);
        return $this->db->execute($query);
    }
}
