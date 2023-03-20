<?php
    require_once(ROOT_PATH. '/vendor/autoload.php');
    use pimax\FbBotApp;
    use pimax\Messages\Message;
    use pimax\Messages\ImageMessage;
    use pimax\UserProfile;
    use pimax\Messages\MessageButton;
    use pimax\Messages\StructuredMessage;
    use pimax\Messages\MessageElement;
    use pimax\Messages\MessageReceiptElement;
    use pimax\Messages\Address;
    use pimax\Messages\Summary;
    use pimax\Messages\Adjustment;
    $jArray[__LINE__]=__LINE__;
    $message    = urldecode($_POST['message']);
    $mid        = $_POST['mid'];
    $wrapupId   = intval($_POST['wrapupId']);
    $scentiment = intval($_POST['scentiment']);
    $isClose    = $_POST['isClose'];if($isClose!=1)$isClose=0;
    if(empty($message)){if(!isset($_POST['is_done'])){$error=__LINE__;SetMessage(31,'Message');}}
    if($mid==''){$error=__LINE__;SetMessage(63,'Message');}
    else{
        $jArray[__LINE__]=__LINE__;
        $m=$db->get_rowData($general->table(9),'mid',$mid);
        if(empty($m)){$error=__LINE__;SetMessage(63,'Message');}
        else{$sender_id=$m['sender_id'];}
    }
    if(!isset($error)){
        $nextCheck=$db->getRowData($general->table(9),"where sender_id='".$sender_id."' and sendType=1 and replyed=0 and seq>".$m['seq']);
        if(isset($_POST['is_done'])){
            $query="
            update ".$general->table(9)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",isDone=1 where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
            ";
            $update=$db->runQuery($query,'array',$jArray);
            if($update==true){
                $jArray[__LINE__]=__LINE__;
                $jArray['status']=1;
            }
            if(empty($nextCheck)){
                $jArray[__LINE__]=__LINE__;
                $data=array('replyed'=>1,'sendTime' => TIME,'lastUpdate'=>TIME,'assignTo'=>0,'replyTime'=>TIME,'replyBy'=>UID);
                $where=array('sender_id'=>$sender_id);
                $update=$db->update($general->table(16),$data,$where);
            }
            else{
                $data=array('assignTo'=>0);
                $where=array('sender_id'=>$sender_id);
                $update=$db->update($general->table(16),$data,$where);

                $data=array('isDone'=>1);
                $where=array('mid'=>$mid);
                $update=$db->update($general->table(9),$data,$where,'array');
                //$update=$db->update($general->table(9),$data,$where);
                $jArray[__LINE__]=$update;
                $jArray['next']=$nextCheck;
            }
            if($isClose==0){$jArray['nextPicup']=$social->nextPicup('m');}
        }
        else{
            $jArray[__LINE__]=__LINE__;
            $nm=$general->messageSplit($message);

            //$jArray[__LINE__]=$nm;
            foreach($nm as $message){
                //$jArray[__LINE__][]=$message;
                $bot = new FbBotApp(ACCESS_TOKEN);    
//                $d=$bot->send(new Message($sender_id,$message),$jArray);
                $sender_id='1348243711915939';
                $attatchment=array(
                    'type'=>'image',
                    'payload'=>array(
                        'url'=>'https://skydivebd.net/gp/images/skydive_logo.png',
                        'is_reusable'=>true
                    )
                );
                $attatchment='';
                $d=$bot->send(new Message($sender_id,$message,$attatchment),$jArray);
                //$jArray[__LINE__][]=$d;
                if(isset($d['recipient_id'])){
                    $jArray['recipient_id']=$d['recipient_id'];
                    $data=array(
                        'mid'       => $d['message_id'],
                        'sender_id' => $sender_id,
                        'sendType'  => 2,
                        'targetSeq' => $m['seq'],
                        'wuID'      => $wrapupId,
                        'scentiment'=> $scentiment,
                        'sendTime'  => TIME,
                        'text'      => $message,
                    );
                    $insert=$db->insert($general->table(9),$data);
                    //            $jArray[__LINE__]=$insert;
                    $query="
                    update ".$general->table(9)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",wuID=".$wrapupId.",scentiment=".$scentiment." where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
                    ";
                    $update=$db->runQuery($query,'array');
                    $jArray['query']=$update;
                    if(empty($nextCheck)){
                        $data=array('replyed'=>1,'sendTime' => TIME,'lastUpdate'=>TIME,'replyTime'=>TIME,'replyBy'=>UID);
                        $where=array('sender_id'=>$sender_id);
                        $update=$db->update($general->table(16),$data,$where);
                    }
                    else{
                        $data=array('assignTo'=>0);
                        $where=array('sender_id'=>$sender_id);
                        $update=$db->update($general->table(16),$data,$where);
                        $jArray['next']=$nextCheck;
                    }
                }
                else{
                    textFileWrite(__FILE__.' L '.__LINE__.' T '.date('d-m-y h:i:s').' notice '. json_encode($d)." \n".
                        "Code ".$d->error->code
                        .
                        "\n m ".$message.' Length '.strlen($message));
                    $jArray[__LINE__]=json_encode($d);}
            }
        }
    }

    if(isset($error)){SetMessage(4,'Error line '.$error);}
    else{
        $jArray['status']=1;
        if($isClose==0){$jArray['nextPicup']=$social->nextPicup('m');}
    }
    //    else{$jArray['ms']=__LINE__;}
    $jArray['m']=show_msg('yes');
    $general->jsonHeader();
    echo json_encode($jArray);
?>
