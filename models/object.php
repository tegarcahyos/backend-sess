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
                    'tbl_name' => $tbl_name,
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
            'tbl_name' => $tbl_name,
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
        $query .= "VALUES ('$name', '$attribute', 'data_$tbl_name')";
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

    public function updateObject($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $query_select = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query_select);
        $row = $result->fetchRow();
        extract($row);
        $tbl_name = $row["tbl_name"] ?? null;
        $name = $row["name"] ?? null;
        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query_alter = "ALTER TABLE $tbl_name RENAME TO data_$name";
        die($query_alter);
        $this->db->execute($query_alter);

        $name = $request[0]->name;
        $attribute = json_encode($request[0]->attribute);
        $tbl_name = $request[0]->name;
        $tbl_name = strtolower($tbl_name);
        $tbl_name = str_replace(" ", "_", $tbl_name);

        $query_update = "UPDATE " . $tablename . " SET name = '" . $name . "', attribute = '" . $attribute . "', tbl_name = '" . $tbl_name . "' WHERE id = " . $id;
        die($query_update);
        return $this->db->execute($query_update);
    }

    public function delete($id, $tablename)
    {
        $query1 = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query1);
        $row = $result->fetchRow();
        extract($row);
        $tbl_name = $row["tbl_name"] ?? null;
        $name = $row["name"] ?? null;
        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query = "ALTER TABLE $tbl_name RENAME TO bug_$name";
        $this->db->execute($query);

        $query2 = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
        // die($query);
        return $this->db->execute($query2);
    }

    public function delete_table($id, $tablename)
    {
        $query1 = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query1);
        $row = $result->fetchRow();
        extract($row);
        $tbl_name = $row["tbl_name"] ?? null;
        $name = $row["name"] ?? null;
        $name = strtolower($name);
        $name = str_replace(" ", "_", $name);
        $query = "ALTER TABLE $tbl_name RENAME TO bug_$name";
        return $this->db->execute($query);
    }
}
