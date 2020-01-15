<?php

class GroupMember
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
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name ' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,

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
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name ' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,
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
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,
                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function select_unit_id($unit_id, $tablename)
    {
        $query = "SELECT * FROM  $tablename WHERE unit_id = $unit_id ORDER BY id ASC";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,
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
        $query = "SELECT * FROM $tablename WHERE user_id = $user_id";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,
                );
                array_push($data_arr, $data_item);

                $msg = $data_arr;
            }
        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function select_push_id($push_id, $tablename)
    {
        $query = "SELECT * FROM $tablename  WHERE push_id = $push_id";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);
                $data_item = array(
                    'id' => $id,
                    'group_id' => $group_id,
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,
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
        $group_name = $request->group_name;
        $unit_id = $request->unit_id;
        $unit_name = $request->unit_name;
        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $user_avatar = $request->user_avatar;
        $type = $request->type;
        $push_id = $request->push_id;

        $query = "INSERT INTO $tablename (group_id, group_name, unit_id, unit_name, user_id, user_name, user_avatar, type, push_id)";
        $query .= "VALUES ($group_id, '$group_name', $unit_id, '$unit_name', $user_id,'$user_name', '$user_avatar', '$type', $push_id) RETURNING * ";
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
                    'group_name' => $group_name,
                    'unit_id' => $unit_id,
                    'unit_name' => $unit_name,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'type' => $type,
                    'push_id' => $push_id,

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

        $query = "UPDATE  $tablename SET title = '$title', message_last = '$message_last' ,message_last_date = '$message_last_date',
                                         message_last_time = '$message_last_time',message_sender_id = $message_sender_id ,
                                         message_sender_name = '$message_sender_name',last_seen_date = '$last_seen_date',
                                         last_seen_time = '$last_seen_time',last_seen_user_id = $last_seen_user_id,
                                         last_seen_user_name = '$last_seen_user_name'  WHERE id =  $id";
        $this->db->execute($query);
        $select_query = $this->findById($id, $tablename);
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
