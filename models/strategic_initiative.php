<?php

class StraIn
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
             $tablename order by created_at asc";

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
                    'parent_id' => $parent_id,
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
    public function getParentSIBy($id)
    {
        // Jika parent
        if ($id == '') {
            // is root
        } else {
            // bukan root
            // select name, parentId by $id,
            $query = "SELECT * FROM strategic_initiative WHERE id = '$id'";
            //
            $result = $this->db->execute($query);
            // die(print_r($result->fetchRow()));
            $row = $result->fetchRow();
            extract($row);
            die(print_r($row['name']));

            $nameTemp = $name;
            // SUNTIK nama array
            array_push($this->parentArray, $nameTemp);
            // Ambil parent id, buat dicari lagi atasnya
            $idParentTemp = $parent_id;
            // Cari atasnya
            $this->getParentSIBy($idParentTemp);
        }
    }

    public function getByParent($parent_id, $tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE parent_id = '$parent_id'";
        // die($query);
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                // ambil parent parentnya pake $parent_id
                $this->getParentSIBy($parent_id);
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
                'name' => $name,
                'code' => $code,
                'parent_id' => $parent_id,
            );
            return $data_item;
        }
    }

    public function getLeaf($tablename)
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

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;
        $code = $request[0]->code;
        $parent_id = $request[0]->parent_id;
        $query = "INSERT INTO $tablename (name, code, parent_id)";
        $query .= "VALUES ('$name', '$code', '$parent_id')";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Ditambah', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;
        $code = $request[0]->code;
        $parent_id = $request[0]->parent_id;

        $query = "UPDATE $tablename SET name = '$name', code = '$code', parent_id = '$parent_id' WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data berhasil diperbaharui', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
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
