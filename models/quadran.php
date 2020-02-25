<?php

class Quadran
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($tablename)
    {
        $query = "SELECT * FROM  $tablename";
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
                    'user_id' => $user_id,
                    'program_charter' => $program_charter,
                    'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
                );
                for ($i=0; $i < count($data_arr); $i++) {
                    $unit = "SELECT * FROM unit WHERE id = '".$data_arr[$i]['unit_id']."'";
                    // echo $unit;
                    $result = $this->db->execute($unit);
                    $row = $result->fetchRow();
                        extract($row);
                        $data_unit = array(
                            'unit_name' => $name,
                        );

                        // array_push($result_arr, $data_unit);
                        if (!empty($data_unit)) {
                            array_push($data_item, $data_unit);
                        }

                    $periode = "SELECT * FROM periode WHERE id = '".$data_arr[$i]['periode_id']."'";
                    $result = $this->db->execute($periode);
                    $row = $result->fetchRow();
                        extract($row);
                        $data_periode = array(
                            'periode_name' => $name,
                        );
                        // array_push($result_arr, $data_periode);
                        if (!empty($data_unit)) {
                            array_push($data_item, $data_periode);
                        }
                        
                }
                
                array_push($data_arr, $data_item);
                // array_push($result_arr, $data_item);
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

            // Push to data_arr

            $data_item = array(
                'id' => $id,
                'user_id' => $user_id,
                'program_charter' => $program_charter,
                'unit_id' => $unit_id,
                'periode_id' => $periode_id,
            );

            $msg = $data_item;
            return $msg;
        }
    }

    public function findByUserId($user_id, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE user_id = '$user_id'";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'program_charter' => $program_charter,
                    'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
                );

                $data_item['unit_name'] = 'adasas';
                
                die(print_r($data_item));

                for ($i=0; $i < count($data_arr); $i++) { 
                    $unit = "SELECT * FROM unit WHERE id = '$data_arr[$i]['unit_id']'";
                    $result = $this->db->execute($unit);
                    $unit = $result->fetchRow();
                    $data_item['unit_name'] = $unit['name'];

                        // array_push($data_arr, $data_unit);

                    $periode = "SELECT * FROM periode WHERE id = '$data_arr[$i]['periode_id']'";
                    $result = $this->db->execute($periode);
                    $periode = $result->fetchRow();
                        // extract($row);
                        // $data_periode = array(
                        //     'periode_name' => $name,
                        // );
                        $data_item['periode_name'] = $periode['name'];
                        // array_push($data_arr, $data_periode);
                        
                }
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
        $program_charter = json_encode($request[0]->program_charter);

        $variable = array('user_id', 'program_charter', 'unit_id', 'periode_id');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        // die($program_charter);

        $query = 'INSERT INTO ' . $tablename . ' (user_id, program_charter, unit_id, periode_id) ';
        $query .= "VALUES ('$user_id', '$program_charter', '$unit_id', '$periode_id') RETURNING *";
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
                        'user_id' => $user_id,
                        'program_charter' => $program_charter,
                        'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
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
        // init attribute dan values

        $data = file_get_contents("php://input");

        $request = json_decode($data);
        $variable = array('user_id', 'program_charter', 'unit_id', 'periode_id');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $program_charter = json_encode($program_charter);

        $query = "UPDATE $tablename SET user_id = '$user_id', program_charter = '$program_charter', unit_id = '$unit_id', periode_id = '$periode_id' WHERE id = '$id' RETURNING *";
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
                        'user_id' => $user_id,
                        'program_charter' => $program_charter,
                        'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
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

        $result = $this->db->execute($query);
        
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = "Data Kosong";
        }
    }

    public function deleteByUserId($user_id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE user_id = '$user_id'";

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
