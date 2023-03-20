<?php
    include("crn_config.php");
    $currentTable=$db->settingsValue('commentReportTable');echo'<br>'; 
    if($currentTable==45){$nexTable=46;}else{$nexTable=45;}
    echo $general->make_date(time(),'time');
    $db->runQuery("INSERT INTO ".$general->table($nexTable)." SELECT * FROM ".$general->table(13),'d');
    $db->settingsUpdate($nexTable,'commentReportTable');
    echo $general->make_date(time(),'time');
    $s2=new social2();$s2->cronLog('commentTableCopy');
    mysqli_close($GLOBALS['connection']);
?>
