<?php  
    $jArray['status']=0;
    $comment_id = $_POST['commentEdit'];
    $jArray[__LINE__]=$_POST['message'];
    $message    = urldecode($_POST['message']);
    $jArray[__LINE__]=$message;
    $targetType = @$_POST['targetType'];
    if($targetType!='c'){$targetType='w';}
    $type       = $_POST['type'];
    $columnID = 'comment_id';
    if($type=='c'){
        $tbl=13;
        $rtbl=63;
    }
    else if($type=='w'){
        if($targetType=='p'){
            $tbl=12;
            $rtbl=64;
            $columnID = 'post_id';
        }
        else{
            $tbl=14;    
            $rtbl=65;    
        }

    }
    $comm = $db->get_rowData($general->table($tbl),$columnID,$comment_id);
    if(!empty($comm)){
        $fb=$social->fbInit();
        try{
            $d=$fb->post('/'.$comment_id.'/?message='.urlencode($message));
            if(isset($d->getDecodedBody()['success'])){
                $jArray['status']=1;
                $data=array(
                    'message'=>$message
                );
                $where=array(
                    $columnID=>$comment_id
                );
                $db->update($general->table($tbl),$data,$where);
                $db->update($general->table($rtbl),$data,$where);
                $jArray['message']=$general->content_show($message);
            }
        }
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
        }
    }
    else{

    }
    $jArray['m']=show_msg('yes');
    $general->jsonHeader($jArray);
?>