<?php

class Upload
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function upload_file()
    {
        if (isset($_FILES['files'])) {
            $errors = [];
            $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

            // $all_files = count($_FILES['files']['tmp_name']);
            // for ($i = 0; $i < $all_files; $i++) {
            $file_name = $_FILES['files']['name'];
            $file_tmp = $_FILES['files']['tmp_name'];
            $file_type = $_FILES['files']['type'];
            $file_size = $_FILES['files']['size'];
            $tmp = explode('.', $_FILES['files']['name']);
            $file_ext = strtolower(end($tmp));
            $file = $path . $file_name;
            if ($file_size > 2097152) {
                $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            }
            if (empty($errors)) {
                if (move_uploaded_file($file_tmp, $file)) {
                    die("berhasil");
                } else {
                    die("gagal");
                }
                // $query = "INSERT INTO attachment (file_name) VALUES ('$file_name')";
                // $result = $this->db->execute($query);
                // $res = $this->db->affected_rows();

                // if ($res == true) {
                //     return $msg = array("message" => 'Data berhasil diperbaharui', "code" => 200);
                // } else {
                //     return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
                // }
            }
            // }
            if ($errors) {
                print_r($errors);
            }

        }

    }
}
