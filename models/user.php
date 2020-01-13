<?php

class User
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get()
    {
        $query = "SELECT
           *
          FROM
             users
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
                    'email' => $email,
                    'phone' => $phone,
                    'username' => $username,
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
        $handle = $this->db->prepare($query);
        $result = $this->db->execute($handle);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);
            $data_item = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
            );
            return $data_item;
        }
    }

    public function findByEmail($email, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE email = '$email'";
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
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
            );
            return $data_item;
        }
    }

    public function findByUsername($username, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE username = '$username'";
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
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
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
        $name = $request[0]->name;
        $email = $request[0]->email;
        $phone = $request[0]->phone;
        $username = $request[0]->username;
        $password = $request[0]->password;
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO $tablename (name, email, phone, username, password)";
        $query .= "VALUES ('$name', '$email', $phone ,'$username', '$password_hash') RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);
        $data_item = array(
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
        );
        return $data_item;

    }

    public function updatePhotoProfile($id, $tablename)
    {
        $data = file_get_contents("php://input");

        $request = json_decode($data);
        $upload_dir = '../upload/profile/';
        $upload_file = $upload_dir . basename($request->photo);
        $name = $request->photo;

        move_uploaded_file($upload_file, $upload_dir);

        $query = "UPDATE $tablename SET photo_profile = '$name' WHERE id = $id";
        return $this->db->execute($query);
    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;
        $email = $request[0]->email;
        $phone = $request[0]->phone;
        $username = $request[0]->username;
        $password = $request[0]->password;
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "UPDATE $tablename SET name = '$name', email = '$email', phone = '$phone', username = ' $username', password = '$password_hash' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        // return $result;

    }
}
