<?php

class ExpertJudgement
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
                    'user_id' => $user_id,
                    'program_charter' => $program_charter,
                    'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
                );

                array_push($data_arr, $data_item);
                $result_arr = array();
                for ($i = 0; $i < count($data_arr); $i++) {
                    $unit = "SELECT * FROM unit WHERE id = '" . $data_arr[$i]['unit_id'] . "'";
                    // die($unit);
                    $result = $this->db->execute($unit);
                    $unit = $result->fetchRow();
                    $data_item['unit_name'] = $unit['name'];
                    $get_id_pc = json_decode($data_arr[$i]['program_charter']);
                    $pc = array_values((array) $get_id_pc);
                    // die(print_r($pc));
                    for ($j = 0; $j < count($pc); $j++) {
                        if (!empty($pc[$j])) {
                            for ($k = 0; $k < count($pc[$j]); $k++) {
                                // die(print_r($pc[$j][$k]));
                                $get_pc = "SELECT * FROM program_charter WHERE id = '" . $pc[$j][$k] . "'";
                                $result = $this->db->execute($get_pc);
                                $num = $result->rowCount();
                                if ($num > 0) {
                                    while ($row = $result->fetchRow()) {
                                        $data_item['detail_pc'][$row['id']]['title'] = $row['title'];
                                        $data_item['detail_pc'][$row['id']]['weight'] = $row['weight'];
                                    }
                                }
                            }
                        }
                    }

                    $periode = "SELECT * FROM periode WHERE id = '" . $data_arr[$i]['periode_id'] . "'";
                    $result = $this->db->execute($periode);
                    $periode = $result->fetchRow();
                    $data_item['periode_name'] = $periode['name'];

                    $user = "SELECT * FROM users WHERE id = '" . $data_arr[$i]['user_id'] . "'";
                    $result = $this->db->execute($user);
                    $user = $result->fetchRow();
                    $data_item['user_name'] = $user['name'];

                    array_push($data_arr, $data_item);
                }

                die(print_r(count($data_arr)));

                // die(print_r($data_item));
                array_push($result_arr, $data_item);

            }
            // die(print_r(count($result_arr)));

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

                array_push($data_arr, $data_item);
                $result_arr = array();
                for ($i = 0; $i < count($data_arr); $i++) {
                    $unit = "SELECT * FROM unit WHERE id = '" . $data_arr[$i]['unit_id'] . "'";
                    $result = $this->db->execute($unit);
                    $unit = $result->fetchRow();
                    $data_item['unit_name'] = $unit['name'];

                    $periode = "SELECT * FROM periode WHERE id = '" . $data_arr[$i]['periode_id'] . "'";
                    $result = $this->db->execute($periode);
                    $periode = $result->fetchRow();
                    $data_item['periode_name'] = $periode['name'];

                    $user = "SELECT * FROM users WHERE id = '" . $data_arr[$i]['user_id'] . "'";
                    $result = $this->db->execute($user);
                    $user = $result->fetchRow();
                    $data_item['user_name'] = $user['name'];
                }

                // die(print_r($data_item));
                array_push($result_arr, $data_item);
                $msg = $result_arr;
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

        $user_id = $request[0]->user_id;
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
