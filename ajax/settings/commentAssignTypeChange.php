<?php
    $nType=intval($_POST['commentAssignTypeChange']);
    if($nType!=ASSIGN_TYPE_AUTO){
        if($nType!=ASSIGN_TYPE_MANUAL){
            $nType=ASSIGN_TYPE_HIGHBREED;
        }
    }
    if($_POST['type']=='c'){
        $update=$db->settingsUpdate($nType,'pagePostAssign');
        if($update){
            if($nType==ASSIGN_TYPE_AUTO){
                $query='DELETE FROM '.$general->table(10);
                $delete=$db->runQuery($query);
            }
        }
    }
    else{
        $db->settingsUpdate($nType,'wallPostAssign');
        if($update){
            if($nType==ASSIGN_TYPE_AUTO){
                $query='DELETE FROM '.$general->table(5);
                $delete=$db->runQuery($query);
            }
        }
    }
?>
