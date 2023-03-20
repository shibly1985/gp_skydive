<?php
    include("crn_config.php");
    $query="delete from ".$general->table(37)." where active<".strtotime("-1 month")." ";
    $db->runQuery($query,'d');
    $query="
    delete from ".$general->table(13)." where
    created_time<".strtotime("-7 month")." 
    and replyTime<".strtotime("-7 month")."
    and replyTime>0
    ";
    $db->runQuery($query,'d');

    $query="delete from ".$general->table(12)." where
    created_time<".strtotime("-7 month")." 
    and replyTime<".strtotime("-7 month")." 
    and replyTime>0
    ";
    $db->fetchQuery($query,'d');
    $query="
    delete from ".$general->table(14)." where
    created_time<".strtotime("-7 month")." 
    and replyTime<".strtotime("-7 month")."
    and replyTime>0
    ";
    $db->runQuery($query,'d');
    $query="
    delete from ".$general->table(37)." where
    active<".strtotime("-7 month");
    $db->runQuery($query,'d');
    $query="
    delete from ".$general->table(31)." where
    hideTime<".strtotime("-7 month");
    $db->runQuery($query,'d');
?>
