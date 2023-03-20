<?php
    $social->messageNewSenderMsgLoad($jArray);
    /*$messageMaxService=intval($db->settingsValue('messageMaxService'));

    if($messageMaxService>0){
    $limit=' limit '.$messageMaxService;
    }
    $jArray[__LINE__]=__LINE__;
    $senders=$db->selectAll($general->table(16),'where assignTo='.UID.' and replyed=0 order by lastUpdate desc'.$limit,'','array',$jArray);
    $jArray['senders']=array();
    if(!empty($senders)){
    foreach($senders as $sender){
    $message=$db->getRowDataWithColumn($general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc",'sendTime,'.$general->mDec('text').',url');
    $sd=array(
    'sender_id'         => $sender['sender_id'],
    'senderName'        => $sender['senderName'],
    'senderPictureLink' => $sender['senderPictureLink'],
    'text'              => $message['text'],
    'url'               => $message['url'],
    'sendTime'          => date('d-m-Y h:i:s A',$message['sendTime']),
    'sendTimestamp'     => $message['sendTime'],
    );
    $jArray['senders'][]=$sd;
    }
    }
    if(count($jArray['senders'])<$messageMaxService){
    if($messageMaxService>0){
    $limit=$messageMaxService-count($jArray['senders']);
    }
    $senders=$db->selectAll($general->table(16),'where assignTo=0 and replyed=0 order by lastUpdate desc limit '.$limit,'','array',$jArray);
    $jArray[__LINE__]=$senders;
    if(!empty($senders)){
    foreach($senders as $sender){
    $message=$db->getRowDataWithColumn($general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc",'sendTime,'.$general->mDec('text').',url');
    $sd=array(
    'sender_id'         => $sender['sender_id'],
    'senderName'        => $sender['senderName'],
    'senderPictureLink' => $sender['senderPictureLink'],
    'text'              => $message['text'],
    'url'               => $message['url'],
    'sendTime'          => date('d-m-Y h:i:s A',$message['sendTime']),
    'sendTimestamp'     => $message['sendTime'],
    );
    $jArray['senders'][]=$sd;
    }
    }
    }
    if(!empty($jArray['senders'])){
    $jArray['status']=1;
    $jArray[__LINE__]=__LINE__;
    if(!empty($jArray['senders'])){
    foreach($jArray['senders'] as $sender){
    $messages=$db->selectAll($general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc",'mid,sendTime,sender_id,'.$general->mDec('text').',url,sendType');
    if(!empty($messages)){
    krsort($messages);
    foreach($messages as $m){
    $message=array(
    'mid'           => $m['mid'],
    'sendTime'      => date('d-m-Y h:i:s A',$m['sendTime']),
    'sendTimestamp' => $m['sendTime'],
    'text'          => $m['text'],
    'url'           => $m['url'],
    'sendType'      => $m['sendType'],
    );
    $jArray['message'][$sender['sender_id']][]=$message;
    }

    $data=array(
    'assignTo'  => UID,
    'assignTime'=> TIME
    );
    $where=array('sender_id'=>$sender['sender_id']);
    $update=$db->update($general->table(16),$data,$where);

    $query="
    update ".$general->table(9)." set assignTo=".UID.",assignTime=".TIME." where sender_id=".$m['sender_id']." and sendTime<=".TIME." and sendType=1 and replyed=0";
    $update=$db->runQuery($query);
    }
    }
    }
    }*/
    $general->jsonHeader($jArray);
?>