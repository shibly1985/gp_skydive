<?php
    $type=$_POST['type'];if($type!='c')$type='w';
    $comment_id     = $_POST['comment_id'];
    if($comment_id==''){$error=__LINE__;if($ctType=='w'){SetMessage(63,'Comment');}else{SetMessage(63,'Post');}}
    if(!isset($error)){
        $social->newLike($comment_id,$type);
        /*if($ctType=='c'){
            $c=$db->get_rowData($general->table(13),'comment_id',$comment_id);
            if(!empty($c)){
                $lData=$db->get_rowData($general->table(27),'comment_id',$comment_id);
                if(empty($lData)){
                    $fb=$social->fbInit();
                    try {
                        $jArray['status']=1;
                        $d=$fb->post('/'.$comment_id.'/likes', 
                            array(
                                'access_token' => ACCESS_TOKEN
                        ));
                        $data=array(
                            'comment_id'=> $comment_id,
                            'likeTime'  => TIME,
                            'uID'       => UID
                        );
                        $insert=$db->insert($general->table(27),$data);
                    }
                    catch(Facebook\Exceptions\FacebookResponseException $e) {
                        SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage());
                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                        SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage()); 
                    }
                }
            }
        }
        elseif($ctType=='w'){
            $c=$db->get_rowData($general->table(12),'post_id',$comment_id);
            if(!empty($c)){
                $lData=$db->get_rowData($general->table(28),'post_id',$comment_id);
                if(empty($lData)){
                    $fb=$social->fbInit();
                    try {
                        $jArray['status']=1;
                        $d=$fb->post('/'.$comment_id.'/likes', 
                            array(
                                'access_token' => ACCESS_TOKEN
                        ));
                        $data=array(
                            'post_id'=> $comment_id,
                            'likeTime'  => TIME,
                            'uID'       => UID
                        );
                        $insert=$db->insert($general->table(28),$data);
                    }
                    catch(Facebook\Exceptions\FacebookResponseException $e) {
                        SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage());
                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                        SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage()); 
                    }
                }
            }
        }*/
    }
    $jArray['m']=show_msg('Yes');
    $general->jsonHeader($jArray);
?>
