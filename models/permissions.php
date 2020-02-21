<?php

class AppFeatures_id
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($tablename)
    {
        $query = "SELECT * FROM  $tablename ";
        // die($query);
        $result = $this->db->execute($query);
        // hitung result
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'features_id' => $features_id,
                    'role_id' => $role_id,
                    'read' => $read,
                    'write' => $write,
                    'delete' => $delete,
                    'approve' => $approve,
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
        if (is_bool($row)) {
            $msg = "Data Kosong";
        } else {

            extract($row);

            // Push to data_arr

            $data_item = array(
                'id' => $id,
                'features_id' => $features_id,
                'role_id' => $role_id,
                'read' => $read,
                'write' => $write,
                'delete' => $delete,
                'approve' => $approve,
            );

            $msg = $data_item;
        }
        return $msg;
    }

    public function insert($tablename)
    {
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $variable = array('features_id', 'role_id', 'read', 'write', 'delete', 'approve');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = 'INSERT INTO ' . $tablename . ' (features_id, role_id, read, write, delete, approve) ';
        $query .= "VALUES ('$features_id', '$role_id', '$read', '$write', '$delete', '$approve') RETURNING *";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();

            // jika ada hasil
            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    // Push to data_arr

                    $data_item = array(
                        'id' => $id,
                        'features_id' => $features_id,
                        'role_id' => $role_id,
                        'read' => $read,
                        'write' => $write,
                        'delete' => $delete,
                        'approve' => $approve,
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
        // init attribute dan values

        $data = file_get_contents("php://input");

        $request = json_decode($data);

        $variable = array('features_id', 'role_id', 'read', 'write', 'delete', 'approve');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        $query = "UPDATE $tablename SET features_id = '$features_id', role_id = '$role_id', read = '$read', write = '$write', delete = '$delete', approve = '$approve' WHERE id = '$id' RETURNING *";

        // die($query);

        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();

            // jika ada hasil
            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    // Push to data_arr

                    $data_item = array(
                        'id' => $id,
                        'features_id' => $features_id,
                        'role_id' => $role_id,
                        'read' => $read,
                        'write' => $write,
                        'delete' => $delete,
                        'approve' => $approve,
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
        $query = "DELETE FROM $tablename WHERE id = '$id'";

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
