<?php
include_once 'isCodeExists.php';
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
             $tablename LIMIT 100";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
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
            id              AS root_id,
            name            AS root_name,
            organization_id AS organization_id,
            cfu_fu_id       AS cfu_fu_id
          FROM unit
          WHERE
            parent_id::text IS NOT NULL
          UNION
          SELECT
            child.id                                    AS id,
            p.number_of_ancestors + 1                   AS ancestry_size,
            child.parent_id::text                              AS parent_id,
            coalesce(p.root_id::uuid, child.parent_id::uuid) AS root_id,
            p.root_name,
            p.organization_id,
            p.cfu_fu_id
          FROM unit child
            INNER JOIN parents p ON p.id::text = child.parent_id::text
        )
        SELECT
          id,
          number_of_ancestors,
          parent_id,
          root_id,
          root_name,
          organization_id,
          cfu_fu_id
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
                    'root_name' => $root_name,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }
        } else {
            $msg = [];
        }

        return $msg;
    }

    public function getAllParent($id)
    {
        $query = "WITH RECURSIVE parents AS
        (
          SELECT
            id              AS id,
            0               AS number_of_ancestors,
            NULL :: TEXT AS parent_id,
            id              AS root_id,
            name            AS root_name,
            organization_id AS organization_id,
            cfu_fu_id       AS cfu_fu_id
          FROM unit
          WHERE
            parent_id::text IS NOT NULL
          UNION
          SELECT
            child.id                                    AS id,
            p.number_of_ancestors + 1                   AS ancestry_size,
            child.parent_id::text                              AS parent_id,
            coalesce(p.root_id::uuid, child.parent_id::uuid) AS root_id,
            p.root_name,
            p.organization_id,
            p.cfu_fu_id
          FROM unit child
            INNER JOIN parents p ON p.id::text = child.parent_id::text
        )
        SELECT
          root_id AS id,
          number_of_ancestors,
          parent_id,
          root_name,
          organization_id,
          cfu_fu_id
        FROM parents  where id = '$id'";

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
                    // 'root_id' => $root_id,
                    'root_name' => $root_name,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
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

    public function searchUnit($value, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE code ilike '%$value%' OR name ilike '%$value%'";
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
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
                // $this->getParentUnitBy($parent_id);
                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
                    'parent_id' => $parent_id,
                    'name' => $name,
                    'code' => $code,
                    // 'parent_list' => $this->parentArray,
                );

                $child = $this->getByParent($id, $tablename);
                $data_item['parent'] = $child;
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
           WHERE NOT EXISTS (SELECT * FROM $tablename t2 WHERE t1.id::text = t2.parent_id::text) LIMIT 100";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
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
        // echo count($msg);
        return $msg;
    }

    public function findByOrgId($org_id, $tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename WHERE organization_id = '$org_id' AND parent_id = '0'";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'organization_id' => $organization_id,
                    'cfu_fu_id' => $cfu_fu_id,
                    'parent_id' => $parent_id,
                    'name' => $name,
                    'code' => $code,
                );

                $get_parent = $this->getByParent($id, $tablename);
                $data_item['parent'] = $get_parent;
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
                'cfu_fu_id' => $cfu_fu_id,
                'parent_id' => $parent_id,
                'name' => $name,
                'code' => $code,
            );
            return $data_item;
        }
    }

    public function getAllUsers($id, $tablename)
    {
        $query = "SELECT * FROM unit WHERE parent_id = '$id' OR id = '$id'";
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
                    for ($i = 0; $i < count($userArray); $i++) {
                        array_push($resultUsers, $userArray[$i]);
                    }
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
        $variable = array('organization_id', 'cfu_fu_id', 'parent_id', 'name', 'code');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }
        $check = checkIfExists($tablename, $code, $this->db);
        if (empty($check)) {
            $query = "INSERT INTO $tablename (
            parent_id, name, code, organization_id, cfu_fu_id)";
            $query .= "VALUES (
            '$parent_id' , '$name', '$code','$organization_id','$cfu_fu_id') RETURNING *";
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
                            'cfu_fu_id' => $cfu_fu_id,
                            'parent_id' => $parent_id,
                            'name' => $name,
                            'code' => $code,
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }
                }
            }

            return $msg;
        } else {
            return "515";
        }
    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);

        $variable = array('organization_id', 'cfu_fu_id', 'parent_id', 'name', 'code');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = "UPDATE $tablename SET name = '$name', code = '$code',parent_id = '$parent_id', organization_id = '$organization_id', cfu_fu_id = '$cfu_fu_id' WHERE id = '$id' RETURNING *";
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
                        'cfu_fu_id' => $cfu_fu_id,
                        'parent_id' => $parent_id,
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
        $get_refs = "SELECT EXISTS(SELECT 1
        from (
            select unit_id as unit_id from group_member
            union all
            select unit_id::text from matrix
            union all
            select unit_id::text from main_program
            union all
            select unit_id::text from unit_target
            union all
            select unit_id::text from user_detail
        ) a
        where unit_id = '$id')";
        // die($get_refs);
        $result = $this->db->execute($get_refs);
        $row = $result->fetchRow();
        if ($row['exists'] == 't') {
            return "403";
        } else {
            $select = "WITH RECURSIVE tree (id) as
            (SELECT unit.id, unit.parent_id, unit.name from unit where id='$id'
              UNION ALL
              SELECT unit.id, unit.parent_id, unit.name from tree, unit where unit.parent_id = tree.id::varchar)
            SELECT * FROM tree;";
            // die($select);
            $result = $this->db->execute($select);
            $num = $result->rowCount();

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

            for ($i = 0; $i < count($msg); $i++) {
                $query = "DELETE FROM $tablename WHERE id = '" . $msg[$i]['id'] . "'";
                $this->db->execute($query);
            }

            $res = $this->db->affected_rows();

            if ($res == true) {
                return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
            } else {
                return $msg = "Data Kosong";
            }
        }
    }
}
