<?php

class User
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
                    'code' => $code,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'username' => $username,
                    'password' => $password,
                    'external_id' => $external_id,
                    'status_active' => $status_active,
                    'status_delete' => $status_delete,
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
            'code' => $code,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'password' => $password,
            'external_id' => $external_id,
            'status_active' => $status_active,
            'status_delete' => $status_delete,
        );
        return $data_item;
    }

    public function findByEmail($email, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE email = ' . $email . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'code' => $code,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'password' => $password,
            'external_id' => $external_id,
            'status_active' => $status_active,
            'status_delete' => $status_delete,
        );
        return $data_item;
    }

    public function findByUsername($username, $tablename)
    {
        $query = 'SELECT * FROM ' . $tablename . ' WHERE username = ' . $username . "";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        extract($row);

        $data_item = array(
            'id' => $id,
            'code' => $code,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'password' => $password,
            'external_id' => $external_id,
            'status_active' => $status_active,
            'status_delete' => $status_delete,
        );
        return $data_item;
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $code = $request[0]->code;
        $name = $request[0]->name;
        $email = $request[0]->email;
        $phone = $request[0]->phone;
        $username = $request[0]->username;
        $password = $request[0]->password;
        $external_id = 1;
        $status_active = 'false';
        $status_delete = 'false';

        $query = "INSERT INTO $tablename (code, name, email, phone, username, password, external_id, status_active, status_delete)";
        $query .= "VALUES ('$code' , '$name', '$email', $phone , '$username', '$password', $external_id, '$status_active', '$status_delete')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $code = $request[0]->code;
        $name = $request[0]->name;
        $email = $request[0]->email;
        $phone = $request[0]->phone;
        $username = $request[0]->username;
        $password = $request[0]->password;
        $external_id = 1;
        $status_active = 'false';
        $status_delete = 'false';

        $query = "UPDATE " . $tablename . " SET name = '" . $name . "', code = '" . $code . "', email = '" . $email . "', phone = '" . $phone . "', username = '" . $username . "', password = '" . $password . "', external_id = '" . $external_id . "', status_active = '" . $status_active . "', status_delete = '" . $status_delete . "'" . " WHERE id = " . $id;
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
