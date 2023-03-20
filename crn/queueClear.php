<?php
    include_once("../class/class.db.php");
    include_once("../class/class.general.php");
    include_once("../class/class.social.php");
    include_once("../class/class.social2.php");
    include_once("../class/messages.php"); 
    $db     = new DB();
    $general= new General();
    $social = new social();
    include("../init.php");

    $all_data=$db->selectAll($general->table(41),'where sendSuccess=0 and totalTry<'.OUTBOX_MAX_RETRY.' order by scqID desc limit 100');
    if(!empty($all_data)){ 
        foreach($all_data as $q){
            $c=$social->getCommentInfoById($q['target_c_id'],'c');
            if(!empty($c)){
                if($c['replyed']==3){
                    $db->delete($general->table(41),array('scqID'=>$q['scqID']),'d');
                }
            }
        }
    }
    else{
        echo 'empty';
    }
?>