<?php
require __DIR__ . "/../adodb/adodb.inc.php";

class TransformerStaging
{
    public $db_transformer;

    public function transformer_connect()
    {

        $db_host = '10.62.163.49';
        $db_username = 'transformer';
        $db_password = 'transform$001';
        $db_name_transformer = 'transformer';

        $this->db_transformer = newADOConnection('mysql');
        $this->db_transformer->connect($db_host, $db_username, $db_password, $db_name_transformer);
        die(print_r($this->db_transformer));
        return $this->db_transformer;
    }
}
