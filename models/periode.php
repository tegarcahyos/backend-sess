<?php

class Periode
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
             $tablename order by id asc";
        // die($query);
        $result = $this->db->execute($query);
        // die($query);
        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'name' => $name,
                    'code' => $code,
                    'status_active' => $status_active,
                    'organisasi_id' => $organisasi_id,
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
                'status_active' => $status_active,
                'organisasi_id' => $organisasi_id,
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
        $code = $request[0]->code;
        $status_active = $request[0]->status_active;
        $organisasi_id = $request[0]->organisasi_id;

        $query_select_status = " SELECT id from $tablename where organisasi_id = '$organisasi_id' and status_active = '$status_active'";
        
        $result_select = $this->db->execute($query_select_status);

        $num = $result_select->rowCount();
        echo $num;

        if ($num > 0) {

            // $data_arr = array();

            while ($row = $result_select->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    
                );
            
                // array_push($data_item);
                $msg_item = $data_item;
            }
            $id_periode = implode('',$msg_item);
          
            if($status_active == true){
                $query_set_status = "UPDATE $tablename SET status_active = 'false' where id = '$id_periode'";
                // die($query_set_status);
                $this->db->execute($query_set_status);
                
            }else{
                $query_set_status = "UPDATE $tablename SET status_active = 'true' where id = '$id_periode'";
                // die($query_set_status);
                $this->db->execute($query_set_status);
            }

        }

            $query = "INSERT INTO $tablename (name, code, status_active,organisasi_id)";
            $query .= "VALUES ('$name', '$code', '$status_active', '$organisasi_id')";
            // die($query);
            $result = $this->db->execute($query);
            $num = $result->rowCount();

            $res = $this->db->affected_rows();

            if ($res == true) {
                $msg = array("message" => 'Data Berhasil Ditambah', "code" => 200);
            } else {
                $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
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
        $code = $request[0]->code;
        $status_active = $request[0]->status_active;
        $organisasi_id = $request[0]->organisasi_id;

        $query_select_status = " SELECT id from $tablename where organisasi_id = '$organisasi_id' and status_active = '$status_active'";
        
        $result_select = $this->db->execute($query_select_status);
        echo $result_select;

        $num = $result_select->rowCount();

        if ($num > 0) {
            while ($row = $result_select->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,                    
                );
                $msg_item = $data_item;
            }
            $id_periode = implode('',$msg_item);
            echo $id_periode;
          
            if($status_active == true){
                $query_set_status = "UPDATE $tablename SET status_active = 'false' where id = '$id_periode'";
                // die($query_set_status);
                echo $status_active;
                // $this->db->execute($query_set_status);           
            }else{
                $query_set_status = "UPDATE $tablename SET status_active = 'true' where id = '$id_periode'";
                // die($query_set_status);
                // $this->db->execute($query_set_status);
            }

        }

        // echo "update laa";
        $query = "UPDATE $tablename SET name = '$name', code = '$code', status_active = '$status_active', organisasi_id = '$organisasi_id' WHERE id = '$id'";
        // die($query);
        echo ($query);
        // $result = $this->db->execute($query);

        $res = $this->db->affected_rows();

        if ($res == true) {
            $msg = array("message" => 'Data berhasil diperbaharui', "code" => 200);
        } else {
            $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
        return $msg;
    }

    public function delete($id, $tablename)
    {
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
