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
        // $name = $request[0]->name;
        $name = $_POST['name'];

        $query = "INSERT INTO $tablename (name)";
        $query .= "VALUES ('$name')";
        // die($query);
        $this->db->execute($query);

        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query2 = "CREATE TABLE data_$name(
          id serial PRIMARY KEY,
          values jsonb,
       )";

        return $this->db->execute($query2);

    }

    public function create_table()
    {
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        // $name = $request[0]->name;
        $name = $_POST['name'];
        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query = "CREATE TABLE data_$name(
          id serial PRIMARY KEY,
          values jsonb,
       )";

        return $this->db->execute($query);
    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;

        $query = "UPDATE " . $tablename . " SET name = '" . $name . "'" . " WHERE id = " . $id;
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
