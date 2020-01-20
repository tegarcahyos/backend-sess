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
             $tablename";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    // 'organization_id' => $organization_id,
                    // 'organization_name' => $organization_name,
                    // 'organization_code' => $organization_code,
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

    public function getAllParent($tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE parent_id = '0'";
        die($query);
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    // 'organization_id' => $organization_id,
                    // 'organization_name' => $organization_name,
                    // 'organization_code' => $organization_code,
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

    public function getRootParent($id)
    {
        $query = "WITH RECURSIVE parents AS
        (
          SELECT
            id              AS id,
            0               AS number_of_ancestors,
            NULL :: TEXT AS parent_id,
            id              AS root_id
          FROM unit
          WHERE
            parent_id::text IS NOT NULL
          UNION
          SELECT
            child.id                                    AS id,
            p.number_of_ancestors + 1                   AS ancestry_size,
            child.parent_id::text                              AS parent_id,
            coalesce(p.root_id::uuid, child.parent_id::uuid) AS root_id
          FROM unit child
            INNER JOIN parents p ON p.id::text = child.parent_id::text
        )
        SELECT
          id,
          number_of_ancestors,
          parent_id,
          root_id
        FROM parents  where id = '$id' AND number_of_ancestors = (select max(parents.number_of_ancestors) from parents where id = '$id')";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'number_of_ancestors' => $number_of_ancestors,
                    'parent_id' => $parent_id,
                    'root_id' => $root_id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = [];
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
            $query = "SELECT * FROM unit WHERE id = '$id'";
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

    public function getByParent($parent_id, $tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE parent_id = '$parent_id'";

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
                    // 'organization_id' => $organization_id,
                    // 'organization_name' => $organization_name,
                    // 'organization_code' => $organization_code,
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
           WHERE NOT EXISTS (SELECT * FROM $tablename t2 WHERE t1.id::text = t2.parent_id::text)";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    // 'organization_id' => $organization_id,
                    // 'organization_name' => $organization_name,
                    // 'organization_code' => $organization_code,
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

    public function findByOrgId($org_id, $tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE organization_id = '$org_id'
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
                // 'organization_id' => $organization_id,
                // 'organization_name' => $organization_name,
                // 'organization_code' => $organization_code,
                'parent_id' => $parent_id,
                'name' => $name,
                'code' => $code,
            );
            return $data_item;
        }
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        // $organization_id = $request[0]->organization_id;
        // $organization_name = $request[0]->organization_name;
        // $organization_code = $request[0]->organization_code;
        $parent_id = $request[0]->parent_id;
        $name = $request[0]->name;
        $code = $request[0]->code;

        $query = "INSERT INTO $tablename (
            parent_id, name, code)";
        $query .= "VALUES (
            '$parent_id' , '$name', '$code')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        // $organization_id = $request->organization_id;
        // $organization_name = $request->organization_name;
        // $organization_code = $request->organization_code;
        $parent_id = $request[0]->parent_id;
        $name = $request[0]->name;
        $code = $request[0]->code;

        $query = "UPDATE $tablename SET name = '$name', code = '$code',parent_id = '$parent_id' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
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
