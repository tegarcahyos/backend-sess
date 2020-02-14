<?php
require __DIR__ . "/../adodb/adodb.inc.php";

class TransformerStaging
{
    public $db_transformer;

    public function transformer_connect()
    {

        $db_host = '10.60.163.49';
        $db_username = 'transformer';
        $db_password = 'transform$001';
        $db_name_transformer = 'transformer';
        // $this->db_transformer = newADOConnection('mysqli');
        // $this->db_transformer->connect($db_host, $db_username, $db_password, $db_name_transformer);
        $mysqli = mysqli_connect($db_host, $db_username, $db_password, $db_name_transformer);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        } else {
            die(print_r($mysqli));
        }

        return $this->db_transformer;
    }
}
