<?php

class AHPFeaturedPC
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
                    'data' => json_decode($data),
                    'judgement' => json_decode($judgement),
                    'name' => $name,
                    'periode_id' => $periode_id,
                    'criteria_id' => $criteria_id,
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
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = "Data Kosong";
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'organization_id' => $organization_id,
                'data' => json_decode($data),
                'judgement' => json_decode($judgement),
                'name' => $name,
                'periode_id' => $periode_id,
                'criteria_id' => $criteria_id,
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

        $variable = array('organization_id', 'data', 'judgement', 'periode_id', 'name', 'criteria_id');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $data = json_encode($data);
        $judgement = json_encode($judgement);

        $query = "INSERT INTO $tablename (organization_id, data, judgement, periode_id, name, criteria_id)";
        $query .= "VALUES ('$organization_id', '$data', '$judgement', '$periode_id', '$name', '$criteria_id') RETURNING *";
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
                        'data' => json_decode($data),
                        'judgement' => json_decode($judgement),
                        'name' => $name,
                        'periode_id' => $periode_id,
                        'criteria_id' => $criteria_id,
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
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $variable = array('organization_id', 'data', 'judgement', 'periode_id', 'name', 'criteria_id');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $data = json_encode($data);
        $judgement = json_encode($judgement);

        $query = "UPDATE $tablename SET organization_id = '$organization_id', data = '$data', judgement = '$judgement', periode_id = '$periode_id', name = '$name', criteria_id = '$criteria_id' WHERE id = '$id' RETURNING *";
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
                        'data' => json_decode($data),
                        'judgement' => json_decode($judgement),
                        'name' => $name,
                        'periode_id' => $periode_id,
                        'criteria_id' => $criteria_id,
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
    }
}
