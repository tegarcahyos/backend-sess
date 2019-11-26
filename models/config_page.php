<?php

class ConfigPage
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
                    'page_config' => $page_config,
                    'page_name' => $page_name,
                    'app_id' => $app_id,
                    'app_name' => $app_name,

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
        $query = "SELECT * FROM  $tablename  WHERE id = '$id'";
        die($query);
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'page_config' => $page_config,
            'page_name' => $page_name,
            'app_id' => $app_id,
            'app_name' => $app_name,

        );
        return $data_item;
    }

    public function insertLayout($tablename, $id)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //

        $data_page = $data;

        $query = "UPDATE  $tablename SET page_config = '$data_page' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);

    }

    public function insertPageData($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $app_id = $request[0]->app_id;
        $app_name = $request[0]->app_name;
        $page_name = $request[0]->name;
        $query = "INSERT INTO $tablename (app_id, app_name, page_name)";
        $query .= " VALUES ($app_id , '$app_name', '$page_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        $page_name = $request->name;
        $app_id = $request->app_id;
        $app_name = $request->app_name;
        $query = "UPDATE $tablename SET page_name = '$page_name', app_id = $app_id, app_name = '$app_name' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id =  '$id'";
        // die($query);
        return $this->db->execute($query);
    }
}
