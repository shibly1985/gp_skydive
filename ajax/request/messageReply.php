<?php
    $times=array();
    $times[__LINE__]=__DIR__.'/'.__FILE__;
    $times[__LINE__]=microtime().' '.date('h:i:s');
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
    $sender_id=$_POST['messageReply'];
    $message    = trim(urldecode($_POST['message']));
    /*if(UID==44){
    echo $message;echo"\n"; 
    print_r($_POST);exit;
    }*/
    $wrapupId   = intval($_POST['wrapupId']);
    $scentiment = intval($_POST['scentiment']);
    $afID= intval($_POST['attatchment']);
    $mid            = $_POST['mid'];
    $sendtype       = $_POST['sendtype'];if($sendtype!=1&&$sendtype!=2)$sendtype=3;//1=close 2=continue 3 wrapup

    $times[__LINE__]=microtime();
    if(empty($message)&&$afID==0){if(!isset($_POST['isDone'])){$error=__LINE__;SetMessage(31,'Message');}}
    if($sender_id==''){$error=__LINE__;SetMessage(63,'Sender');}
    else{
        $sender=$db->get_rowData($general->table(16),'sender_id',$sender_id);
    }
    $times[__LINE__]=microtime();
    if($mid==''){$error=__LINE__;SetMessage(63,'Message');}
    else{
        $jArray[__LINE__]=__LINE__;
        $m=$db->get_rowData($general->table(9),'mid',$mid);
        if(empty($m)){$error=__LINE__;SetMessage(63,'Message');}
        elseif($sender_id!=$m['sender_id']){$error=__LINE__;SetMessage(63,'Message');}
        $jArray[__LINE__]=__LINE__.' - '.$m['sender_id'];
    }
    $times[__LINE__]=microtime();
    $jArray[__LINE__]=__LINE__;
    $attachment='';
    $attachmentUrl='';

    $times[__LINE__]=microtime();
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
            $attachmentUrl[]=array(
                'type'=>'image',
                'url'=>URL.'/attachments/'.UID.'/'.$af['afFile']
            );
            $attachment=array(
                'type'=>'image',
                'payload'=>array(
                    'url'=>URL.'/attachments/'.UID.'/'.$af['afFile']
                    //'is_reusable'=>true
                )
            );
            $data=array('afOrder'=>TIME);
            $where=array('afID'=>$afID);
            $update=$db->update($general->table(61),$data,$where);
        }
        $jArray[__LINE__]=__LINE__;
    }
    $times[__LINE__]=microtime();
    if(!isset($error)){
        $jArray[__LINE__]=__LINE__;
        if(isset($_POST['isDone'])){
            $times[__LINE__]=microtime();
            $query="
            update ".$general->table(9)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",isDone=1 where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
            ";
            $update=$db->runQuery($query,'array',$jArray);
            $times[__LINE__]=microtime();
            $query="
            update ".$general->table(66)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",isDone=1 where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
            ";
            $update=$db->runQuery($query,'array',$jArray);
            $times[__LINE__]=microtime();
            /*if($update==true){
            $jArray[__LINE__]=$update;
            }*/
        }
        else{
            $times[__LINE__]=microtime();
            $jArray[__LINE__]=__LINE__;
            $nm=$general->messageSplit($message,$attachment);
            foreach($nm as $message){
                //$jArray[__LINE__][]=$general->content_show($message,'n');
                $bot = new FbBotApp(ACCESS_TOKEN);    
                //$d=$bot->send(new Message($sender_id,$message),$jArray);
                /*$attatchment=array(
                'type'=>'image',
                'payload'=>array(
                'url'=>'https://skydivebd.net/gp/images/skydive_logo.png',
                'is_reusable'=>true
                )
                );*/
                /*$attatchment='';*/
                if(!is_array($message)){
                    $attachment='';
                    $messageR=$general->content_show($message,'n');
                }
                else{
                    $attachment=$message;
                    $messageR='';
                }
                $jArray[__LINE__]=$sender_id;
                $jArray[__LINE__]=$messageR;
                $jArray[__LINE__]=$attachment;
                $times[__LINE__]=microtime();
                $d=$bot->send(new Message($sender_id,$messageR,$attachment),$jArray);
                $jArray[__LINE__][]=$d;
                if(isset($d['recipient_id'])){
                    $times[__LINE__]=microtime();
                    $jArray['recipient_id']=$d['recipient_id'];
                    $data=array(
                        'mid'       => $d['message_id'],
                        'sender_id' => $sender_id,
                        'sendType'  => 2,
                        'targetSeq' => $m['seq'],
                        'wuID'      => $wrapupId,
                        'scentiment'=> $scentiment,
                        'sendTime'  => TIME,
                        'url'       => json_encode($attachmentUrl),
                        'text'      => $general->mEnc($message),
                    );
                    $insert=$db->insertEnc($general->table(9),$data);
                }
                else{
                    $times[__LINE__]=microtime();
                    if($d['error']['code']!=230&&$d['error']['code']!=551&&$d['error']['code']!=10){
                        //(#10) This message is sent outside of allowed window. You need page_messaging_subscriptions permission to be able to do it
                        $error=__LINE__;
                        textFileWrite(__FILE__.' L '.__LINE__.' T '.date('d-m-y h:i:s').' notice '. json_encode($d)." \n".
                            "Code ".$d['error']['code']
                            .
                            "\n m ".json_encode($_POST)
                            ."\n".json_encode($nm)
                        );

                        $jArray[__LINE__]=json_encode($d);
                    }
                    else{
                        /*textFileWrite(__FILE__.' L '.__LINE__.' T '.date('d-m-y h:i:s').' notice '. json_encode($d)." \n".
                        "Code ".$d['error']['code']
                        .
                        "\n m ".json_encode($_POST)
                        ."\n".json_encode($nm)
                        );*/
                    }
                }
            }
        }
        if(!isset($error)){
            $times[__LINE__]=microtime();
            $query="
            update ".$general->table(9)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",wuID=".$wrapupId.",scentiment=".$scentiment." where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
            ";
            $update=$db->runQuery($query);
            $query="
            update ".$general->table(66)." set replyed=1,replyTime=".TIME.",replyBy=".UID.",wuID=".$wrapupId.",scentiment=".$scentiment." where sender_id='".$sender_id."' and seq<='".$m['seq']."' and replyed=0
            ";
            $update=$db->runQuery($query);

            $times[__LINE__]=$query;
            $times[__LINE__]=microtime();
            //$jArray[__LINE__]=__LINE__;
            $jArray['status']=1;
            if($sendtype==1||$sendtype==3||isset($_POST['isDone'])){//কন্টিনিউ ছাড়া সব সময় ইউজার ক্লোজ
                $data=array(
                    'assignTo'=>0,
                    'assignTime'=>0,
                    'replyed'=>1,
                    'replyTime'=>TIME,
                    'replyBy'=>UID
                );
                if(isset($_POST['isDone'])){
                    $data['isDone']   =1;
                }
                $where=array('sender_id'=>$sender_id);
                $update=$db->update($general->table(16),$data,$where,'array');
                $times[__LINE__]=$update;
                $update=$db->update($general->table(67),$data,$where,'array');
                $times[__LINE__]=$update;
                $times[__LINE__]=microtime();
                //$jArray[__LINE__]=$update;
                $jArray['close']=$sender_id;
            }
            $times[__LINE__]=microtime();
        }
    }
    $times[__LINE__]=microtime();
    if(isset($error)){
        $jArray[__LINE__]=$error;
    }
    $times[__LINE__]=microtime().' '.date('h:i:s');
    //$times[__LINE__]=$jArray;
    $jArray['times']=$times;
    $jArray['m']=show_msg('yes');
    //textFileWrite(json_encode($times).',');
    //textFileWrite(__FILE__.' L '.__LINE__.' T '.date('d-m-y h:i:s').' jArray '.json_encode($jArray));
    $general->jsonHeader($jArray);
?>
