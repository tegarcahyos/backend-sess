<?php
class Organization
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
                    'code' => $code,
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
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'name' => $name,
                'code' => $code,
            );
            return $data_item;
        }
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        // die(print_r($request[0]));
        // $type_id = 1;
        // $type_name = "Telkom";
        // $type_code = "T12";
        $name = $request[0]->name;
        $code = $request[0]->code;

        $query = "INSERT INTO $tablename (name, code)";
        // $query .= "VALUES ($type_id , '$type_name', '$type_code', '$name', '$code')";
        $query .= "VALUES ('$name', '$code')";
        // die($query);
        return $this->db->execute($query);

    }

    public function update($id, $tablename)
    {
        $data = file_get_contents("php://input");
        $request = json_decode($data);
        // $type_id = 1;
        // $type_name = "Telkom";
        // $type_code = "T12";
        $name = $request[0]->name;
        $code = $request[0]->code;

        $query = "UPDATE $tablename SET name = '$name', code = '$code' WHERE id = '$id'";
        // die($query);
        return $this->db->execute($query);
    }

    public function delete($id, $tablename)
    {
        $get_unit = "SELECT EXISTS(SELECT * FROM unit WHERE organization_id = '$id')";
        $result = $this->db->execute($get_unit);
        $row = $result->fetchRow();
        if ($row['exists'] == 't') {
            return "403";
        }
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
