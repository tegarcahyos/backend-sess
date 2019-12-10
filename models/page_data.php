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
                    'page_id' => $page_id,
                    'page_name' => $page_name,
                    'data' => $data,

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
            'page_id' => $page_id,
            'page_name' => $page_name,
            'data' => $data,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;
        $data = $request[0]->data[0];
        $data = json_encode($data);
        $query = "INSERT INTO $tablename (page_id, page_name, data)";
        $query .= " VALUES ('$page_id','$page_name',$data)";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;
        $data = $request[0]->data[0]->data;
        $query = "UPDATE $tablename SET data = $data, page_id = '$page_id', page_name = '$page_name' WHERE id = " . $id;
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
