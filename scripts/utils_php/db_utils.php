<?php
function getDb()
{
    $db = new SQLite3('./scripts/birds.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
if($db == False) {
  echo "Database is busy";
 return header("refresh: 0;");
}
   return $db;
}
?>