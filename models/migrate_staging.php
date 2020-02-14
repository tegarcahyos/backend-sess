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
        $this->$db_transformer = new TransformerStaging();
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

                die($data_item['risks']);
                $query_staging = "INSERT INTO staging_program (btp, businessRisk, description, title, inititative_id, generator, programType, organization_id)";
                $query_staging .= "VALUES (1, )";

            }

        }
    }
}
