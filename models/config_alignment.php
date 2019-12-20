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
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'name' => $name,
            'alignment' => json_decode($alignment),

        );

        return $data_item;
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
        // $alignment = json_encode($request[0]->data);
        $query = "INSERT INTO $tablename (name)";
        $query .= " VALUES ('$name')";
        // die($query);
        $this->db->execute($query);
        $lastId = $this->db->insert_Id($tablename, 'id');
        $select = "SELECT * FROM $tablename WHERE id = '$lastId'";

        $result = $this->db->execute($select);
        $num = $result->rowCount();

        // jika ada hasil
        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                // Push to data_arr

                $data_item = array(
                    'id' => $id,
                    'name' => $name,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;



    }

    public function insertData($tablename, $id)
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
        // $alignment = json_encode($request[0]->data);
        $query = "UPDATE  $tablename SET name = '$name' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename  WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }
}
