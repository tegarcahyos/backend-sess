<?php

class ConfigAlignment
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
                    'name' => $name,
                    'alignment' => json_decode($alignment),

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
        $query = "SELECT * FROM $tablename  WHERE id = '$id'";
        $handle = $this->db->prepare($query);
        $result = $this->db->execute($handle);
        if (empty($result)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            $row = $result->fetchRow();
            extract($row);

            $data_item = array(
                'id' => $id,
                'name' => $name,
                'alignment' => json_decode($alignment),

            );

            return $data_item;
        }
    }

    public function insertAlignData($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(print_r($data));
        // die(json_decode($data));
        $name = $request[0]->name;
        $alignment = $request[0]->alignment;
        // $alignment = json_encode($request[0]->data);
        $query = "INSERT INTO $tablename (name, alignment)";
        $query .= " VALUES ('$name', '$alignment') RETURNING id";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        // jika ada hasil
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

        return $msg;

    }

    public function insertData($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $alignment = $data;
        $query = "UPDATE  $tablename SET alignment = '$alignment' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        // // die(json_encode($request));
        $request = json_decode($data);
        // die(print_r($data));
        // die(json_decode($data));
        $name = $request[0]->name;
        $alignment = $request[0]->alignment;
        // $alignment = json_encode($request[0]->data);
        $query = "UPDATE  $tablename SET name = '$name', alignment = '$alignment' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename  WHERE id = '$id'";
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
