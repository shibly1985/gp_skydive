<?php
    $jArray=array('status'=>0);
    $comment_id = $_POST['commentPostTransferWithMember'];
    $type       = $_POST['type'];
    $users      = $_POST['users'];
    $targetType = $_POST['targetType'];
    $isClose    = $_POST['isClose'];if($isClose!=1)$isClose=0;
    $ug_ids = array();
    if($type=='c'){
        $c=$social->getCommentInfoById($comment_id,$type);
        if(!empty($c)){
            if($c['replyed']==0){
                $where=array('comment_id'=>$comment_id);
                $db->delete($general->table(44),$where);//this query for multiple assigne cleare
                foreach($users as $uID){
                    if($uID!=UID){
                        $u=$db->get_rowData($general->table(17),'uID',intval($uID));
                        if(!empty($u)){
                            $ug_ids[$u['ugID']]=$u['ugID'];
                            $data=array(
                                'comment_id'    => $comment_id,
                                'uID'           => $uID,
                                'created_time'  => $c['created_time'],
                                'assign_time'   => TIME
                            );
                            $insert=$db->insert($general->table(10),$data);
                            if(!$insert){$error=__LINE__;}
                        }
                    }
                }
                if(!isset($error)){
                    $jArray['status']=1;
                    $query="update ".$general->table(13)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                    $query="update ".$general->table(63)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                    $db->runQuery($query);
                }
                if(!isset($error)&&!empty($ug_ids)){
                    foreach($ug_ids as $ugID){
                        $data = array(
                            'uID'           => UID,
                            'ugID'          => $ugID,
                            'comment_id'    => $comment_id,
                            'transfer_time' => TIME
                        );
                        $db->insert($general->table(54),$data); 
                    }
                }
            }
        }else{$error=__LINE__;SetMessage(63,'Comment');}
    }
    else if($type=='w'){
        if($targetType=='c'){
            $c=$social->getCommentInfoById($comment_id,$type);
            //$jArray[__LINE__]=$c;
            if(!empty($c)){
                if($c['replyed']==0){
                    foreach($users as $uID){
                        if($uID!=UID){
                            $u=$db->get_rowData($general->table(17),'uID',intval($uID));
                            if(!empty($u)){
                                $jArray[__LINE__]=__LINE__;
                                $ug_ids[$u['ugID']]=$u['ugID'];
                                $data=array(
                                    'comment_id'    => $comment_id,
                                    'uID'           => $uID,
                                    'created_time'  => $c['created_time'],
                                    'assign_time'   => TIME
                                );
                                $insert=$db->insert($general->table(36),$data);
                                if(!$insert){$error=__LINE__;}
                            }
                        }
                    }
                    if(!isset($error)){
                        $query="update ".$general->table(14)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                        $query="update ".$general->table(65)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                        $db->runQuery($query);
                        $jArray['status']=1;
                    }
                }else{$jArray[__LINE__]=__LINE__;}
            }
            else{
                $error=__LINE__;SetMessage(63,'Comment');
            }
            $jArray[__LINE__]=$ug_ids;
            if(isset($error)){
                $jArray[__LINE__]=$error;
            }
            if(!isset($error)&&!empty($ug_ids)){
                $jArray[__LINE__]=__LINE__;
                foreach($ug_ids as $ugID){
                    $data = array(
                        'uID'           => UID,
                        'ugID'          => $ugID,
                        'comment_id'    => $comment_id,
                        'transfer_time' => TIME
                    );
                    $db->insert($general->table(55),$data); 
                }
            }
        }
        else{
            $c=$social->getPostInfoById($comment_id,$type);
            if(!empty($c)){
                $jArray[__LINE__]=__LINE__;
                if($c['replyed']==0){
                    foreach($users as $uID){
                        if($uID!=UID){
                $jArray[__LINE__]=__LINE__;
                            $u=$db->get_rowData($general->table(17),'uID',intval($uID));
                            if(!empty($u)){
                $jArray[__LINE__]=__LINE__;
                                $ug_ids[$u['ugID']]=$u['ugID'];
                                $data=array(
                                    'post_id'       => $comment_id,
                                    'uID'           => $uID,
                                    'created_time'  => $c['created_time'],
                                    'assign_time'   => TIME
                                );
                                $insert=$db->insert($general->table(5),$data);
                                if(!$insert){$error=__LINE__;}
                            }
                        }
                    }
                    if(!isset($error)){
                $jArray[__LINE__]=__LINE__;
                        $query="update ".$general->table(12)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                        $query="update ".$general->table(64)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                        $db->runQuery($query);
                        $jArray['status']=1;
                    }
                }
                if(!isset($error)&&!empty($ug_ids)){
                    foreach($ug_ids as $ugID){
                $jArray[__LINE__]=__LINE__;
                        $data = array(
                            'uID'           => UID,
                            'ugID'          => $ugID,
                            'post_id'       => $comment_id,
                            'transfer_time' => TIME
                        );
                        $db->insert($general->table(56),$data); 
                    }
                }
            }else{$error=__LINE__;SetMessage(63,'Post');}
        }
    }
    if(isset($error)){
        $jArray[__LINE__]=$error;
    }
    $jArray['m']=show_msg('Yes');
    if($jArray['status']==1){
        if($isClose==0){$jArray['nextComment']=$social->nextPicup($type);}
    }
    $general->jsonHeader($jArray);
?>
