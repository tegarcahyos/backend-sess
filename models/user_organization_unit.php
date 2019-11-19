<?php

class UserOrganizationUnit
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
                    'organization_unit_id' => $organization_unit_id,
                    'organization_unit_name' => $organization_unit_name,
                    'organization_unit_code' => $organization_unit_code,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_code' => $user_code,

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
            'organization_unit_id' => $organization_unit_id,
            'organization_unit_name' => $organization_unit_name,
            'organization_unit_code' => $organization_unit_code,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'user_code' => $user_code,

        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $organization_id = $_POST["organization_id"];
        $organization_name = $_POST["organization_name"];
        $organization_code = $_POST["organization_code"];
        $organization_unit_id = $_POST["organization_unit_id"];
        $organization_unit_name = $_POST["organization_unit_name"];
        $organization_unit_code = $_POST["organization_unit_code"];
        $user_id = $_POST["user_id"];
        $user_name = $_POST["user_name"];
        $user_code = $_POST["user_code"];

        $query = "INSERT INTO $tablename (organization_id, organization_name, organization_code, organization_unit_id, organization_unit_name, organization_unit_code, user_id, user_name, user_code,)";
        $query .= "VALUES ($organization_id , '$organization_name', '$organization_code', $organization_unit_id , '$organization_unit_name', '$organization_unit_code', $user_id , '$user_name', '$user_code')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $organization_id = $_POST["organization_id"];
        $organization_name = $_POST["organization_name"];
        $organization_code = $_POST["organization_code"];
        $organization_unit_id = $_POST["organization_unit_id"];
        $organization_unit_name = $_POST["organization_unit_name"];
        $organization_unit_code = $_POST["organization_unit_code"];
        $user_id = $_POST["user_id"];
        $user_name = $_POST["user_name"];
        $user_code = $_POST["user_code"];

        $query = "UPDATE " . $tablename . " SET organization_id = '" . $organization_id . "', organization_code = '" . $organization_code . "', organization_name = '" . $organization_name . "', organization_unit_id = '" . $organization_unit_id . "', organization_unit_code = '" . $organization_unit_code . "', organization_unit_name = '" . $organization_unit_name . "', user_id = '" . $user_id . "', user_code = '" . $user_code . "', user_name = '" . $user_name . "'" . " WHERE id = " . $id;
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
