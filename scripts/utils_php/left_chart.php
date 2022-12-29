<!-- TODO NEEDS REFACTORING -->
<?php

$statement = $db->prepare('SELECT COUNT(*) FROM detections');
if ($statement == False) {
    echo "Database is busy";
    header("refresh: 0;");
}
$result = $statement->execute();
$totalcount = $result->fetchArray(SQLITE3_ASSOC);

$statement2 = $db->prepare('SELECT COUNT(*) FROM detections WHERE Date == DATE(\'now\', \'localtime\')');
if ($statement2 == False) {
    echo "Database is busy";
    header("refresh: 0;");
}
$result2 = $statement2->execute();
$todaycount = $result2->fetchArray(SQLITE3_ASSOC);

$statement3 = $db->prepare('SELECT COUNT(*) FROM detections WHERE Date == Date(\'now\', \'localtime\') AND TIME >= TIME(\'now\', \'localtime\', \'-1 hour\')');
if ($statement3 == False) {
    echo "Database is busy";
    header("refresh: 0;");
}
$result3 = $statement3->execute();
$hourcount = $result3->fetchArray(SQLITE3_ASSOC);

$statement5 = $db->prepare('SELECT COUNT(DISTINCT(Com_Name)) FROM detections WHERE Date == Date(\'now\',\'localtime\')');
if ($statement5 == False) {
    echo "Database is busy";
    header("refresh: 0;");
}
$result5 = $statement5->execute();
$speciestally = $result5->fetchArray(SQLITE3_ASSOC);

$statement6 = $db->prepare('SELECT COUNT(DISTINCT(Com_Name)) FROM detections');
if ($statement6 == False) {
    echo "Database is busy";
    header("refresh: 0;");
}
$result6 = $statement6->execute();
$totalspeciestally = $result6->fetchArray(SQLITE3_ASSOC);

?>
<table>
    <tr>
        <th>Total</th>
        <td>
            <?php echo $totalcount['COUNT(*)']; ?>
        </td>
    </tr>
    <tr>
        <th>Today</th>

        <td>
            <form action="" method="GET"><button type="submit" name="view" value="Today's Detections">
                    <?php echo $todaycount['COUNT(*)']; ?>
                </button>
        </td>
        </form>
    </tr>
    <tr>
        <th>Last Hour</th>
        <td><?php echo $hourcount['COUNT(*)']; ?></td>
    </tr>
    <tr>
        <th>Species Detected Today</th>
        <td>
            <form action="" method="GET"><input type="hidden" name="view" value="Recordings"><button type="submit"
                    name="date" value="<?php echo date('Y-m-d'); ?>">
                    <?php echo $speciestally['COUNT(DISTINCT(Com_Name))']; ?>
                </button>
        </td>
        </form>
    </tr>
    <tr>
        <th>Total Number of Species</th>
        <td>
            <form action="" method="GET"><button type="submit" name="view" value="Species Stats">
                    <?php echo $totalspeciestally['COUNT(DISTINCT(Com_Name))']; ?>
                </button>
        </td>
        </form>
    </tr>
</table>
<?php
die();

?>