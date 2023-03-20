<?php
    $sender_id=$_POST['messageCheckForNew'];
    $after=intval($_POST['after']);
    //সেন্ডার আর কারো কাছে এসাইন আছে কি না সেটা চেক করতে হবে
    //$jArray[__LINE__]="where sender_id='".$sender_id."' and sendTime>".$after." order by sendTime asc";
    $messages=$db->selectAll($general->table(9),"where sender_id='".$sender_id."' and sendTime>".$after." order by sendTime asc",'mid,sendTime,'.$general->mDec('text').',url,sendType');
    if(!empty($messages)){
        foreach($messages as $m){
                $attachment='';
                if(json_decode($m['url'])!=null){
                    $attachment=json_decode($m['url'],true);
                    $m['url']='';
                }
            $message=array(
                'mid'           => $m['mid'],
                'sendTime'      => date('d-m-Y h:i:s A',$m['sendTime']),
                'sendTimestamp' => $m['sendTime'],
                'text'          => $m['text'],
                'url'           => $m['url'],
                    'at'            => $attachment,
                'sendType'      => $m['sendType'],
            );
            $jArray['message'][]=$message;
        }
        $query="
        update ".$general->table(9)." set assignTo=".UID.",assignTime=".TIME." where sender_id=".$sender_id." and sendTime<=".TIME." and sendType=1 and replyed=0 and assignTo=0";
        $update=$db->runQuery($query);

        /*$data=array(
            'assignTo'  => UID,
            'assignTime'=> TIME
        );
        $where=array('sender_id'=>$sender['sender_id']);
        $update=$db->update($general->table(16),$data,$where);*/
        $jArray['status']=1;
    }
    $general->jsonHeader($jArray);
?>