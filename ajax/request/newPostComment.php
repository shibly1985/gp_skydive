<?php
    $comment_id     = $_POST['comment_id'];
    $targetType     = $_POST['targetType'];
    $isClose        = intval($_POST['isClose']);if($isClose!=1)$isClose=0;
    $action_like    = 0;
    $action_hide    = 0;
    $action_ban     = 0;
    $parent         = $comment_id;
    $targetCommentID=$comment_id;
    if(isset($_POST['privRep'])){$privRep= intval($_POST['privRep']);if($privRep!=1&&$privRep!=2)$privRep=0;}
    if(isset($_POST['action_like'])){$action_like= intval($_POST['action_like']);if($action_like!=1)$action_like=0;}
    if(isset($_POST['action_ban'])){$action_ban= intval($_POST['action_ban']);if($action_ban!=1)$action_ban=0;}
    if(isset($_POST['action_hide'])){$action_hide= intval($_POST['action_hide']);if($action_hide!=1)$action_hide=0;}
    $log[__LINE__]=$general->make_date(microtime(),'time');
    if($targetType=='p'){
        if($comment_id==''){
            $error=__LINE__;
            SetMessage(63,'Post');
        }
        else{
            $post_id=$comment_id;
            $p=$social->getPostInfoById($post_id,'w');
            if(!empty($p)){
                $comment_id=$p['post_id'];
                $post_id=$comment_id;
            }
        }
    }
    elseif($targetType=='c'){
        if($comment_id==''){
            $error=__LINE__;
            SetMessage(63,'Comment');
        }
        else{
            $c=$social->getCommentInfoById($comment_id,$type);
            $jArray[__LINE__]=$general->make_date(microtime(),'time');
            if(!empty($c)){
                $p=$social->getPostInfoById($c['post_id'],'w');
                $post_id=$c['post_id'];
                if($c['parent_id']!=$post_id&&$c['parent_id']!=$p['post_id_2']){
                    $targetCommentID=$c['parent_id'];
                    $parent=$c['parent_id'];
                }
                $post_id=$p['post_id'];
            }
            else{
                $error=__LINE__;SetMessage(63,'Comment');
            }
        }
    }
    else{$error=__LINE__;SetMessage(63,'Targate type');}
    if(!isset($error)){
        $jArray['targetType']=$targetType;
        if(isset($_POST['is_done'])){
            $d=$social->commentPostDone($type,$comment_id,$jArray);//jArray required for targetType info
            $log[__LINE__]=$general->make_date(microtime(),'time');

        }
        else{
            $message        = urldecode($_POST['message']);
            $attachmentUrl  ='';
            $afID           = intval($_POST['attatchment']);
            $wuID           = intval($_POST['wrapupId']);
            $scentiment     = intval($_POST['scentiment']);
            $rqData         = array();
            //if($message==''){$error=__LINE__;SetMessage(31,'Message');}
            if($message==''&&$afID==0){$error=__LINE__;SetMessage(31,'Message');}
            else{
                if($general->content_show($message,'n')!=''){
                    $rqData=array(
                        'message' => $general->content_show($message,'n'),
                        //attachment_url
                    );
                }
            }
            if($afID>0){
                $jArray[__LINE__]=__LINE__;
                $af=$db->get_rowData($general->table(61),'afID',$afID);
                if(empty($af)){
                    SetMessage(63,'Attachment');$error=__LINE__;
                }
                elseif($af['uID']!=UID){
                    SetMessage(63,'Attachment');$error=__LINE__;
                }
                else{
                    $attachmentUrl=URL.'/attachments/'.UID.'/'.$af['afFile'];
                    $rqData['attachment_url']=$attachmentUrl;
                    $data=array('afOrder'=>TIME);
                    $where=array('afID'=>$afID);
                    $update=$db->update($general->table(61),$data,$where);
                }
                $jArray[__LINE__]=$rqData;
            }
            if(!isset($error)){
                $jArray['status']=1;
                if($action_like==1){
                    $jArray['like']=$social->newLike($comment_id,$type,$targetType);
                }
                $rqType='comments';
                $newCommentData=array(
                    'target_c_id'   => $comment_id,
                    'post_id'       => $post_id,
                    'parent_id'     => $parent,
                    'message'       => $message,
                    'photo'         => $attachmentUrl,
                    'wuID'          => $wuID,
                    'scentiment'    => $scentiment,
                    'replyed'       => 1,
                    'replyBy'       => UID,
                    'replyTime'     => TIME
                );
                $targetDataUpdate=array(
                    'replyed'=>1,
                    'replyTime'=>TIME,
                    'wuID'=>$wuID,
                    'replyBy'=>UID,
                    'scentiment'=>$scentiment
                );
                $fb = $social->fbInit();
                try {
                    $d=$fb->post('/'.$targetCommentID.'/'.$rqType,$rqData);
                    if(isset($d->getDecodedBody()['id'])){
                        $newCommentID=$d->getDecodedBody()['id'];
                        $newCommentData['comment_id']=$cm['created_time'];
                        $newCommentData['comment_id']=$newCommentID;
                        $newCommentData['sender_id']=PAGE_ID;
                        $newCommentData['created_time']=TIME;
                        $jArray['n_comment_id']=$newCommentID;
                        $jArray['status']=1;
                        $insert=$db->insert($general->table($cTbl),$newCommentData);
                        if($targetType=='p'){
                            $where=array('post_id'=>$comment_id);
                            $db->update($general->table($pTbl),$targetDataUpdate,$where);   
                            $db->update($general->table($rpTbl),$targetDataUpdate,$where);   
                        }
                        else{
                            $where=array('comment_id'=>$comment_id);
                            $db->update($general->table($cTbl),$targetDataUpdate,$where);
                            $db->update($general->table($rcTbl),$targetDataUpdate,$where);
                        }
                        textFileWrite(__FILE__.' '.__LINE__.' '.$comment_id,'replyed.txt');
                        textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($_POST),'replyed.txt');
                    }
                    else{
                        if($targetType=='c'){
                            $newCommentData['created_time']=$c['created_time'];
                        }
                        else{
                            $newCommentData['created_time']=$p['created_time'];
                        }
                        $newCommentData['targetType']=$targetType;
                        $newCommentData['sendType']=$rqType;
                        $newCommentData['errorCode']='Unknown';
                        $newCommentData['errorMessage']='Unknown';
                        if($privRep==2){
                            $newCommentData['deleteTarget']=1;
                        }
                        $insert=$db->insert($general->table(42),$newCommentData);
                        $targetDataUpdate['replyed']=2;
                        if($targetType=='p'){
                            $where=array('post_id'=>$comment_id);
                            $db->update($general->table($pTbl),$targetDataUpdate,$where);   
                            $db->update($general->table($rpTbl),$targetDataUpdate,$where);   
                        }
                        else{
                            $where=array('comment_id'=>$comment_id);
                            $db->update($general->table($cTbl),$targetDataUpdate,$where);
                            $db->update($general->table($rcTbl),$targetDataUpdate,$where);
                        }
                    }
                }
                catch(Facebook\Exceptions\FacebookResponseException $e) {
                    $a=__FILE__."/".__LINE__." \n Code ".$e->getCode()." D: ". $e->getMessage();
                    textFileWrite($a,'jsonresponse.txt');
                    if($targetType=='c'){
                        $newCommentData['created_time']=$c['created_time'];
                    }
                    else{
                        $newCommentData['created_time']=$p['created_time'];
                    }
                    $newCommentData['targetType']=$targetType;
                    $newCommentData['sendType']=$rqType;
                    $newCommentData['errorCode']=$e->getCode();
                    $newCommentData['errorMessage']=$e->getMessage();
                    if($privRep==2){
                        $newCommentData['deleteTarget']=1;
                    }
                    $insert=$db->insert($general->table(42),$newCommentData);
                    $targetDataUpdate['replyed']=2;
                    if($targetType=='p'){
                        $where=array('post_id'=>$comment_id);
                        $db->update($general->table($pTbl),$targetDataUpdate,$where);   
                        $db->update($general->table($rpTbl),$targetDataUpdate,$where);   
                    }
                    else{
                        $where=array('comment_id'=>$comment_id);
                        $db->update($general->table($cTbl),$targetDataUpdate,$where);
                        $db->update($general->table($rcTbl),$targetDataUpdate,$where);
                    }
                    /*if($e->getCode()==100){
                    $jArray['rm']=$comment_id;
                    SetMessage(4,'Graph returned an error: ' . $e->getCode().'  '.$e->getMessage());
                    $social->removeComment($comment_id,$type);
                    $jArray['status']=1;
                    }
                    else{
                    SetMessage(4,'Graph returned an error: ' . $e->getCode().'  '.$e->getMessage().' Line:'.__LINE__);
                    }*/
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
                    $a=__FILE__."/".__LINE__." \n Code ".$e->getCode()." D: ". $e->getMessage();
                    textFileWrite($a,'jsonresponse.txt');
                    if($targetType=='c'){
                        $newCommentData['created_time']=$c['created_time'];
                    }
                    else{
                        $newCommentData['created_time']=$p['created_time'];
                    }
                    $newCommentData['targetType']=$targetType;
                    $newCommentData['sendType']=$rqType;
                    $newCommentData['errorCode']=$e->getCode();
                    $newCommentData['errorMessage']=$e->getMessage();
                    if($privRep==2){
                        $newCommentData['deleteTarget']=1;
                    }
                    $insert=$db->insert($general->table(42),$newCommentData);
                    $targetDataUpdate['replyed']=2;
                    if($targetType=='p'){
                        $where=array('post_id'=>$comment_id);
                        $db->update($general->table($pTbl),$targetDataUpdate,$where);   
                        $db->update($general->table($rpTbl),$targetDataUpdate,$where);   
                    }
                    else{
                        $where=array('comment_id'=>$comment_id);
                        $db->update($general->table($cTbl),$targetDataUpdate,$where);
                        $db->update($general->table($rcTbl),$targetDataUpdate,$where);
                    }
                }
                //                if(!isset($error)){
                if($action_ban==1){
                    if($db->permission(PER_BAN_USER)==true){
                        $banData=array($comment_id=>array($c['sender_id'],$targetType));
                        $jArray['ban']=$social->banFromFB($type,$banData);
                    }
                    else{
                        $jArray['ban']=false;
                    }
                }
                if($action_hide==1){
                    $jArray['hide']=$social->hideFromFB($comment_id,$type,$targetType);
                }
                //                }
            }
        }
        if($jArray['status']==1){

        }
    }
    if($isClose==0){
        if(isset($error)){
            $jArray['err']=$error;
        }
        $jArray['status']=1;
        //textFileWrite(json_encode($log));
        $jArray['nextComment']=$social->nextPicup($type);
    }
?>
