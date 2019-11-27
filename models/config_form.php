<?php

class ConfigForm
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
                    'form_name' => $form_name,
                    'form_type_submit' => $form_type_submit,
                    'data_cfg' => json_decode($form_config),
                    'object_id' => $object_id,
                    'object_name' => $object_name,
                    'object_table' => $object_table,

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
        $query = "SELECT * FROM $tablename  WHERE id = '$id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'form_type_submit' => $form_type_submit,
            'form_name' => $form_name,
            'data_cfg' => json_decode($form_config),
            'object_id' => $object_id,
            'object_name' => $object_name,
            'object_table' => $object_table,

        );

        return $data_item;
    }

    public function insertLayout($tablename, $id)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //

        $data_form = $data;

        // $form_id = $request[0]->form_id;
        // $form_name = $request[0]->form_name;
        // $query = "INSERT INTO $tablename (form_id, form_name, form_config)";
        // $query .= " VALUES ($form_id , '$form_name', '$form_config')";
        // $implode = implode(" ", $data);

        $query = "UPDATE  $tablename SET form_config = '$data_form' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);

    }

    public function insertFormData($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $form_type_submit = $request[0]->type;
        $form_name = $request[0]->name;
        $object_id = $request[0]->object_id;
        $object_name = $request[0]->object_name;
        $object_table = $request[0]->object_table;
        $query = "INSERT INTO $tablename (form_type_submit, form_name, object_id, object_name, object_table)";
        $query .= " VALUES ('$form_type_submit' , '$form_name', $object_id, '$object_name', '$object_table')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        // $request = json_decode($data);
        // // die(json_encode($request));
        // $form_id = $request->form_id;
        // $form_name = $request->form_name;
        // $form_config = $request->form_config;

        $data_form = $data;
        // $query = "UPDATE " . $tablename . " SET form_config = '" . $form_config . "', form_id = '" . $form_id . "', form_name = '" . $form_name . "'" . " WHERE id = " . $id;
        $query = "UPDATE  $tablename SET form_config = '$data_form' WHERE id = $id";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename  WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }
}
