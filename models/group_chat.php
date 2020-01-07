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
                    'title'=> $title,
                    'message_last'=> $message_last,
                    'message_last_date'=> $message_last_date,
                    'message_last_time'=> $message_last_time,
                    'message_sender_id'=> $message_sender_id,
                    'message_sender_name '=> $message_sender_name,
                    'last_seen_date'=> $last_seen_date,
                    'last_seen_time'=> $last_seen_time,
                    'last_seen_user_id'=> $last_seen_user_id,
                    'last_seen_user_name'=>  $last_seen_name,
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
            'title'=> $title,
            'message_last'=> $message_last,
            'message_last_date'=> $message_last_date,
            'message_last_time'=> $message_last_time,
            'message_sender_id'=> $message_sender_id,
            'message_sender_name '=> $message_sender_name,
            'last_seen_date'=> $last_seen_date,
            'last_seen_time'=> $last_seen_time,
            'last_seen_user_id'=> $last_seen_user_id,
            'last_seen_user_name'=>  $last_seen_name,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        
            $title= $request[0]->title;
            $message_last= $request[0]->message_last;
            $message_last_date= $request[0]->message_last_date;
            $message_last_time= $request[0]->message_last_time;
            $message_sender_id= $request[0]->message_sender_id;
            $message_sender_name= $request[0]->message_sender_name;
            $last_seen_date= $request[0]->last_seen_date;
            $last_seen_time= $request[0]->last_seen_time;
            $last_seen_user_id= $request[0]->last_seen_user_id;
            $last_seen_user_name= $request[0]->last_seen_name;

        $query = "INSERT INTO $tablename (title,message_last,message_last,message_last_date,message_last_time,
                                            message_sender_id,message_sender_name,last_seen_date,last_seen_time,last_seen_user_id,last_seen_name)";
        $query .= "VALUES ('$title','$message_last','$message_last','$message_last_date','$message_last_time',
                            '$message_sender_id','$message_sender_name','$last_seen_date','$last_seen_time','$last_seen_user_id','$last_seen_name')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request->name;

        $query = "UPDATE " . $tablename . " SET name = '" . $name . "' WHERE id = " . $id;
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
