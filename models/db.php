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

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'values' => json_decode($values),
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
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
                    'values' => json_decode($values),
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
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
                'values' => $arr,
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
        $query = "SELECT values FROM $tablename WHERE ";
        $values = explode('OR', $val);
        $attr = explode('OR', $attr);
        for ($i = 0; $i < count($attr); $i++) {
            if ($i == 0) {

            } else {
                $query .= " OR ";
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
                'values' => $arr,
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

        $query = "SELECT values FROM $tablename WHERE ";

        $query .= "values @> '{\"" . $attr . "\": \"" . $val . "\"}'";

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
                'values' => $arr,
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
        $data = file_get_contents("php://input");
        //
        $query = 'INSERT INTO ' . $tablename . ' (values) ';
        $query .= "VALUES ('$data')";
        // die($query); 
        $this->db->execute($query);
        $lastId = $this->db->insert_Id($tablename, 'id');
        $select = "SELECT * FROM $tablename WHERE id = $lastId";

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
                    'values' => json_decode($values),
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;

    }

    public function update_all($attr, $val, $tablename)
    {
        // init values baru
        $this->values = $_POST["values"];

        $condition_values = explode('AND', $val);
        $condition_attr = explode('AND', $attr);
        $query = "UPDATE  $tablename  SET values = '$this->values'";
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

    public function update_id($id, $tablename)
    {
        // init attribute dan values
        $this->attr = $_POST["attr"];
        $this->values = $_POST["values"];

        $query = "UPDATE $tablename SET values = values || '{\"" . $this->attr . "\":\"" . $this->values . "\"}' AND id = $id";

        // die($query);

        return $this->db->execute($query);
    }

    public function update_where($attr, $val, $tablename)
    {
        $this->attr = $_POST["attr"];
        $this->values = $_POST["values"];

        $condition_values = explode('AND', $val);
        $condition_attr = explode('AND', $attr);

        $query = "UPDATE $tablename SET values = values || '{\"" . $this->attr . "\":\"" . $this->values . "\"}' AND ";
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

    public function delete_all_get($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id . " ";

        return $this->db->execute($query);
    }

    public function delete_where_get($attr, $val, $tablename)
    {

        $condition_values = explode('AND', $val);
        $condition_attr = explode('AND', $attr);
        $query = "DELETE FROM  $tablename";
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

    public function delete_values_on_attribute($id, $attr, $tablename)
    {

        $query = "UPDATE $tablename SET values = values || '{";
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
        $query = "UPDATE $tablename SET values = values - '" . $attr . "' WHERE id = " . $id . " ";

        return $this->db->execute($query);
    }
}
