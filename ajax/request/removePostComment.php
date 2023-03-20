<?php
    $comment_id=$_POST['comment_id'];
    $targetType=$_POST['targetType'];
    $type=$_POST['type'];
    $isClose        = $_POST['isClose'];if($isClose!=1)$isClose=0;
    $c=array();

    if($type=='c'){
        $canDelete=$db->permission(PER_COMMENT_DELETE);    
    }
    else if($type=='w'){
        $canDelete=$db->permission(PER_WALL_DELETE);
    }
    else{
        $canDelete=false;
    }

    if($canDelete==true){
        if($type=='c'){
            $jArray[__LINE__]=__LINE__;
            $c=$db->get_rowData($general->table(13),'comment_id',$comment_id);
        }
        elseif($type=='w'){
            if($targetType=='p'){
                $jArray[__LINE__]=__LINE__;
                $c=$db->get_rowData($general->table(12),'post_id',$comment_id);
            }
            else{
                $jArray[__LINE__]=__LINE__;
                $c=$db->get_rowData($general->table(14),'comment_id',$comment_id);
            }
        }

        if(!empty($c)){
            $jArray[__LINE__][]=$c;
            $delete=$social->deleteCommentByAgent($c,$type,$targetType,$jArray);
            if($delete==true){
                $jArray['status']=1;
            }
        }
        else{$error=__LINE__;SetMessage(4,'Invalid Post / Comment');}
    }else{
        $jArray['status']=1;
        $jArray[__LINE__]='Not authorize to delete';
    }
    if($jArray['status']==1){if($isClose==0){$jArray['nextComment']=$social->nextPicup($type);}}
    $jArray['m']=show_msg('Yes');
    $general->jsonHeader();
    echo json_encode($jArray);
?>
