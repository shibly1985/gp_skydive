<?php
    $jArray=array('status'=>0);
    $comment_id = $_POST['commentTranser'];
    $type       = $_POST['type'];
    $targetType = $_POST['targetType'];
    $ugID       = intval($_POST['group']);
    $isClose    = $_POST['isClose'];if($isClose!=1)$isClose=0;
    $jArray[__LINE__]=__LINE__;
    if(UGID!=$ugID){
        $jArray[__LINE__]=__LINE__;
        if($type=='c'){
            $c=$social->getCommentInfoById($comment_id,$type);
            if(!empty($c)){
                if($c['replyed']==0){
                    $where=array('comment_id'=>$comment_id);
                    $db->delete($general->table(44),$where);//this query for multiple assigne cleare
                    $ug=$db->groupInfoByID($ugID);
                    if(!empty($ug)){
                        $users=$db->selectAll($general->table(17),'where ugID='.$ugID);
                        if(!empty($users)){
                            foreach($users as $u){
                                $data=array(
                                    'comment_id'    => $comment_id,
                                    'uID'           => $u['uID'],
                                    'created_time'  => $c['created_time'],
                                    'assign_time'   => TIME
                                );
                                $insert=$db->insert($general->table(10),$data);
                                if(!$insert){$error=__LINE__;}
                            }
                            if(!isset($error)){
                                $query="update ".$general->table(13)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                                $query="update ".$general->table(63)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                                $db->runQuery($query);
                                $data = array(
                                    'uID'           => UID,
                                    'ugID'          => $ugID,
                                    'comment_id'    => $comment_id,
                                    'transfer_time' => TIME
                                );
                                $db->insert($general->table(54),$data); 
                                $jArray['status']=1;
                            }
                        }
                    }else{$error=__LINE__;SetMessage(63,'Group ');}
                }
            }else{$error=__LINE__;SetMessage(63,'Comment');}
        }
        else if($type=='w'){
            $jArray[__LINE__]=__LINE__;
            if($targetType=='c'){
                $c=$social->getCommentInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['replyed']==0){
                        $ug=$db->groupInfoByID($ugID);
                        if(!empty($ug)){
                            $users=$db->selectAll($general->table(17),'where ugID='.$ugID);
                            if(!empty($users)){
                                foreach($users as $u){
                                    if($u['uID']!=UID){
                                        $data=array(
                                            'comment_id'    => $comment_id,
                                            'uID'           => $u['uID'],
                                            'created_time'  => $c['created_time'],
                                            'assign_time'   => TIME
                                        );
                                        $insert=$db->insert($general->table(36),$data);
                                        if(!$insert){$error=__LINE__;}
                                    }
                                }
                                if(!isset($error)){
                                    $query="update ".$general->table(14)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                                    $query="update ".$general->table(65)." set assignTo=0,assignTime=0 where comment_id='".$comment_id."'";
                                    $db->runQuery($query);
                                    $jArray['status']=1;$data = array(
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
                            $error=__LINE__;SetMessage(63,'Group '); 
                        }
                    }else{
                        $error=__LINE__;SetMessage(66);
                    }
                }
                else{
                    $error=__LINE__;SetMessage(63,'Comment');
                }
            }
            else{
                $jArray[__LINE__]=__LINE__;
                $c=$social->getPostInfoById($comment_id,$type);
                $jArray[__LINE__]=$c;
                if(!empty($c)){
                    $jArray[__LINE__]=__LINE__;
                    if($c['replyed']==0){
                        $ug=$db->groupInfoByID($ugID);
                        if(!empty($ug)){
                            $jArray[__LINE__]=__LINE__;
                            $users=$db->selectAll($general->table(17),'where ugID='.$ugID);
                            if(!empty($users)){
                                foreach($users as $u){
                                    $data=array(
                                        'post_id'       => $comment_id,
                                        'uID'           => $u['uID'],
                                        'created_time'  => $c['created_time'],
                                        'assign_time'   => TIME
                                    );
                                    $insert=$db->insert($general->table(5),$data);
                                    if(!$insert){$error=__LINE__;}
                                }
                                if(!isset($error)){
                                    $query="update ".$general->table(12)." set assignTo=0,assignTime=0 where post_id='".$comment_id."'";
                                    $query="update ".$general->table(64)." set assignTo=0,assignTime=0 where post_id='".$comment_id."'";
                                    $db->runQuery($query);
                                    $jArray['status']=1;$data = array(
                                        'uID'           => UID,
                                        'ugID'          => $ugID,
                                        'post_id'       => $comment_id,
                                        'transfer_time' => TIME
                                    );
                                    $db->insert($general->table(56),$data); 
                                }
                            }
                        }
                        else{
                            $error=__LINE__;SetMessage(63,'Group '); 
                        }
                    }
                    else{
                        $jArray['status']=1;
                    }
                }
                else{
                    $error=__LINE__;SetMessage(63,'Comment');
                }
            }
        }
    }else{$error=__LINE__;SetMessage(63,'Group ');}
    $jArray['m']=show_msg('Yes');
    if($jArray['status']==1){
        if($isClose==0){$jArray['nextComment']=$social->nextPicup($type);}
    }
    $general->jsonHeader($jArray);
?>
