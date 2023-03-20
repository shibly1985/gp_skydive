<?php

    $comment_id     = $_POST['comment_id'];
    $targetCommentID= $comment_id;
    $isClose        = $_POST['isClose'];if($isClose!=1)$isClose=0;
    $privRep        = 0;
    $action_like    = 0;
    $action_hide    = 0;
    $action_ban    = 0;
    if(isset($_POST['privRep'])){$privRep= intval($_POST['privRep']);if($privRep!=1&&$privRep!=2)$privRep=0;}
    if(isset($_POST['action_like'])){$action_like= intval($_POST['action_like']);if($action_like!=1)$action_like=0;}
    if(isset($_POST['action_ban'])){$action_ban= intval($_POST['action_ban']);if($action_ban!=1)$action_ban=0;}
    if(isset($_POST['action_hide'])){$action_hide= intval($_POST['action_hide']);if($action_hide!=1)$action_hide=0;}
    if($comment_id==''){$error=__LINE__;SetMessage(63,'Comment');}
    if(!isset($error)){
        $times[__LINE__]=microtime().' '.date('h:i:s');
        $cm=$db->get_rowData($general->table($cTbl),'comment_id',$comment_id);
        if(!empty($cm)){
            if($cm['assignTo']==UID){
                $times[__LINE__]=microtime().' '.date('h:i:s');
                $post=$db->get_rowData($general->table($pTbl),'post_id',$cm['post_id']);
                if($post['post_id']!=$cm['parent_id']){
                    //It's for child comment reply
                    $comment_id=$cm['parent_id'];
                }
            }
            else{
                $times[__LINE__]=microtime().' '.date('h:i:s');
                $jArray[__LINE__]=__LINE__;
                $jArray['status']=1;
                textFileWrite('['.date('d-m-Y h:i:s').']'.__FILE__.' line '.__LINE__.' UID '.UID.' js'.json_encode($cm),'fb_error.txt');
                $error=__LINE__;//SetMessage(5,'Last comment not assign to you or another agent already reply this.');
            }
        }
        else{
            $times[__LINE__]=microtime().' '.date('h:i:s');
            $jArray[__LINE__]=__LINE__;
            textFileWrite('['.date('d-m-Y h:i:s').']'.__FILE__.' line '.__LINE__.' id '.$comment_id,'fb_error.txt');
            $ingn==1;//it's only for gp and temporary
            //            $error=__LINE__;SetMessage(63,'comment. Line '.__LINE__);
        }
    }
    if(!isset($error)){
        $times[__LINE__]=microtime().' '.date('h:i:s');
        //$jArray[__LINE__]=__LINE__;
        if(isset($_POST['is_done'])){
            $times[__LINE__]=microtime().' '.date('h:i:s');
            $d=$social->commentPostDone('c',$targetCommentID);//whern done then we need to action in main comment
            $jArray['status']=1;
        }
        else{
            $times[__LINE__]=microtime().' '.date('h:i:s');
            //$jArray[__LINE__]=$_POST['message'];
            $message        = urldecode($_POST['message']);
            $attachmentUrl  ='';
            $afID           = intval($_POST['attatchment']);
            //$jArray[__LINE__]=$message;
            $rqData         = array();
            $wuID           = intval($_POST['wrapupId']);
            $scentiment     = intval($_POST['scentiment']);
            if($message==''&&$afID==0){$error=__LINE__;SetMessage(31,'Message');}
            else{
                $times[__LINE__]=microtime().' '.date('h:i:s');
                if($general->content_show($message,'n')!=''){
                    $rqData=array(
                        'message' => $general->content_show($message,'n'),
                        //attachment_url
                    );
                }
            }
            if($afID>0){
                $times[__LINE__]=microtime().' '.date('h:i:s');
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
                $times[__LINE__]=microtime().' '.date('h:i:s');
            }

            if(!isset($error)){
                $times[__LINE__]=microtime().' '.date('h:i:s');
                //$jArray[__LINE__]=__LINE__;
                $jArray['status']=1;
                if($action_like==1){
                    $jArray['like']=$social->newLike($targetCommentID,$type);
                }
                if($privRep==0){$rqType='comments';}else{$rqType='private_replies';}
                //$jArray[__LINE__]='c '.$comment_id.' a '.$rqType.'   '.$general->content_show($message);
                $fb = $social->fbInit();
                if(
                    $cm['post_id']==$comment_id
                ){
                    textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".json_encode($_POST));
                }
                $newCommentData=array(
                    'post_id'       => $cm['post_id'],
                    'parent_id'     => $comment_id,
                    'target_c_id'   => $targetCommentID,
                    'wuID'          => $wuID,
                    'message'       => $general->content_show($message,'n'),
                    'scentiment'    => $scentiment,
                    'photo'         => $attachmentUrl,
                    'replyed'       => 1,
                    'replyBy'       => UID,
                    'replyTime'     => TIME,
                );
                $targetDataUpdate=array(
                    'replyed'   => 1,
                    'replyTime' => TIME,
                    'wuID'      => $wuID,
                    'replyBy'   => UID,
                    'scentiment'=> $scentiment
                );
                try {
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    //$jArray[__LINE__]=$rqData;
                    $d=$fb->post('/'.$comment_id.'/'.$rqType,
                        $rqData
                    );
                    $jArray[__LINE__]=$d->getDecodedBody();
                    if(isset($d->getDecodedBody()['id'])){
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        $newCommentID=$d->getDecodedBody()['id'];

                        $query="delete from ".$general->table(44)." where comment_id='".$targetCommentID."'";
                        $db->runQuery($query);//this query for cleare multiple reply


                        $jArray['n_comment_id']=$newCommentID;
                        if($privRep!=0){
                            $newCommentData['message']='<b>Private</b>: '.$general->content_show($message,'n');
                        }
                        $newCommentData['created_time']=$cm['created_time'];
                        $newCommentData['comment_id']=$newCommentID;
                        $newCommentData['sender_id']=PAGE_ID;
                        $newCommentData['created_time']=TIME;
                        $insert=$db->insert($general->table($cTbl),$newCommentData);
                        $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                        if($privRep==2){ 
                            $social->deleteCommentByAgent($cm,$type);
                        }
                    }
                    else{
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        $jArray[__LINE__]=$d;
                        //$error=__LINE__;//SetMessage(5,'Last comment added to queue.');
                        $newCommentData['created_time']=$cm['created_time'];
                        $newCommentData['sendType']=$rqType;
                        $newCommentData['errorCode']='Unknown';
                        $newCommentData['errorMessage']='Unknown';
                        if($privRep==2){
                            $newCommentData['deleteTarget']=1;
                        }
                        $insert=$db->insert($general->table(41),$newCommentData);
                        $targetDataUpdate['replyed']=2;
                        $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                        /*$error=__LINE__;SetMessage(66);*/
                    }
                }
                catch(Facebook\Exceptions\FacebookResponseException $e) {
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    $jArray[__LINE__]=$e->getCode().' - '.$e->getMessage();
                    //$error=__LINE__;//SetMessage(5,'Last comment added to queue.');
                    $newCommentData['created_time']=$cm['created_time'];
                    $newCommentData['sendType']=$rqType;
                    $newCommentData['errorCode']=$e->getCode();
                    $newCommentData['errorMessage']=$e->getMessage();
                    if($privRep==2){
                        $newCommentData['deleteTarget']=1;
                    }
                    $insert=$db->insert($general->table(41),$newCommentData);
                    $targetDataUpdate['replyed']=2;
                    $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                    /*if($e->getCode()==100){
                    $jArray['rm']=$comment_id;
                    SetMessage(4,'Graph returned an error: ' . $e->getCode().'  '.$e->getMessage());
                    $social->removeComment($comment_id,$type);
                    $jArray['status']=1;
                    }
                    else{
                    $error=__LINE__;
                    $er=__FILE__.'  Line '.__LINE__.'. Graph returned an error: ' . $e->getCode().'  '.$e->getMessage();
                    textFileWrite($er.' data '.serialize($_POST),'fb_error.txt');
                    SetMessage(4,'Graph return error. Please click sending again '.$er);
                    }*/
                } 
                catch(Facebook\Exceptions\FacebookSDKException $e) {
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    //$jArray[__LINE__]=__LINE__;
                    //$error=__LINE__;//SetMessage(5,'Last comment added to queue.');
                    $newCommentData['created_time']=$cm['created_time'];
                    $newCommentData['sendType']=$rqType;
                    $newCommentData['errorCode']=$e->getCode();
                    $newCommentData['errorMessage']=$e->getMessage();
                    if($privRep==2){
                        $newCommentData['deleteTarget']=1;
                    }
                    $insert=$db->insert($general->table(41),$newCommentData);
                    $targetDataUpdate['replyed']=2;
                    $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                    /*$er=__FILE__.'  Line '.__LINE__.'. Facebook SDK returned an error: ' . $e->getCode().'  '.$e->getMessage();
                    textFileWrite($er.' data '.serialize($_POST),'fb_error.txt');
                    SetMessage(4,$er); */
                }
            }
            if(!isset($error)){
                $times[__LINE__]=microtime().' '.date('h:i:s');
                //$jArray[__LINE__]=__LINE__;
                if($action_ban==1){
                    if($db->permission(PER_BAN_USER)==true){
                        $jArray['ban']=$social->banFromFB($type,array($targetCommentID=>$cm['sender_id']));
                    }
                    else{
                        $jArray['ban']=false;
                    }
                }
                if($action_hide==1){
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    //SetMessage(4,'hide');
                    $jArray['hide']=$social->hideFromFB($targetCommentID,$type);
                }
            }
        }
    }
    if($jArray['status']==1||isset($ingn)){
        $times[__LINE__]=microtime().' '.date('h:i:s');
        //$jArray[__LINE__]=__LINE__;
        if($jArray['status']==0){$jArray['status']=1;}
        if($isClose==0){$jArray['nextComment']=$social->nextPicup($type);}
        $times[__LINE__]=$jArray['nextComment']['times'];
        $times[__LINE__]=microtime().' '.date('h:i:s');
        textFileWrite(json_encode($times),'sendTime.txt');
    }

?>
