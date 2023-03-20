<?php
    if($_POST['nextPage']=='undefined'){
        $nextPage =1;
    }else{
        $nextPage = $_POST['nextPage'];
    }
    $jArray=$social->wholeThreatData($_POST['loadWholeThreat'],$nextPage);
    $general->jsonHeader($jArray);
?>