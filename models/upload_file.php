<?php

class Upload
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function download_file($filename)
    {

    }

    public function upload_file()
    {
        
            $uuid =sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        
                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),
        
                // // 16 bits for "time_hi_and_version",
                // // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,
        
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,
        
                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
            // echo $uuid;
        
        if (isset($_FILES['file'])) {
            $errors = [];
            $path = '/app/pmo-backend/uploads/';

            // $all_files = count($_FILES['files']['tmp_name']);
            // for ($i = 0; $i < $all_files; $i++) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];
            $file_size = $_FILES['file']['size'];
            $tmp = explode('.', $_FILES['file']['name']);
            $file_ext = strtolower(end($tmp));
            $file_name_upload = $uuid.'.'.$file_ext;
            $file = $path.$file_name_upload;
        
           
            if ($file_size > 2097152) {
                $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            }
            if (empty($errors)) {
                move_uploaded_file($file_tmp, $file);
                //  print_r($file_tmp);
                $query = "INSERT INTO upload_file (file_name) VALUES ('$file_name_upload') RETURNING *";
                // die($query);
                $result = $this->db->execute($query);
                $num = $result->rowCount();

                // jika ada hasil
                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        // Push to data_arr

                        $data_item = array(
                            'id' => $id,
                            'file_name' => $file_name,
                            
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }

                } else {
                    $msg = 'Data Kosong';
                }

                return $msg;
            }
            // }
            if ($errors) {
                print_r($errors);
            }

        }

    }

    public function downloadFile($id_file, $tablename)
    {
        $query = "SELECT * FROM $tablename where id = $id_file";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);
            $filename = basename($row['file_name']);
            $file = '/app/pmo-backend/uploads/' . $filename;
            if (!file_exists($file)) { // file does not exist
                die('file not found');
            } else {
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: binary");

                // read the file from disk
                readfile($file);
            }
        }

    }

    public function get($tablename)
    {
        $query = "SELECT * FROM $tablename ORDER BY id ASC";

        $result = $this->db->execute($query);
        
        $num = $result->rowCount();

        if($num>0){
            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id'=>$id,
                    'file_name'=>$file_name
                );
                array_push($data_arr,$data_item);
                }
                $msg=$data_arr;
        }else{
            $msg='0';
        }
        return $msg;
        


    }
}
