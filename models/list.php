<?php

class Lists
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
                    'type_list' => $type_list,
                    'object_id' => $object_id,
                    'object_name' => $object_name,
                    'selected_data' => $selected_data,

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
            'type_list' => $type_list,
            'object_id' => $object_id,
            'object_name' => $object_name,
            'selected_data' => $selected_data,

        );
        return $data_item;
    }

    // public function findByAppId($object_name, $tablename)
    // {
    //     $query = 'SELECT * FROM ' . $tablename . ' WHERE object_name = ' . $object_name . "";
    //     $result = $this->db->execute($query);
    //     $row = $result->fetchRow();
    //     extract($row);

    //     $data_item = array(
    //         'id' => $id,
    //         'name' => $name,
    //         'type_table' => $type_table,
    //         'object_id' => $object_id,
    //         'object_name' => $object_name,
    //         'selected_data' => $selected_data,
    //     );
    //     return $data_item;
    // }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        $name = $request[0]->name;
        $type_list = $request[0]->type_list;
        $object_id = $request[0]->object_id;
        $object_name = $request[0]->object_name;
        $selected_data = $request[0]->selected_data;

        $query = "INSERT INTO $tablename (name, type_list, object_id, object_name, selected_data)";
        $query .= "VALUES ($name , $type_list, '$object_id', $object_name , '$selected_data')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $name = $request[0]->name;
        $type_list = $request[0]->type_list;
        $object_id = $request[0]->object_id;
        $object_name = $request[0]->object_name;
        $selected_data = $request[0]->selected_data;

        $query = "UPDATE " . $tablename . " SET type_list = '" . $type_list . "', object_name = '" . $object_name . "', name = '" . $name . "', object_id = '" . $object_id . "', selected_data = '" . $selected_data . " WHERE id = " . $id;
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
