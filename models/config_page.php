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
                    'page_type' => $page_type,

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
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            $row = $result->fetchRow();
            extract($row);

            $data_item = array(
                'id' => $id,
                'page_config' => $page_config,
                'page_name' => $page_name,
                'page_type' => $page_type,

            );
            return $data_item;
        }
    }

    public function insertLayout($id, $tablename)
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

        $page_name = $request[0]->name;
        $page_type = $request[0]->type;
        $query = "INSERT INTO $tablename (page_name, page_type)";
        $query .= " VALUES ('$page_name', '$page_type')";
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
        $page_type = $request->type;
        $query = "UPDATE $tablename SET page_name = '$page_name', page_type = '$page_type' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id =  '$id'";
        // die($query);
        $result = $this->db->execute($query);
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
    }
}
