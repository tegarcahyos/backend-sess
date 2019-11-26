<?php

class Unit
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
                    'parent_id' => $parent_id,
                    'name' => $name,
                    'code' => $code,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    private $parentArray = [""];
    public function getParentUnitBy($id)
    {
        // Jika parent
        if ($id == 0) {
            // is root
        } else {
            // bukan root
            // select name, parentId by $id,
            $query = "SELECT * FROM unit WHERE id = $id";
            //
            $result = $this->db->execute($query);
            $row = $result->fetchRow();
            extract($row);

            $nameTemp = $name;
            // SUNTIK nama array
            array_push($this->parentArray, $nameTemp);
            // Ambil parent id, buat dicari lagi atasnya
            $idParentTemp = $parent_id;
            // Cari atasnya
            $this->getParentUnitBy($idParentTemp);
        }
    }

    public function getByParent($tablename, $parent_id)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE parent_id = $parent_id
          ORDER BY
            id ASC";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                // ambil parent parentnya pake $parent_id
                $this->getParentUnitBy($parent_id);
                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'organization_name' => $organization_name,
                    'organization_code' => $organization_code,
                    'parent_id' => $parent_id,
                    'name' => $name,
                    'code' => $code,
                    'parent_list' => $this->parentArray,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = [];
        }

        return $msg;
    }

    public function getLeafUnit($tablename)
    {
        $query = "SELECT
        *
       FROM
          $tablename t1
           WHERE NOT EXISTS (SELECT * FROM $tablename t2 WHERE t1.id=t2.parent_id)";
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
                    'parent_id' => $parent_id,
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

    public function getUnitByOrgId($tablename, $org_id)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE organization_id = $org_id AND parent_id = 0
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
                    'parent_id' => $parent_id,
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
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'organization_id' => $organization_id,
            'organization_name' => $organization_name,
            'organization_code' => $organization_code,
            'parent_id' => $parent_id,
            'name' => $name,
            'code' => $code,
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
        $parent_id = $request[0]->parent_id;
        $name = $request[0]->name;
        $code = $request[0]->code;

        $query = "INSERT INTO $tablename (organization_id, organization_name, organization_code, parent_id, name, code)";
        $query .= "VALUES ($organization_id , '$organization_name', '$organization_code', $parent_id , '$name', '$code')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $organization_id = $request->organization_id;
        $organization_name = $request->organization_name;
        $organization_code = $request->organization_code;
        $parent_id = $request->parent_id;
        $name = $request->name;
        $code = $request->code;

        $query = "UPDATE " . $tablename . " SET name = '" . $name . "', code = '" . $code . "', organization_id = '" . $organization_id . "', organization_code = '" . $organization_code . "', organization_name = '" . $organization_name . "', parent_id = '" . $parent_id . " WHERE id = " . $id;
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
