<?php
    include("crn_config.php");
    $currentTable=$db->settingsValue('commentReportTable');echo'<br>'; 
    if($currentTable==45){$nexTable=46;}else{$nexTable=45;}
    echo $general->make_date(time(),'time');echo'<br>'; 
    $db->runQuery("TRUNCATE ".$general->table($nexTable),'d');
    echo $general->make_date(time(),'time');
    $s2=new social2();$s2->cronLog('commentTableDelete');
    mysqli_close($GLOBALS['connection']);
?>
