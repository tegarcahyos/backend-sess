<?php

class PageData
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
                    'data' => json_decode($data),

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
            'data' => json_decode($data),
        );
        return $data_item;
    }

    public function select_where_get($attr, $val, $tablename)
    {

        $query = "SELECT values FROM $tablename WHERE ";
        $values = explode('AND', $val);
        $attr = explode('AND', $attr);
        for ($i = 0; $i < count($attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "values @> '{\"" . $attr[$i] . "\": \"" . $values[$i] . "\"}'";

        }

        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        $result = $this->db->execute($query_real);

        $num = $result->rowCount();

        if ($num > 0) {
            $arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                array_push($arr, json_decode($values));
            }

            $item = array(
                'data' => $arr,
            );

            // echo json_encode($item);
        } else {
            echo json_encode(
                array('message' => 'data tidak ditemukan')
            );
        }
        return $item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");

        $query = "INSERT INTO $tablename (data)";
        $query .= " VALUES ('$data')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        // die(json_encode($request));
        $data = $request->data;

        $query = "UPDATE $tablename SET data = '$data' WHERE id = " . $id;
        // die($query);
        return $this->db->execute($query);
    }

    public function update_where($attr, $val, $tablename)
    {
        $data = file_get_contents("php://input");

        $condition_values = explode('AND', $val);
        $condition_attr = explode('AND', $attr);

        $query = "UPDATE $tablename SET values = '$data' ";
        for ($i = 0; $i < count($condition_attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "values @> '{\"" . $condition_attr[$i] . "\": \"" . $condition_values[$i] . "\"}'";

        }
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);

        return $this->db->execute($query_real);
    }

    public function delete($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
        // die($query);
        return $this->db->execute($query);
    }
}
