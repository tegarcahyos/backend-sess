<?php

class FormPage
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
                    'form_id' => $form_id,
                    'form_name' => $form_name,
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
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'form_id' => $form_id,
                'form_name' => $form_name,
                'app_id' => $app_id,
                'app_name' => $app_name,

            );
            return $data_item;
        }
    }

    public function findByAppId($app_id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE app_id = ' . $app_id . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'name' => $name,
            );
            return $data_item;
        }
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        // die(json_encode($request));
        $form_id = $request[0]->form_id;
        $form_name = $request[0]->form_name;
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;

        $query = "INSERT INTO $tablename (form_id, form_name, form_code, page_id, page_name)";
        $query .= "VALUES ($form_id , '$form_name', $page_id , '$page_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $form_id = $request->form_id;
        $form_name = $request->form_name;
        $page_id = $request[0]->page_id;
        $page_name = $request[0]->page_name;

        $query = "UPDATE " . $tablename . " SET page_name = '" . $page_name . "', page_id = '" . $page_id . "', form_id = '" . $form_id . "', form_name = '" . $form_name . " WHERE id = " . $id;
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = 'DELETE FROM ' . $tablename . ' WHERE id = ' . $id;
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
