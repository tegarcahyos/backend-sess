<?php

class GroupChat
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
                    'title' => $title,
                    'message_last' => $message_last,
                    'message_last_date' => $message_last_date,
                    'message_last_time' => $message_last_time,
                    'message_sender_id' => $message_sender_id,
                    'message_sender_name ' => $message_sender_name,
                    'last_seen_date' => $last_seen_date,
                    'last_seen_time' => $last_seen_time,
                    'last_seen_user_id' => $last_seen_user_id,
                    'last_seen_user_name' => $last_seen_user_name,
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
        $query = "SELECT * FROM  $tablename  WHERE id = $id ";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'title' => $title,
                    'message_last' => $message_last,
                    'message_last_date' => $message_last_date,
                    'message_last_time' => $message_last_time,
                    'message_sender_id' => $message_sender_id,
                    'message_sender_name ' => $message_sender_name,
                    'last_seen_date' => $last_seen_date,
                    'last_seen_time' => $last_seen_time,
                    'last_seen_user_id' => $last_seen_user_id,
                    'last_seen_user_name' => $last_seen_user_name,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }
    public function findByTitle($title, $tablename)
    {
        $query = "SELECT * FROM  $tablename  WHERE title ilike '%$title%' ";
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'title' => $title,
                    'message_last' => $message_last,
                    'message_last_date' => $message_last_date,
                    'message_last_time' => $message_last_time,
                    'message_sender_id' => $message_sender_id,
                    'message_sender_name ' => $message_sender_name,
                    'last_seen_date' => $last_seen_date,
                    'last_seen_time' => $last_seen_time,
                    'last_seen_user_id' => $last_seen_user_id,
                    'last_seen_user_name' => $last_seen_user_name,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }
    public function join_chat($user_id, $group_id, $group_chat, $group_member)
    {
        $query_count = "SELECT group_id, count(user_id)  from group_member where group_id in $group_id group by group_id HAVING count(user_id) = 2";

        $result_count = $this->db->execute($query_count);

        $num = $result_count->rowCount();

        $data_arr = "";
        if ($num > 0) {

            $data_arr .= "(";
            $i = 0;
            while ($rows = $result_count->fetchRow()) {
                extract($rows);

                $data_arr .= $group_id;
                if ($i < $num - 1) {
                    $data_arr .= ",";
                };
                $i++;

            }
            $data_arr .= ")";

            // echo $data_arr;

            $query = "SELECT *  FROM $group_chat INNER JOIN $group_member " .
                "ON  $group_chat.id = $group_member.group_id " .
                "where  $group_member.user_id != '$user_id' and $group_chat.id in $data_arr";
            // die($query);

            $result = $this->db->execute($query);

            $num = $result->rowCount();

            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'user_id' => $user_id,
                        'group_id' => $group_id,
                        'user_name' => $user_name,
                        'message_last' => $message_last,
                        'message_last_time' => $message_last_time,
                        'user_avatar' => $user_avatar,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            } else {
                $msg = '0';
            }

        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function join_group_chat($user_id, $group_id, $group_chat, $group_member)
    {
        $query_count = "SELECT group_id, count(user_id)  from group_member where group_id in $group_id group by group_id HAVING count(user_id) > 2";

        $result_count = $this->db->execute($query_count);
        // echo $result_count;
        $num = $result_count->rowCount();
        // echo $num;
        $data_arr = "";
        if ($num > 0) {
            // echo "assup";
            $data_arr .= "(";
            $i = 0;
            while ($rows = $result_count->fetchRow()) {
                extract($rows);
                // echo $group_id;
                $data_arr .= $group_id;
                if ($i < $num - 1) {
                    $data_arr .= ",";
                };
                $i++;

            }
            $data_arr .= ")";

            if ($data_arr != "") {

                $query = "SELECT *  FROM $group_chat where $group_chat.id in $data_arr";
                // die($query);

                $result = $this->db->execute($query);
                $num = $result->rowCount();

                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        $data_item = array(
                            'id' => $id,
                            'title' => $title,
                            'message_last' => $message_last,
                            'message_last_date' => $message_last_date,
                            'message_last_time' => $message_last_time,
                            'message_sender_id' => $message_sender_id,
                            'message_sender_name ' => $message_sender_name,
                            'last_seen_date' => $last_seen_date,
                            'last_seen_time' => $last_seen_time,
                            'last_seen_user_id' => $last_seen_user_id,
                            'last_seen_user_name' => $last_seen_user_name,
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }

                } else {
                    $msg = '0';
                }

            } else {
                $msg = '0';
            }

            // echo $data_arr;
            // return $msg;

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

        $title = $request->title;
        $message_last = $request->message_last;
        $message_last_date = $request->message_last_date;
        $message_last_time = $request->message_last_time;
        $message_sender_id = $request->message_sender_id;
        $message_sender_name = $request->message_sender_name;
        $last_seen_date = $request->last_seen_date;
        $last_seen_time = $request->last_seen_time;
        $last_seen_user_id = $request->last_seen_user_id;
        $last_seen_user_name = $request->last_seen_user_name;

        $query = "INSERT INTO $tablename (title,message_last,message_last_date,message_last_time,
                                          message_sender_id,message_sender_name,last_seen_date,last_seen_time,
                                          last_seen_user_id,last_seen_user_name)";
        $query .= "VALUES ('$title','$message_last','$message_last_date','$message_last_time',
                            '$message_sender_id','$message_sender_name','$last_seen_date','$last_seen_time',
                            '$last_seen_user_id','$last_seen_user_name') RETURNING *";
        // die($query);

        $retunring_value = $this->db->execute($query);
        $num = $retunring_value->rowCount();
        // echo ($num);

        if ($num > 0) {

            $data_arr = array();

            while ($row = $retunring_value->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'title' => $title,
                    'message_last' => $message_last,
                    'message_last_date' => $message_last_date,
                    'message_last_time' => $message_last_time,
                    'message_sender_id' => $message_sender_id,
                    'message_sender_name ' => $message_sender_name,
                    'last_seen_date' => $last_seen_date,
                    'last_seen_time' => $last_seen_time,
                    'last_seen_user_id' => $last_seen_user_id,
                    'last_seen_user_name' => $last_seen_user_name,

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

        $title = $request->title;
        $message_last = $request->message_last;
        $message_last_date = $request->message_last_date;
        $message_last_time = $request->message_last_time;
        $message_sender_id = $request->message_sender_id;
        $message_sender_name = $request->message_sender_name;
        $last_seen_date = $request->last_seen_date;
        $last_seen_time = $request->last_seen_time;
        $last_seen_user_id = $request->last_seen_user_id;
        $last_seen_user_name = $request->last_seen_user_name;

        $query = "UPDATE  $tablename SET title = '$title', message_last = '$message_last' ,message_last_date = '$message_last_date',
                                         message_last_time = '$message_last_time', message_sender_id = '$message_sender_id' ,
                                         message_sender_name = '$message_sender_name',last_seen_date = '$last_seen_date',
                                         last_seen_time = '$last_seen_time',last_seen_user_id = '$last_seen_user_id',
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
            return $msg = "Data Kosong";
        }
    }
}
