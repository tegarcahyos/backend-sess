<?php

class CfuFu
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
             $tablename";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'name' => $name,
                    'code' => $code,
                    'organization_id' => $organization_id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function findByOrgId($org_id, $tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE organization_id = '$org_id'";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'name' => $name,
                    'code' => $code,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = [];
        }

        return $msg;
    }

    public function findById($id, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE id = '$id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = "Data Kosong";
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'organization_id' => $organization_id,
                'name' => $name,
                'code' => $code,
            );
            return $data_item;
        }
    }

    public function getAllUsers($id, $tablename)
    {
        $query = "SELECT * FROM unit WHERE cfu_fu_id = '$id'";
        $listUnit = $this->db->execute($query);
        $num = $listUnit->rowCount();

        if ($num > 0) {

            $unitArray = array();

            while ($row = $listUnit->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'parent_id' => $parent_id,
                    'name' => $name,
                );

                array_push($unitArray, $data_item);
            }

            $resultUsers = array();

            for ($i = 0; $i < count($unitArray); $i++) {
                $user = "SELECT * FROM user_detail WHERE unit_id = '" . $unitArray[$i]['id'] . "'";
                $listUser = $this->db->execute($user);
                $num = $listUser->rowCount();

                if ($num > 0) {

                    $userArray = array();

                    while ($row = $listUser->fetchRow()) {
                        extract($row);

                        $data_item = array(
                            'id' => $id,
                            'user_id' => $user_id,
                            'unit_id' => $unit_id,
                            'role_id' => json_decode($role_id),
                        );

                        array_push($userArray, $data_item);

                    }

                } else {
                    $userArray = [];
                }
                if (!empty($userArray)) {
                    array_push($resultUsers, $userArray);
                }
                $msg = $resultUsers;
            }

        } else {
            $msg = [];
        }

        return $msg;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));

        $variable = array('organization_id', 'name', 'code');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = "INSERT INTO $tablename (
            name, code, organization_id)";
        $query .= "VALUES (
            '$name', '$code','$organization_id') RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();
            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    // Push to data_arr

                    $data_item = array(
                        'id' => $id,
                        'organization_id' => $organization_id,
                        'name' => $name,
                        'code' => $code,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }
        }

        return $msg;
    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);

        $variable = array('name', 'code', 'organization');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = "UPDATE $tablename SET name = '$name', code = '$code', organization_id = '$organization_id' WHERE id = '$id' RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();
            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'organization_id' => $organization_id,
                        'name' => $name,
                        'code' => $code,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }
        }

        return $msg;
    }

    public function delete($id, $tablename)
    {
        // $get_refs = "SELECT EXISTS(SELECT 1
        // from (
        //     select cfu_fu_id as cfu_fu_id from program_charter
        //     union all
        //     select cfu_fu_id::text from matrix
        //     union all
        //     select cfu_fu_id::text from main_program
        // ) a
        // where cfu_fu_id = '$id')";
        // $result = $this->db->execute($get_refs);
        // $row = $result->fetchRow();
        // if ($row['exists'] == 't') {
        //     return "403";
        // } else {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = "Data Kosong";
        }
        // }
    }
}
