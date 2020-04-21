<?php

class Matrix
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
                    'si_id' => $si_id,
                    'unit_id' => $unit_id,
                    'matrix' => $matrix,
                );

                array_push($data_arr, $data_item);

            }

            for ($i = 0; $i < count($data_arr); $i++) {
                $get_si = "SELECT * FROM strategic_initiative WHERE id = '" . $data_arr[$i]['si_id'] . "'";
                $result = $this->db->execute($get_si);
                $si = $result->fetchRow();
                $data_arr[$i]['si_name'] = $si['name'];

                $get_unit = "SELECT * FROM unit WHERE id = '" . $data_arr[$i]['unit_id'] . "'";
                $result = $this->db->execute($get_unit);
                $unit = $result->fetchRow();
                $data_arr[$i]['unit_name'] = $unit['name'];
            }

            $msg = $data_arr;
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

            // Push to data_arr

            $data_item = array(
                'id' => $id,
                'si_id' => $si_id,
                'unit_id' => $unit_id,
                'matrix' => $matrix,
            );
            $get_si = "SELECT * FROM strategic_initiative WHERE id = '" . $data_item['si_id'] . "'";
            $result = $this->db->execute($get_si);
            $si = $result->fetchRow();
            $data_item['si_name'] = $si['name'];

            $get_unit = "SELECT * FROM unit WHERE id = '" . $data_item['unit_id'] . "'";
            $result = $this->db->execute($get_unit);
            $unit = $result->fetchRow();
            $data_item['unit_name'] = $unit['name'];

            $msg = $data_item;
            return $msg;
        }
    }

    public function getByValues($attr, $val, $tablename)
    {

        $query = "SELECT * FROM $tablename WHERE ";
        $values = explode('AND', $val);
        $attr = explode('AND', $attr);
        for ($i = 0; $i < count($attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            // $query .= "values @> '{\"" . $attr[$i] . "\": \"" . $values[$i] . "\"}'";
            $query .= "values ->> '" . $attr[$i] . "' = '" . $values[$i] . "'";

        }

        $query_real = str_replace("%20", " ", $query);
        die($query_real);
        $result = $this->db->execute($query_real);

        $num = $result->rowCount();

        if ($num > 0) {
            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'si_id' => $si_id,
                    'unit_id' => $unit_id,
                    'matrix' => $matrix,
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
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $variable = array('si_id', 'unit_id', 'matrix');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $queryCheckIfExist = "SELECT * FROM $tablename WHERE si_id = '$si_id' and unit_id = '$unit_id' and matrix = '$matrix'";
        $resultCheck = $db->execute($queryCheckIfExist);
        $rowCheck = $resultCheck->fetchRow();
        
        if (empty($rowCheck)) {
            $query = 'INSERT INTO ' . $tablename . ' (si_id, unit_id, matrix) ';
            $query .= "VALUES ('$si_id','$unit_id', '$matrix') RETURNING *";
            // die($query);
            $result = $this->db->execute($query);
            if (empty($result)) {
                return "422";
            } else {
                $num = $result->rowCount();

                // jika ada hasil
                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        // Push to data_arr

                        $data_item = array(
                            'id' => $id,
                            'si_id' => $si_id,
                            'unit_id' => $unit_id,
                            'matrix' => $matrix,
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }
                }

                return $msg;

            }
        } else {
            return "515";
        }
    }

    public function update($id, $tablename)
    {
        // init attribute dan values

        $data = file_get_contents("php://input");

        $request = json_decode($data);

        $variable = array('si_id', 'unit_id', 'matrix');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = "UPDATE $tablename SET si_id = '$si_id', unit_id = '$unit_id',matrix = '$matrix' WHERE id = '$id' RETURNING *";

        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();

            // jika ada hasil
            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    // Push to data_arr

                    $data_item = array(
                        'id' => $id,
                        'si_id' => $si_id,
                        'unit_id' => $unit_id,
                        'matrix' => $matrix,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }

            return $msg;
        }

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
            return $msg = "Data Kosong";
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
