<?php
    $scqID=intval($_POST['outboxRetry']);
    $type=$_POST['type'];
    $jArray=array('status'=>0);
    if($type=='c'){
        $scq=$db->get_rowData($general->table(41),'scqID',$scqID);
        if(!empty($scq)){
            if($scq['sendSuccess']==0){
                if($scq['totalTry']<OUTBOX_MAX_RETRY){
                    $jArray=$social->commentQueueResend($scq);
                }
                else{
                    $jArray['e']="It's already tryed too many times";
                }
            }
            else{
                $jArray['status']=1;
                $jArray['e']="It's already send successfully";
            }
        }
        else{
            $jArray['e']="May be it's successfully send before or invalid request.";
        }
    }
    $general->jsonHeader($jArray);
?>