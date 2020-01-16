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
            echo $tmp;
            // $file_ext = strtolower(end($tmp));
            // $file = $path . $file_name;
            // $file_date = date('d-m-Y h:i:s');
            // if ($file_size > 2097152) {
            //     $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            // }
            // if (empty($errors)) {
            //     move_uploaded_file($file_tmp, $file);
            //     $query = "INSERT INTO upload_file (file_name) VALUES ('$file_name') RETURNING *";

            //     $result = $this->db->execute($query);
            //     $num = $result->rowCount();

            //     // jika ada hasil
            //     if ($num > 0) {

            //         $data_arr = array();

            //         while ($row = $result->fetchRow()) {
            //             extract($row);

            //             // Push to data_arr

            //             $data_item = array(
            //                 'file_name' => $file_name,
            //             );

            //             array_push($data_arr, $data_item);
            //             $msg = $data_arr;
            //         }

            //     } else {
            //         $msg = 'Data Kosong';
            //     }

            //     return $msg;
            // }
            // // }
            // if ($errors) {
            //     print_r($errors);
            // }

        }

    }

    public function downloadFile($id_file)
    {

        $query = "SELECT * FROM upload_file where id = $id_file";
        die($query);
        $file = basename($_GET['file']);
        $file = '/app/pmo-backend/uploads/' . $file;
        if (!file_exists($file)) { // file does not exist
            die('file not found');
        } else {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");

            // read the file from disk
            readfile($file);
        }
    }
}
