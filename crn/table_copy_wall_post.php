<?php
    include("crn_config.php");
    echo $general->make_date(time(),'time');echo'<br>'; 
    $currentTable=$db->settingsValue('postWallReportTable');echo'<br>'; 
    if($currentTable==47){$nexTable=48;}else{$nexTable=47;}

    $db->runQuery("TRUNCATE ".$general->table($nexTable),'d');
    $db->runQuery("INSERT INTO ".$general->table($nexTable)." SELECT * FROM ".$general->table(12),'d');
    $db->settingsUpdate($nexTable,'postWallReportTable');
    echo $general->make_date(time(),'time');

    $currentTable=$db->settingsValue('commentWallReportTable');echo'<br>'; 
    if($currentTable==49){$nexTable=50;}else{$nexTable=49;}
    $db->runQuery("TRUNCATE ".$general->table($nexTable),'d');
    $db->runQuery("INSERT INTO ".$general->table($nexTable)." SELECT * FROM ".$general->table(14),'d');
    $db->settingsUpdate($nexTable,'commentWallReportTable');
    echo $general->make_date(time(),'time');echo'<br>'; 
    $s2=new social2();$s2->cronLog('wallTableCopyDelete');
    mysqli_close($GLOBALS['connection']);
?>
