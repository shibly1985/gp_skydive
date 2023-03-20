<?php
    $wallPostCommentFlowType=intval($_POST['commentFlowType']);
    if($wallPostCommentFlowType!=WALL_POST_COMMENT_FLOW_FIFO)$wallPostCommentFlowType=WALL_POST_COMMENT_FLOW_LIFO;
    if($_POST['type']=='w'){
        $db->settingsUpdate($wallPostCommentFlowType,'wallPostFlowType');
        $query="UPDATE `".$general->table(17)."` SET `uWallpostFlow`=".$wallPostCommentFlowType;
    }
    else{
        $db->settingsUpdate($wallPostCommentFlowType,'commentFlowType');
        $query="UPDATE `".$general->table(17)."` SET `uCommentFlow`=".$wallPostCommentFlowType;
    }
    $db->runQuery($query);
?>
