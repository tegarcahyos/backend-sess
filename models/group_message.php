<?php

class GroupMessage
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
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,

                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function select_id($id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,
                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function select_group_id($group_id, $tablename)
    {
        $query = "SELECT * FROM  $tablename WHERE group_id = $group_id ORDER BY id ASC";
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,

                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function status_read($group_id, $tablename)
    {
        $query = "SELECT * FROM  $tablename WHERE group_id = $group_id AND status_read = false ORDER BY id ASC";
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,

                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function select_user_id($user_id, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE user_id = $user_id ORDER BY id";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,
                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        // print_r($data);
        //
        $request = json_decode($data);

        $group_id = $request->group_id;
        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $message = $request->message;
        $create_date = $request->create_date;
        $create_time = $request->create_time;
        $status_origin = $request->status_origin;
        $status_read = $request->status_read;
        $status_removed = $request->status_removed;

        $query = "INSERT INTO $tablename (group_id,user_id,user_name,message,create_date,create_time,status_origin,status_read,status_removed)";
        $query .= "VALUES ($group_id,$user_id,'$user_name','$message','$create_date','$create_time','$status_origin','$status_read','$status_removed') RETURNING * ";
        // die($query);

        $returning_value = $this->db->execute($query);
        $num = $returning_value->rowCount();
        // echo ($num);

        if ($num > 0) {

            $data_arr = array();

            while ($row = $returning_value->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'message' => $message,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                    'status_origin' => $status_origin,
                    'status_read' => $status_read,
                    'status_removed' => $status_removed,

                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = '0';
        }
        return $msg;

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $group_id = $request->group_id;
        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $message = $request->message;
        $create_date = $request->create_date;
        $create_time = $request->create_time;
        $status_origin = $request->status_origin;
        $status_read = $request->status_read;
        $status_removed = $request->status_removed;

        $query = "UPDATE  $tablename SET group_id = $group_id, user_id = $user_id, user_name = '$user_name', message = '$message',
                                         create_date = '$create_date',create_time = '$create_time',
                                         status_origin = '$status_origin', status_read = '$status_read', status_removed = '$status_removed'  WHERE id =  $id";
        $this->db->execute($query);

        $select_query = $this->select_id($id, $tablename);

        $data_arr = array();
        array_push($data_arr, $select_query);
        $msg = $data_arr;
        // die($query);
        return $msg;
    }

    public function delete($id, $tablename)
    {
        $query = " DELETE FROM  $tablename  WHERE id =  $id";
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
