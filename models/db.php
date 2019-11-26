<?php

class DB
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function select_all_get($tablename)
    {
        $query = "SELECT * FROM  $tablename ";
        // die($query);
        $result = $this->db->execute($query);
        // hitung result
        $num = $result->rowCount();

        // jika ada hasil
        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                array_push($data_arr, json_decode($value));
            }

            $data_item = array(
                'id' => $id,
                'value' => $data_arr,
            );

            // Turn to JSON
        } else {
            echo json_encode(
                array('message' => 'data not found')
            );
        }

        return $data_arr;
    }

    public function select_id_get($id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . " ";
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
                    'value' => json_decode($value),
                );

                array_push($data_arr, $data_item);
            }

            // $data_item is array of data_arr
            // $data_item = array(

            //     'value' => $data_arr,
            // );
            // Turn to JSON
            // echo json_encode($data_item);
        }
        // jika tidak ada hasil
        else {
            echo json_encode(
                array('message' => 'data not found')
            );
        }

        return $data_item;
    }

    public function select_where_get($attr, $val, $tablename)
    {

        $query = "SELECT value FROM $tablename";
        $value = explode('AND', $val);
        $attr = explode('AND', $attr);
        for ($i = 0; $i < count($attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "value @> '{\"" . $attr[$i] . "\": \"" . $value[$i] . "\"}'";

        }

        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        $result = $this->db->execute($query_real);

        $num = $result->rowCount();

        if ($num > 0) {
            $arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                array_push($arr, json_decode($value));
            }

            $item = array(
                'value' => $arr,
            );

            // echo json_encode($item);
        } else {
            echo json_encode(
                array('message' => 'data tidak ditemukan')
            );
        }
        return $item;
    }

    public function select_or_where_get($attr, $val, $tablename)
    {
        // find query
        $query = "SELECT value FROM $tablename ";
        $value = explode('OR', $val);
        $attr = explode('OR', $attr);
        for ($i = 0; $i < count($attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " OR ";
            }
            $query .= "value @> '{\"" . $attr[$i] . "\": \"" . $value[$i] . "\"}'";

        }

        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        $result = $this->db->execute($query_real);

        $num = $result->rowCount();

        if ($num > 0) {
            $arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                array_push($arr, json_decode($value));
            }

            $item = array(
                'value' => $arr,
            );

            // echo json_encode($item);
        } else {
            echo json_encode(
                array('message' => 'data tidak ditemukan')
            );
        }
        return $item;
    }

    public function select_where_like_get($attr, $val, $tablename)
    {

        $query = "SELECT value FROM $tablename ";

        $query .= "value @> '{\"" . $attr . "\": \"" . $val . "\"}'";

        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        $result = $this->db->execute($query_real);

        $num = $result->rowCount();

        if ($num > 0) {
            $arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                array_push($arr, json_decode($value));
            }

            $item = array(
                'value' => $arr,
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
        // $data = file_get_contents("php://input");

        $data = $_POST['data'];
        $query = 'INSERT INTO ' . $tablename . ' (values) ';
        $query .= "VALUES ('$data')";
        // die($query);
        return $this->db->execute($query);
    }

    public function update_all($attr, $val, $tablename)
    {
        // init value baru
        $this->value = $_POST["value"];

        $condition_value = explode('AND', $val);
        $condition_attr = explode('AND', $attr);
        $query = "UPDATE  $tablename  SET value = '$this->value'";
        for ($i = 0; $i < count($condition_attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "value @> '{\"" . $condition_attr[$i] . "\": \"" . $condition_value[$i] . "\"}'";

        }
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        return $this->db->execute($query_real);
    }

    public function update_id($id, $tablename)
    {
        // init attribute dan value
        $this->attr = $_POST["attr"];
        $this->value = $_POST["value"];

        $query = "UPDATE $tablename SET value = value || '{\"" . $this->attr . "\":\"" . $this->value . "\"}' AND id = $id";

        // die($query);

        return $this->db->execute($query);
    }

    public function update_where($attr, $val, $tablename)
    {
        $this->attr = $_POST["attr"];
        $this->value = $_POST["value"];

        $condition_value = explode('AND', $val);
        $condition_attr = explode('AND', $attr);

        $query = "UPDATE $tablename SET value = value || '{\"" . $this->attr . "\":\"" . $this->value . "\"}' AND ";
        for ($i = 0; $i < count($condition_attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "value @> '{\"" . $condition_attr[$i] . "\": \"" . $condition_value[$i] . "\"}'";

        }
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);

        return $this->db->execute($query_real);
    }

    public function delete_all_get($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id . " ";

        return $this->db->execute($query);
    }

    public function delete_where_get($attr, $val, $tablename)
    {

        $condition_value = explode('AND', $val);
        $condition_attr = explode('AND', $attr);
        $query = "DELETE FROM  $tablename";
        for ($i = 0; $i < count($condition_attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " AND ";
            }
            $query .= "value @> '{\"" . $condition_attr[$i] . "\": \"" . $condition_value[$i] . "\"}'";

        }
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);
        return $this->db->execute($query_real);
    }

    public function delete_value_on_attribute($id, $attr, $tablename)
    {

        $query = "UPDATE $tablename SET value = value || '{";
        $condition_attr = explode('AND', $attr);
        for ($i = 0; $i < count($condition_attr); $i++) {

            if ($i == 0) {

            } else {
                $query .= ",";
            }
            $query .= "\"$condition_attr[$i]\" : \"\"";

        }
        $query .= " }' WHERE id = " . $id . " ";
        $query_real = str_replace("%20", " ", $query);
        // die($query_real);

        return $this->db->execute($query_real);
    }

    public function delete_attr_by_id($id, $attr, $tablename)
    {
        $query = "UPDATE $tablename SET value = value - '" . $attr . "' WHERE id = " . $id . " ";

        return $this->db->execute($query);
    }
}
