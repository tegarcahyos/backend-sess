<?php

class ConfigTable
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
                    'type_table' => $type_table,
                    'object_id' => $object_id,
                    'object_name' => $object_name,
                    'object_table' => $object_table,
                    'selected_data' => $selected_data,
                    'form_id' => $form_id,
                    'selected_button_action' => $selected_button_action,
                    'detail_page_id' => $detail_page_id,
                    'page_id' => $page_id,
                    'view_thumbnail' => $view_thumbnail,
                    'sort_attribute_by' => $sort_attribute_by,
                    'type_sort' => $type_sort,
                    'filter_data_by' => $filter_data_by,
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
                'name' => $name,
                'type_table' => $type_table,
                'object_id' => $object_id,
                'object_name' => $object_name,
                'object_table' => $object_table,
                'selected_data' => $selected_data,
                'form_id' => $form_id,
                'selected_button_action' => $selected_button_action,
                'detail_page_id' => $detail_page_id,
                'page_id' => $page_id,
                'view_thumbnail' => $view_thumbnail,
                'sort_attribute_by' => $sort_attribute_by,
                'type_sort' => $type_sort,
                'filter_data_by' => $filter_data_by,
            );
            return $data_item;
        }
    }

    public function getLayout($id_object)
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
        // die(print_r($request));
        $name = $request[0]->name;
        $type_table = $request[0]->type_table;
        $object_id = $request[0]->object_id;
        $object_name = $request[0]->object_name;
        $object_table = $request[0]->object_table;
        $selected_data = json_encode($request[0]->selected_data);
        $sort_attribute_by = json_encode($request[0]->sort_attribute_by);
        $type_sort = json_encode($request[0]->type_sort);
        $filter_data_by = json_encode($request[0]->filter_data_by);
        $form_id = $request[0]->form_id;
        $selected_button_action = json_encode($request[0]->selected_button_action);
        $detail_page_id = $request[0]->detail_page_id;
        $page_id = $request[0]->page_id;
        if (empty($object_id)) {
            $object_id = 'NULL';
            $object_name = 'NULL';
            $object_table = 'NULL';
        }
        if (empty($page_id)) {
            $page_id = 'NULL';
        }
        $query = "INSERT INTO $tablename (name, type_table, object_id, object_name, object_table, selected_data, sort_attribute_by, type_sort, filter_data_by, form_id, selected_button_action, detail_page_id, page_id)";
        $query .= "VALUES ('$name' , $type_table, NULLIF('$object_id','NULL'), NULLIF('$object_name','NULL'), NULLIF('$object_table','NULL'), '$selected_data', NULLIF('$sort_attribute_by','NULL'), NULLIF('$type_sort', 'NULL'), NULLIF('$filter_data_by', 'NULL'), '$form_id', '$selected_button_action', '$detail_page_id', NULLIF('$page_id', 'NULL'))";
        // die($query);
        $result = $this->db->execute($query);
        return $result;

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        $name = $request[0]->name;
        $type_table = $request[0]->type_table;
        $object_id = $request[0]->object_id;
        $object_name = $request[0]->object_name;
        $object_table = $request[0]->object_table;
        $selected_data = json_encode($request[0]->selected_data);
        $sort_attribute_by = json_encode($request[0]->sort_attribute_by);
        $type_sort = json_encode($request[0]->type_sort);
        $filter_data_by = json_encode($request[0]->filter_data_by);
        $detail_page_id = $request[0]->detail_page_id;
        $form_id = $request[0]->form_id;
        $selected_button_action = json_encode($request[0]->selected_button_action);
        $detail_page_id = $request[0]->detail_page_id;
        $view_thumbnail = $request[0]->view_thumbnail;
        $page_id = $request[0]->page_id;
        if (empty($page_id)) {
            $page_id = 'NULL';
        }

        $query = "UPDATE $tablename SET type_table = $type_table, object_name = '$object_name', name = '$name', object_id = $object_id, object_table = '$object_table', selected_data = '$selected_data', sort_attribute_by = '$sort_attribute_by', type_sort = '$type_sort', filter_data_by = '$filter_data_by', form_id = '$form_id', selected_button_action = '$selected_button_action', detail_page_id ='$detail_page_id', view_thumbnail = '$view_thumbnail', page_id = NULLIF('$page_id', 'NULL') WHERE id =  '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
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
