<?php

class isCodeExists
{
  public function checkIfExists($tablename, $code)
  {
    $query = "SELECT * FROM $tablename WHERE code = '$code'";
    $result = $this->db->execute($query);
    $row = $result->fetchRow();
    return $row;
  }
}
