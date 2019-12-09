<?php

class App
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
                    'code' => $code,
                    'page_data' => $page_data,
                    'properties' => $properties,
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
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'name' => $name,
            'code' => $code,
            'page_data' => $page_data,
            'properties' => $properties,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        $name = $request[0]->name;
        $code = $request[0]->code;
        $properties = $request[0]->properties;

        $query = "INSERT INTO $tablename (name, code, properties)";
        $query .= " VALUES ('$name', '$code', $properties)";
        // die($query);
        return $this->db->execute($query);

    }

    public function addPage($id, $tablename)
    {
        $data = file_get_contents("php://input");
        //
        // $request = json_decode($data);
        // die(json_encode($request))
        $page_data = $data;
        $query = "UPDATE $tablename  SET page_data = '$page_data' WHERE id = $id";
        // die($query);
        return $this->db->execute($query);
    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        die(print_r($request));
        // die(json_encode($request));
        $name = $request[0]->name;
        $code = $request[0]->code;
        $properties = $request[0]->properties;

        $query = "UPDATE $tablename SET name = '$name', code = '$code', properties = $properties WHERE id =  $id";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
        // die($query);
        return $this->db->execute($query);
    }
}
