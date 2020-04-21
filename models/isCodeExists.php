<?php

function checkIfExists($tablename, $code, $db)
{
  $query = "SELECT * FROM $tablename WHERE code = '$code'";
  $result = $db->execute($query);
  $row = $result->fetchRow();
  return $row;
}
