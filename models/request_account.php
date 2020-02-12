<?php

class RequestAccount
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
             $tablename";

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
                    'password' => $password,
                    'role' => $role_id,
                    'unit' => $unit_id,
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
                'password' => $password,
                'role' => $role_id,
                'unit' => $unit_id,
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
        $role_id = $request[0]->role_id;
        $unit_id = $request[0]->unit_id;
        // $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO $tablename (name, email, phone, username, password, role_id, unit_id)";
        $query .= "VALUES ('$name', '$email', $phone ,'$username', '$password', '$role_id', '$unit_id') RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "402";
        } else {
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
                        'password' => $password,
                        'role' => $role_id,
                        'unit' => $unit_id,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }
        }
        return $msg;

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
        $role_id = $request[0]->role_id;
        $unit_id = $request[0]->unit_id;
        // $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "UPDATE $tablename SET name = '$name', email = '$email', phone = '$phone', username = ' $username', password = '$password', role_id = '$role_id', unit_id = '$unit_id' WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "402";
        } else {
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
                        'password' => $password,
                        'role' => $role_id,
                        'unit' => $unit_id,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }
        }
        return $msg;
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = $id";
        // die($query);
        $this->db->execute($query);
        $res = $this->db->affected_rows();

        if ($res == true) {

            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
        // }

    }
}
