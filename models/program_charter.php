<?php

class ProgramCharter
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
                    'cfu_fu' => $cfu_fu,
                    'weight' => $weight,
                    'matrix' => $matrix,
                    'description' => $description,
                    'refer_to' => $refer_to,
                    'stakeholders' => $stakeholders,
                    'kpi' => $kpi,
                    'budget' => $budget,
                    'main_activities' => $main_activities,
                    'key_asks' => $key_asks,
                    'risks' => $risks,
                    'approval' => $approval,
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
                'title' => $title,
                'code' => $code,
                'strategic_initiative' => $strategic_initiative,
                'cfu_fu' => $cfu_fu,
                'weight' => $weight,
                'matrix' => $matrix,
                'description' => $description,
                'refer_to' => $refer_to,
                'stakeholders' => $stakeholders,
                'kpi' => $kpi,
                'budget' => $budget,
                'main_activities' => $main_activities,
                'key_asks' => $key_asks,
                'risks' => $risks,
                'approval' => $approval,
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
        $title = $request[0]->title;
        $code = $request[0]->code;
        $strategic_initiative = json_encode($request[0]->strategic_initiative);
        $cfu_fu = $request[0]->cfu_fu;
        $weight = $request[0]->weight;
        $matrix = $request[0]->matrix;
        $description = $request[0]->description;
        $refer_to = $request[0]->refer_to;
        $stakeholders = $request[0]->stakeholders;
        $kpi = $request[0]->kpi;
        $budget = $request[0]->budget;
        $main_activities = $request[0]->main_activities;
        $key_asks = $request[0]->key_asks;
        $risks = $request[0]->risks;
        $approval = $request[0]->approval;

        if (empty($description)) {
            $description = 'NULL';
        }
        if (empty($risks)) {
            $risks = 'NULL';
        }
        if (empty($approval)) {
            $approval = 'NULL';
        }

        $query = "INSERT INTO $tablename (
        title,
        code,
        strategic_initiative
        cfu_fu,
        weight,
        matrix,
        description,
        refer_to,
        stakeholders,
        kpi,
        budget,
        main_activities,
        key_asks,
        risks,
        approval)";
        $query .= "VALUES (
            '$title',
            '$code',
            '$strategic_initiative'
            '$cfu_fu',
            '$weight'
            '$matrix',
            NULLIF('$description', 'NULL'),
            '$refer_to',
            '$stakeholders',
            '$kpi',
            '$budget',
            '$main_activities',
            '$key_asks',
            NULLIF('$risks', 'NULL'),
            NULLIF('$approval', 'NULL')
            )";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Ditambah', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $title = $request[0]->title;
        $code = $request[0]->code;
        $cfu_fu = $request[0]->cfu_fu;
        $strategic_initiative = $request[0]->strategic_initiative;
        $weight = $request[0]->weight;
        $matrix = $request[0]->matrix;
        $description = $request[0]->description;
        $refer_to = $request[0]->refer_to;
        $stakeholders = $request[0]->stakeholders;
        $kpi = $request[0]->kpi;
        $budget = $request[0]->budget;
        $main_activities = $request[0]->main_activities;
        $key_asks = $request[0]->key_asks;
        $risks = $request[0]->risks;
        $approval = $request[0]->approval;

        $query = "UPDATE $tablename SET
            title = '$title',
            code = '$code',
            strategic_initiative = '$strategic_initiative',
            cfu_fu = '$cfu_fu',
            weight = '$weight'
            matrix = '$matrix',
            description = '$description',
            refer_to = '$refer_to',
            stakeholders = '$stakeholders',
            kpi = '$kpi',
            budget = '$budget',
            main_activities = '$main_activities',
            key_asks = '$key_asks',
            risks = '$risks',
            approval ='$approval',
        WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data berhasil diperbaharui', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
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
