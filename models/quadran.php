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
                    'program_charter' => json_decode($program_charter),
                    'unit_id' => $unit_id,
                    'periode_id' => $periode_id,
                );
                array_push($data_arr, $data_item);
            }

            $result_arr = array();
            for ($i = 0; $i < count($data_arr); $i++) {
                $unit = "SELECT * FROM unit WHERE id = '" . $data_arr[$i]['unit_id'] . "'";
                // die($unit);
                $result = $this->db->execute($unit);
                $unit = $result->fetchRow();
                $data_arr[$i]['unit_name'] = $unit['name'];

                $periode = "SELECT * FROM periode WHERE id = '" . $data_arr[$i]['periode_id'] . "'";
                $result = $this->db->execute($periode);
                $periode = $result->fetchRow();
                $data_arr[$i]['periode_name'] = $periode['name'];

                $user = "SELECT * FROM users WHERE id = '" . $data_arr[$i]['user_id'] . "'";
                $result = $this->db->execute($user);
                $user = $result->fetchRow();
                $data_arr[$i]['user_name'] = $user['name'];

                $get_id_pc = json_decode($data_arr[$i]['program_charter']);
                // die(print_r($get_id_pc));
                $pc = array_values((array) $get_id_pc);
                for ($k = 0; $k < count($pc); $k++) {
                    if (!empty($pc[$k])) {
                        for ($j = 0; $j < count($pc[$k]); $j++) {
                            // die(print_r($pc[2][0]));
                            $get_pc = "SELECT * FROM program_charter WHERE id = '" . $pc[$k][$j] . "'";
                            // print_r($get_pc);
                            $result = $this->db->execute($get_pc);
                            $num = $result->rowCount();
                            if ($num > 0) {
                                while ($row = $result->fetchRow()) {
                                    $data_arr[$i]['detail_pc'][$row['id']]['title'] = $row['title'];
                                    $data_arr[$i]['detail_pc'][$row['id']]['weight'] = $row['weight'];
                                }
                            }
                        }
                    }
                }
            }
            $msg = $data_arr;
        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function getOrg($org_id)
    {
        $query = "SELECT
           *
          FROM
             unit WHERE organization_id = '$org_id'";

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

        return $msg;
    }

    public function getByPeriodeAndOrganization($periode_id, $org_id, $tablename)
    {

        $getOrg = $this->getOrg($org_id);
        // die(print_r($getOrg));
        $result_arr = array();
        if (!empty($getOrg)) {
            for ($m = 0; $m < count($getOrg); $m++) {
                $query = "SELECT * FROM  $tablename WHERE periode_id = '$periode_id' AND unit_id = '" . $getOrg[$m]['id'] . "'";
                // print_r($query);
                $result = $this->db->execute($query);
                // hitung result
                $num = $result->rowCount();
                // print_r($num);
                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        $data_item = array(
                            'id' => $id,
                            'user_id' => $user_id,
                            'program_charter' => json_decode($program_charter),
                            'unit_id' => $unit_id,
                            'periode_id' => $periode_id,
                        );
                        array_push($data_arr, $data_item);
                    }


                    for ($i = 0; $i < count($data_arr); $i++) {
                        $unit = "SELECT * FROM unit WHERE id = '" . $data_arr[$i]['unit_id'] . "'";
                        // die($unit);
                        $result = $this->db->execute($unit);
                        $unit = $result->fetchRow();
                        $data_arr[$i]['unit_name'] = $unit['name'];

                        $periode = "SELECT * FROM periode WHERE id = '" . $data_arr[$i]['periode_id'] . "'";
                        $result = $this->db->execute($periode);
                        $periode = $result->fetchRow();
                        $data_arr[$i]['periode_name'] = $periode['name'];

                        $user = "SELECT * FROM users WHERE id = '" . $data_arr[$i]['user_id'] . "'";
                        $result = $this->db->execute($user);
                        $user = $result->fetchRow();
                        $data_arr[$i]['user_name'] = $user['name'];

                        $get_id_pc = json_decode($data_arr[$i]['program_charter']);
                        // die(print_r($get_id_pc));
                        $pc = array_values((array) $get_id_pc);
                        for ($k = 0; $k < count($pc); $k++) {
                            if (!empty($pc[$k])) {
                                for ($j = 0; $j < count($pc[$k]); $j++) {
                                    // die(print_r($pc[2][0]));
                                    $get_pc = "SELECT * FROM program_charter WHERE id = '" . $pc[$k][$j] . "'";
                                    // print_r($get_pc);
                                    $result = $this->db->execute($get_pc);
                                    $num = $result->rowCount();
                                    if ($num > 0) {
                                        while ($row = $result->fetchRow()) {
                                            $data_arr[$i]['detail_pc'][$row['id']]['title'] = $row['title'];
                                            $data_arr[$i]['detail_pc'][$row['id']]['weight'] = $row['weight'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // $msg = $data_arr;
                    array_push($result_arr, $data_arr);
                }
            }
            die($result_arr);
            $msg = $result_arr;
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

        $variable = array('user_id', 'program_charter', 'unit_id', 'periode_id');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }
        $program_charter = json_encode($request[0]->program_charter);
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
