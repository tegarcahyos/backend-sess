<?php

class ConfigList
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
                    'object_table' => $object_table,
                    'selected_data' => $selected_data,
                    'detail_page_id' => $detail_page_id,
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
        $query = "SELECT * FROM $tablename WHERE id = '$id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'name' => $name,
            'type_list' => $type_list,
            'object_id' => $object_id,
            'object_name' => $object_name,
            'object_table' => $object_table,
            'selected_data' => $selected_data,
            'detail_page_id' => $detail_page_id,
        );
        return $data_item;
    }

    public function get_layout($id_object)
    {
        $query = "SELECT * FROM config_form_layout WHERE object_id = $id_object";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_layout = array(
                    'id' => $id,
                    'form_type_submit' => $form_type_submit,
                    'name' => $name,
                    'data_cfg' => json_decode($form_config),
                    'object_id' => $object_id,
                    'object_name' => $object_name,
                    'object_table' => $object_table,

                );

                array_push($data_arr, $data_layout);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

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
        if (empty($object_id)) {
            $object_id = null;
        }
        $page_id = $request[0]->page_id;
        if (empty($page_id)) {
            $page_id = null;
        }
        $selected_data = json_encode($request[0]->selected_data);
        $detail_page_id = $request[0]->detail_page_id;

        $query = "INSERT INTO $tablename (name, type_list, object_id, object_name, object_table, page_id, selected_data, detail_page_id)";
        $query .= "VALUES ('$name' , $type_list, $object_id, '$page_id', '$selected_data','$detail_page_id')";
        die($query);
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
        $object_table = $request[0]->object_table;
        $page_id = $request[0]->page_id;
        $selected_data = json_encode($request[0]->selected_data);
        $detail_page_id = $request[0]->detail_page_id;

        $query = "UPDATE $tablename SET type_list = '$type_list', object_name = '$object_name', name = '$name', object_id = $object_id, page_id = $page_id, object_table = '$object_table', selected_data = '$selected_data', detail_page_id ='$detail_page_id' WHERE id =  '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }
}
