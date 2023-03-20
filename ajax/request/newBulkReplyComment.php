<?php
    $date_range=$_POST['date_range'];
    $dates=explode('__',$date_range);
    $from=strtotime($dates[0]);
    $to=strtotime($dates[1]);
    if(date('h:i',$to)=='12:00'){
        $to=strtotime('+23 hour',$to);
        $to=strtotime('+59 minute',$to);
    }

    $threads        = $_POST['threads'];
    $message        = urldecode($_POST['message']);
    $wuID           = intval($_POST['wrapupId']);
    $scentiment     = intval($_POST['scentiment']);
    if($message==''){$error=__LINE__;SetMessage(31,'Message');}
    $replyeds       = array();
    if(!isset($error)){
        if(!empty($threads)){
            foreach($threads as $comment_id){
                if($comment_id!=''){
                    $targetCommentID= $comment_id;
                    $privRep        = 0;
                    $action_like    = 0;
                    $action_hide    = 0;
                    $action_ban    = 0;
                    if(isset($_POST['privRep'])){$privRep= intval($_POST['privRep']);if($privRep!=1&&$privRep!=2)$privRep=0;}
                    if(isset($_POST['action_like'])){$action_like= intval($_POST['action_like']);if($action_like!=1)$action_like=0;}
                    if(isset($_POST['action_ban'])){$action_ban= intval($_POST['action_ban']);if($action_ban!=1)$action_ban=0;}
                    if(isset($_POST['action_hide'])){$action_hide= intval($_POST['action_hide']);if($action_hide!=1)$action_hide=0;}
                    if(!isset($error)){
                        $cm=$db->get_rowData($general->table($cTbl),'comment_id',$comment_id);
                        if(!empty($cm)){
                            if($cm['assignTo']==0){
                                $post=$db->get_rowData($general->table($pTbl),'post_id',$cm['post_id']);
                                if($post['post_id']!=$cm['parent_id']){
                                    //It's for child comment reply
                                    $comment_id=$cm['parent_id'];
                                }
                            }
                            else{
                                $error=__LINE__;//SetMessage(5,'Last comment not assign to you or another agent already reply this.');
                            }
                        }
                        else{
                            textFileWrite('['.date('d-m-Y h:i:s').']'.__FILE__.' line '.__LINE__.' id '.$comment_id,'fb_error.txt');
                        }
                    }
                    if(!isset($error)){
                        $jArray['status']=1;
                        if($privRep==0){$rqType='comments';}else{$rqType='private_replies';}
                        //$jArray[__LINE__]='c '.$comment_id.' a '.$rqType.'   '.$general->content_show($message);
                        $fb = $social->fbInit();
                        $newCommentData=array(
                            'post_id'       => $cm['post_id'],
                            'parent_id'     => $comment_id,
                            'target_c_id'   => $targetCommentID,
                            'wuID'          => $wuID,
                            'message'       => $message,
                            'scentiment'    => $scentiment,
                            'replyed'       => 1,
                            'replyBy'       => UID,
                            'replyTime'     => TIME,
                        );
                        $targetDataUpdate=array(
                            'assignTime'=> strtotime('-1 second',TIME),
                            'assignTo'  => UID,
                            'replyed'   => 1,
                            'replyed'   => 1,
                            'replyTime' => TIME,
                            'wuID'      => $wuID,
                            'replyBy'   => UID,
                            'scentiment'=> $scentiment
                        );
                        try {
                            $d=$fb->post('/'.$comment_id.'/'.$rqType,array('message'=>$general->content_show($message,'n'),));

                            if(isset($d->getDecodedBody()['id'])){
                                $newCommentID=$d->getDecodedBody()['id'];
                                if($privRep!=0){
                                    $newCommentData['message']='<b>Private</b>: '.$message;
                                }
                                $newCommentData['comment_id']=$newCommentID;
                                $newCommentData['sender_id']=PAGE_ID;
                                $newCommentData['created_time']=TIME;
                                $insert=$db->insert($general->table($cTbl),$newCommentData);
                                $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);

                                if($privRep==2){ 
                                    $social->deleteCommentByAgent($cm,$type);
                                }
                                $replyeds[$targetCommentID]=$newCommentID;
                                $data=array(
                                    'comment_id'=> $targetCommentID,
                                    'uID'       => UID
                                );
                                $insert=$db->insert($general->table(58),$data);
                            }
                            else{
                                $newCommentData['sendType']=$rqType;
                                $newCommentData['errorCode']=$e->getCode();
                                $newCommentData['errorMessage']=$e->getMessage();
                                if($privRep==2){
                                    $newCommentData['deleteTarget']=1;
                                }
                                $insert=$db->insert($general->table(41),$newCommentData);
                                $targetDataUpdate['replyed']=2;
                                $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                            }
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {
                            //$error=__LINE__;//SetMessage(5,'Last comment added to queue.');
                            $newCommentData['sendType']=$rqType;
                            $newCommentData['errorCode']=$e->getCode();
                            $newCommentData['errorMessage']=$e->getMessage();
                            if($privRep==2){
                                $newCommentData['deleteTarget']=1;
                            }
                            $insert=$db->insert($general->table(41),$newCommentData);
                            $targetDataUpdate['replyed']=2;
                            $social->commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type);
                        } 
                        catch(Facebook\Exceptions\FacebookSDKException $e) {
                            //$error=__LINE__;//SetMessage(5,'Last comment added to queue.');
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
                        if(!isset($error)){
                            if($action_like==1){
                                $jArray['like']=$social->newLike($targetCommentID,$type);
                            }
                            if($action_ban==1){
                                $jArray['ban']=$social->banFromFB($type,array($targetCommentID=>$cm['sender_id']));
                            }
                            if($action_hide==1){
                                SetMessage(4,'hide');
                                $jArray['hide']=$social->hideFromFB($targetCommentID,$type);
                            }
                        }
                    }
                }
                unset($error);
            }
        }
    }
    //$jArray['replyeds']=$replyeds;
    $rt=$social->assignmentData($type,$from,$to);
    $jArray['bulk']['comments'] = $rt['comments'];
    $jArray['bulk']['names']    = $rt['names'];
    $jArray['bulk']['post_ids'] = $rt['post_ids'];
?>
