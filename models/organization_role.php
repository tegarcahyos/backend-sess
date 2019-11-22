<?php

class OrganizationRole
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
            'organization_id' => $organization_id,
            'organization_name' => $organization_name,
            'organization_code' => $organization_code,
            'role_id' => $role_id,
            'role_name' => $role_name,

        );
        return $data_item;
    }

    public function findByOrgId($organization_id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE organization_id = ' . $organization_id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'name' => $name,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        $organization_id = $request[0]->organization_id;
        $organization_name = $request[0]->organization_name;
        $organization_code = $request[0]->organization_code;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = "INSERT INTO $tablename (organization_id, organization_name, organization_code, role_id, role_name)";
        $query .= "VALUES ($organization_id , '$organization_name', '$organization_code', $role_id , '$role_name')";
        die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $organization_id = $request->organization_id;
        $organization_name = $request->organization_name;
        $organization_code = $request->organization_code;
        $role_id = $request[0]->role_id;
        $role_name = $request[0]->role_name;

        $query = "UPDATE " . $tablename . " SET role_name = '" . $role_name . "', role_id = '" . $role_id . "', organization_id = '" . $organization_id . "', organization_code = '" . $organization_code . "', organization_name = '" . $organization_name . " WHERE id = " . $id;
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
