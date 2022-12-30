<?php
class DataLayer
{
  // Deklaration einer Eigenschaft
  public $birdsDb = './scripts/birds.db';

  // Deklaration einer Methode
  function getDb()
  {
    $db = new SQLite3($this->birdsDb, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    if ($db == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $db;
  }

  function getResult4()
  {
    $statement4 = $this->getDb()->prepare('SELECT Com_Name, Sci_Name, Date, Time, Confidence, File_Name FROM detections ORDER BY Date DESC, Time DESC LIMIT 5');
    if ($statement4 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement4->execute();

  }
}
?>