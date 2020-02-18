<?php
require_once 'transformer_connect.php';

class MigrateStaging
{
    public $db;
    public $db_transformer;

    public function __construct($db)
    {
        $this->db = $db;

    }

    public function get($tablename)
    {
        $db_transformer = new TransformerStaging();
        $db_transformer = $db_transformer->transformer_connect();
        // die(print_r($db_transformer->transformer_connect()));
        $query = "SELECT
           *
          FROM
             $tablename order by updated_at desc";

        // die($query);
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'title' => $title,
                    'code' => $code,
                    'strategic_initiative' => $strategic_initiative,
                    'unit_id' => $unit_id,
                    'weight' => $weight,
                    'description' => $description,
                    'refer_to' => json_decode($refer_to),
                    'stakeholders' => json_decode($stakeholders),
                    'kpi' => json_decode($kpi),
                    'main_activities' => json_decode($main_activities),
                    'key_asks' => json_decode($key_asks),
                    'risks' => $risks,
                    'status' => $status,
                    'generator_id' => $generator_id,
                );

                $title = $data_item['title'];
                $strategic_initiative = $data_item['strategic_initiative'];
                $description = $data_item['description'];
                $risks = $data_item['risks'];
                $generator_id = $data_item['generator_id'];
                $get_data_generator = "SELECT * FROM users WHERE id = '$generator_id'";
                $data_generator = $this->db->execute($get_data_generator);
                $row = $data_generator->fetchRow();
                $name = $row['name'];

                $query_get = "SELECT EXISTS(SELECT * FROM staging_program WHERE title = '$title')";
                // die($query_get);
                $get_result = $db_transformer->execute($query_get);
                // die(print_r($get_result == false));
                if ($get_result === false) {
                    $query_staging = "UPDATE staging_program SET btp = 1, businessRisk = '$risks', description = '$description', title = '$title', generator = '$name', programType = 'btp' WHERE title = '$title' AND generator = '$name'";
                    // die($query_staging);
                    $db_transformer->execute($query_staging);
                } else {
                    $query_staging = "INSERT INTO staging_program (btp, businessRisk, description, title, generator, programType)";
                    $query_staging .= "VALUES (1, '$risks', '$description', '$title', '$name', 'btp')";
                    // die($query_staging);
                    $db_transformer->execute($query_staging);
                }

                // $query_staging = "INSERT INTO staging_program (btp, businessRisk, description, title, generator, programType)
                // SELECT * FROM (SELECT 1, '$risks', '$description', '$title', '$name', 'btp') AS tmp
                // WHERE NOT EXISTS (
                //     SELECT name FROM staging_program WHERE title = '$title'
                // )";
                // die($query_staging);

            }

        }
    }
}
