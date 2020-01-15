<?php

class UserLogin
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
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'device_id' => $device_id,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function findById($id, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE id = ' . $id . "";
        try {
            $handle = $this->db->prepare($query);
            $result = $this->db->execute($handle);
            $num = $result->rowCount();
        } catch (\Trhowable $th) {
            $msg = array("message" => 'User Tidak Ditemukan', "code" => 400);
        }

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'device_id' => $device_id,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = '0';
        }

        return $msg;
    }

    public function findByDeviceId($device_id, $tablename)
    {
        $data = "0";

        $query = "SELECT * FROM $tablename WHERE device_id = '$device_id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        $num = $result->rowCount();

        if ($num > 0) {
            $data_arr = array();
            extract($row);

            $data_item = array(
                'id' => $id,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'device_id' => $device_id,
                'create_date' => $create_date,
                'create_time' => $create_time,
            );

            array_push($data_arr, $data_item);
            $data = $data_arr;
        }

        return $data;
    }

    public function findByUsername($user_name, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE user_name = $user_name";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'device_id' => $device_id,
            'create_date' => $create_date,
            'create_time' => $create_time,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");

        // $request = json_decode($data); KALO ADA FRONTEND
        $request = json_decode($data);
        // die(print_r($request->user_id));

        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $device_id = $request->device_id;
        $create_date = $request->create_date;
        $create_time = $request->create_time;

        $query = "INSERT INTO $tablename (user_id,user_name,device_id, create_date,create_time)"; 
        $query .= "VALUES ('$user_id','$user_name','$device_id', '$create_date','$create_time') RETURNING *";
        die($query);
        $retunring_value = $this->db->execute($query);
        $num = $retunring_value->rowCount();
        // echo ($num);

        if ($num > 0) {

            $data_arr = array();

            while ($row = $retunring_value->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'device_id' => $device_id,
                    'create_date' => $create_date,
                    'create_time' => $create_time,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = '0';
        }
        return $msg;

    }

    // public function updatePhotoProfile($id, $tablename)
    // {
    //     $data = file_get_contents("php://input");

    //     $request = json_decode($data);
    //     $upload_dir = '../upload/profile/';
    //     $upload_file = $upload_dir . basename($request->photo);
    //     $name = $request->photo;

    //     move_uploaded_file($upload_file, $upload_dir);

    //     $query = "UPDATE $tablename SET photo_profile = '$name' WHERE id = $id";
    //     return $this->db->execute($query);
    // }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);

        $id = $request[0]->id;
        $user_id = $request[0]->user_id;
        $user_name = $request[0]->user_name;
        $device_id = $request[0]->device_id;
        $create_date = $request[0]->create_date;
        $create_time = $request[0]->create_time;

        $query = "UPDATE $tablename SET user_id = '$user_id', user_name = '$user_name', device_id = '$device_id', create_date = ' $create_date', create_time = ' $create_time' WHERE id = $id";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($device_id, $tablename)
    {
        $query = " DELETE FROM  $tablename  WHERE device_id = '$device_id'";
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
