<?php
    include("crn_config.php");
    sleep(5);
    $s2=new social2();$s2->cronLog('serverLog');
    echo 'success';
    mysqli_close($GLOBALS['connection']);
?>
