<?php

class ConfigGann
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
                    'task' => json_decode($task),

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
            'task' => json_decode($task),

        );

        return $data_item;
    }

    public function insertGann($tablename)
    {
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
        return $this->db->execute($query);

        // die($query);
        return $this->db->execute($query);

    }

    public function insertGannData($tablename, $id)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        // die(print_r($data));
        // die(json_decode($data));
        $task = $data;
        $query = "UPDATE  $tablename SET task = '$task' WHERE id = $id";
        // die($query);
        return $this->db->execute($query);

    }

    public function updateData($id, $tablename)
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
