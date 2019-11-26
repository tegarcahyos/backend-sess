<?php

class Objects
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
                    'attribute' => $attribute,
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
        // die($query);
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'name' => $name,
            'attribute' => $attribute,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        
        $name = $request[0]->name;
        $attribute = json_encode($request[0]->attribute);
        $tbl_name = $request[0]->name;
        $tbl_name = strtolower($tbl_name);
        $tbl_name = str_replace(" ", "_", $tbl_name);
        // implode("," ,$request[0]->attribute);
        $query = "INSERT INTO $tablename (name, attribute, tbl_name)";
        $query .= "VALUES ('$name', '$attribute', '$tbl_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function create_table()
    {
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        // $name = $request[0]->name;
        $name = $request[0]->name;
        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query = "CREATE TABLE data_$name(
          id serial PRIMARY KEY,
          values jsonb
       );";
        return $this->db->execute($query);
    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;
        $attribute = $request[0]->attribute;

        $query = "UPDATE " . $tablename . " SET name = '" . $name . "', attribute = '". $attribute . "' WHERE id = " . $id;
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
