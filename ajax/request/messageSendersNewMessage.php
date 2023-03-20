<?php
    $senders=$_POST['messageSendersNewMessage'];
    $jArray['message']=array();
    foreach($senders as $sender_id=>$after){
        //সেন্ডার আর কারো কাছে এসাইন আছে কি না সেটা চেক করতে হবে
        //$jArray[__LINE__]="where sender_id='".$sender_id."' and sendTime>".$after." order by sendTime asc";
        $sq='';
        if($after>1){
            $sq="and sendTime>".$after;
            $messages=$db->selectAll($general->table(9),"where sender_id='".$sender_id."' ".$sq." order by sendTime asc limit 1",'sendTime,'.$general->mDec('text').',url,sendType');
            if(!empty($messages)){
                foreach($messages as $m){
                    $attachment='';
                    if(json_decode($m['url'])!=null){
                        $attachment=json_decode($m['url'],true);
                        $m['url']='';
                    }
                    $message=array(
                        'sendTime'      => date('d-m-Y h:i:s A',$m['sendTime']),
                        'sendTimestamp' => $m['sendTime'],
                        'text'          => $m['text'],
                        'url'           => $m['url'],
                        'at'            => $attachment,
                        'sendType'      => $m['sendType'],
                    );
                    $jArray['message'][$sender_id]=$message;
                }
                $jArray['status']=1;
            }
        }
    }
    $general->jsonHeader($jArray);
?>