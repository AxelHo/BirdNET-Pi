<?php
class DataLayer
{

  private $birdsDb = './scripts/birds.db';

  function getDb()
  {
    $db = new SQLite3($this->birdsDb, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    if ($db == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $db;
  }

  function getTotalCount()
  {
    $statement1 = $this->getDb()->prepare('SELECT COUNT(*) FROM detections');
    if ($statement1 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement1->execute();
  }

  function getTodayCount()
  {
    $statement2 = $this->getDb()->prepare('SELECT COUNT(*) FROM detections WHERE Date == DATE(\'now\', \'localtime\')');
    if ($statement2 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement2->execute();
  }

  function getHourCount()
  {
    $statement3 = $this->getDb()->prepare('SELECT COUNT(*) FROM detections WHERE Date == Date(\'now\', \'localtime\') AND TIME >= TIME(\'now\', \'localtime\', \'-1 hour\')');
    if ($statement3 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement3->execute();
  }

  function getMostRecent()
  {
    $statement4 = $this->getDb()->prepare('SELECT Com_Name, Sci_Name, Time, Confidence FROM detections LIMIT 1');
    if ($statement4 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement4->execute();

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

  function getTodaySpeciestally()
  {
    $statement5 = $this->getDb()->prepare('SELECT COUNT(DISTINCT(Com_Name)) FROM detections WHERE Date == Date(\'now\', \'localtime\')');
    if ($statement5 == False) {
      echo "Database is busy";
      header("refresh: 0;");
    }
    return $statement5->execute();
  }
}
?>