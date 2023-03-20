<?php
    //    header('Content-Type: text/html; charset=utf-8');
    //    date_default_timezone_set('Asia/Dhaka');
    //    $db = new mysqli('localhost','pureheal_fbapp','X38VyQklEgN','pureheal_fbapp');
    //    mysqli_set_charset($db,'utf8');


    $verify_token = "X38VyQklEgN"; // Verify token
    require_once(dirname(__FILE__) . '/vendor/autoload.php');
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
    // Make Bot Instance

    function textFileWriteNew($data,$fileName="error.txt"){
        $handle = fopen($fileName, 'a');
        if(is_array($data)){
            foreach($data as $key=>$p){
                $new_data = $key.'->'.$p."\n";
                fwrite($handle, $new_data);
            }
        }
        else{fwrite($handle, $data."\n");}
        fclose($handle);
    }
//    textFileWriteNew('hi'.time(),'jsonresponse.txt');
    if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
        // Webhook setup request
        echo $_REQUEST['hub_challenge'];
    }
    else {
        $data = json_decode(file_get_contents("php://input"), true);
//        textFileWriteNew(array(__LINE__ => json_encode($data)),'jsonresponse.txt');
        if(!empty($data)){
            foreach($data as $i){
                //textFileWriteNew(array('type'.__LINE__ => gettype($i)),'jsonresponse.txt');
                //textFileWriteNew(array('type'.__LINE__ => json_encode($i)),'jsonresponse.txt');
                if(gettype($i)!=='string'){
                    foreach($i as $ch){
                        if(isset($ch['changes'])){
                            //textFileWriteNew('data'.__LINE__.'  '.json_encode($ch));
                            foreach($ch['changes'] as $c){
                                //textFileWriteNew(array('status'.__LINE__ => $c['value']['item']),'jsonresponse.txt');
                                //echo $c['value']['item'];echo'<br>'; 
                                if(isset($c['value']['item'])){
                                    if($c['value']['item']=='status'||$c['value']['item']=='post'||$c['value']['item']=='photo'||$c['value']['item']=='video'){
                                        include_once("class/class.db.php");
                                        include_once("class/class.general.php");
                                        include_once("class/class.social.php");
                                        $db     = new DB();
                                        $general= new General();
                                        $social = new social();
                                        include("init.php");
                                        if(isset($c['value']['from'])){
                                            $c['value']['sender_id']=$c['from']['id'];
                                            $c['value']['sender_name']=$c['from']['id'];
                                        }
                                        $social->newPost($c,$ch['time']);
                                        $social->senderNameUpdate($c['value']['sender_id'],$c['value']['sender_name']);
                                        //                                        textFileWriteNew(__LINE__.' - '.$c['value']['sender_id'].'-'.$c['value']['sender_name']);
                                    }
                                    elseif($c['value']['item']=='comment'){
                                        //textFileWriteNew(json_encode($c));
                                        include_once("class/class.db.php");
                                        include_once("class/class.general.php");
                                        include_once("class/class.social.php");
                                        include_once("class/class.social2.php");
                                        $db= new DB();
                                        $general = new General();
                                        $social = new social();
                                        include_once("init.php");
                                        $fb = $social->fbInit();
                                        $return=array();
                                        $doned=0;
                                        $tCal=0;
                                        //textFileWriteNew('data'.__FILE__.' - '.__LINE__.'  '.json_encode($c));
                                        while($doned==0){
                                            $doned=1;
                                            try {
                                                $c['value']['post_sender_id']=PAGE_ID;
                                                $response = $fb->get('/'.$c['value']['post_id'].'/?fields=from');
                                                if(isset($response->getDecodedBody()['from']['id'])){
                                                    $c['value']['post_sender_id']=$response->getDecodedBody()['from']['id'];
                                                }

                                                if(!isset($c['value']['photo'])){
                                                    if($c['value']['verb']!='remove'){
                                                        try {
                                                            $response2 = $fb->get('/'.$c['value']['comment_id'].'/?fields=id,attachment');
                                                            if(isset($response2->getDecodedBody()['attachment']['media']['image']['src'])){
                                                                $c['value']['photo']=$response2->getDecodedBody()['attachment']['media']['image']['src'];
                                                            }
                                                        } 
                                                        catch(Facebook\Exceptions\FacebookResponseException $e) {
                                                            if(PROJECT=='gp'){
                                                                if(intval($d->getCode())!=100){
                                                                    $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().' -'.intval($d->getCode()).'-  '.$e->getMessage()."\n".json_encode($c);
                                                                    textFileWriteNew($er,'fb_error.txt');
                                                                }
                                                            }
                                                            else{
                                                                //if(intval($d->getCode())!=100){
                                                                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().' -'.intval($d->getCode()).'-  '.$e->getMessage()."\n".json_encode($ch);
                                                                textFileWriteNew($er,'fb_error.txt');
                                                                //}
                                                            }
                                                            exit;
                                                        }
                                                        catch(Facebook\Exceptions\FacebookSDKException $e) {
                                                            if(intval($d->getCode)!=100){
                                                                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n".json_encode($c);
                                                                textFileWriteNew($er,'fb_error.txt');
                                                            }
                                                            exit;
                                                        }
                                                    }
                                                }
                                                $v=$c['value'];
                                                if($v['post_id']!=$v['parent_id']){
                                                    $parentArray=explode('_',$v['parent_id']);
                                                    if($parentArray[0]==PAGE_ID){
                                                        $c['value']['parent_id']=$v['post_id'];
                                                    }
                                                }
                                                if(strlen($c['value']['from']['id'])>0){
                                                    $c['value']['sender_id']=$c['value']['from']['id'];
                                                    $c['value']['sender_name']=$c['value']['from']['name'];
                                                }
                                                else{
                                                    //textFileWriteNew('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.' - '.json_encode($c['value']['from']['id']));
                                                }
                                                $social->newCommenti($c);
                                                textFileWriteNew('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.' - '.json_encode($c));
                                                $social->senderNameUpdate($c['value']['sender_id'],$c['value']['sender_name']);
                                                //textFileWriteNew(__LINE__.' - '.$c['value']['sender_id'].'-'.$c['value']['sender_name']);
                                            }
                                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                                if(intval($d->getCode())==28&&$tCal<4){
                                                    $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().' Recall '.$tCal;
                                                    textFileWriteNew($er,'fb_error.txt');
                                                    sleep(1);
                                                    
                                                    $doned=0;
                                                    $tCal++;
                                                }
                                                else{
                                                    if(intval($d->getCode())!=100){
                                                        $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n".json_encode($c);
                                                        textFileWriteNew($er,'fb_error.txt');
                                                    }
                                                    exit;
                                                }
                                            }
                                            catch(Facebook\Exceptions\FacebookSDKException $e) {
                                                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n".json_encode($c);
                                                textFileWriteNew($er,'fb_error.txt');
                                                exit;
                                            }
                                        }
                                    }
                                    elseif($c['value']['item']=='like'){}
                                    elseif($c['value']['item']=='reaction'){}
                                    else{
                                        textFileWriteNew('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.' - '.' '.json_encode($c),'jsonresponse.txt');
                                    }
                                }
                            }
                        }
                        else if(isset($ch['messaging'])){
                            include_once("class/class.db.php");
                            include_once("class/class.general.php");
                            include_once("class/class.social.php");
                            $db = new DB();
                            $general = new General();
                            $social = new social();
                            include_once("init.php");
                            if(OPERATION_MESSAGE_ALLWO==false){exit;}
                            $m=$ch['messaging'][0];
                            if(isset($m['message'])){
                                textFileWriteNew(json_encode($m),'message.txt');
                                $sender_id=$m['sender']['id'];

                                $sender=$db->get_rowData($general->table(16),'sender_id',$sender_id);
                                if(empty($sender)){
                                    $bot = new FbBotApp(ACCESS_TOKEN);
                                    $user = $bot->userProfile($sender_id);
                                    $senderName = $user->getFirstName()." ".$user->getLastName();
                                    $senderPictureLink = $user->getPicture();
                                    $data=array(
                                        'sender_id'         => $sender_id,
                                        'senderName'        => $senderName,
                                        'senderPictureLink' => $senderPictureLink,
                                        'sendTime'          => TIME,
                                        'lastUpdate'        => TIME
                                    );
                                    $db->insert($general->table(16),$data);
                                    $db->insert($general->table(67),$data);

                                    //textFileWriteNew(__FILE__.' line '.__LINE__ .$insert);
                                }
                                else{
                                    $oSender=$db->get_rowData($general->table(67),'sender_id',$sender_id);
                                    if(empty($oSender)){
                                        $data=array(
                                            'sender_id'         => $sender_id,
                                            'sendTime'          => TIME,
                                            'lastUpdate'        => TIME
                                        );
                                        $db->insert($general->table(67),$data);
                                    }
                                    else{
                                        $data=array(
                                            'sendTime'          => TIME,
                                            'lastUpdate'        => TIME
                                        );
                                        $where=array(
                                            'sender_id'         => $sender_id
                                        );
                                        $update=$db->update($general->table(67),$data,$where);
                                    }

                                    if(trim($sender['senderPictureLink'])==''){
                                        $bot = new FbBotApp(ACCESS_TOKEN);
                                        $user = $bot->userProfile($sender_id);
                                        $data=array(
                                            'senderName'        => $user->getFirstName()." ".$user->getLastName(),
                                            'senderPictureLink' => $user->getPicture()
                                        );
                                        $where=array(
                                            'sender_id'         => $sender_id
                                        );
                                        $update=$db->update($general->table(16),$data,$where);
                                        $sender=$db->get_rowData($general->table(67),'sender_id',$sender_id);
                                        if(empty($sender)){
                                            $data=array(
                                                'sender_id'         => $sender_id,
                                                'senderName'        => $user->getFirstName()." ".$user->getLastName(),
                                                'senderPictureLink' => $user->getPicture(),
                                                'sendTime'          => TIME,
                                                'lastUpdate'        => TIME
                                            );
                                            $db->insert($general->table(67),$data);
                                        }
                                        else{
                                            $update=$db->update($general->table(67),$data,$where);
                                        }
                                        ///textFileWriteNew(__FILE__.' line '.__LINE__ .$update);
                                    }
                                    else{
                                        //textFileWriteNew(__FILE__.' line '.__LINE__ );
                                    }
                                    $data=array('replyed'=>0,'isDone'=>0,'sendTime' => TIME,'lastUpdate'=>TIME);
                                    $where=array('sender_id'=>$sender_id);
                                    $update=$db->update($general->table(16),$data,$where);
                                    $update=$db->update($general->table(67),$data,$where);
                                    //textFileWriteNew(__FILE__.' line '.__LINE__ .$update);
                                }
                                $data=array(
                                    'mid'       => $m['message']['mid'],
                                    'seq'       => $m['message']['seq'],
                                    'text'      => $general->mEnc($m['message']['text']),
                                    'sendTime'  => TIME,
                                    'sendType'  => 1,
                                    'sender_id' => $sender_id
                                );
                                $dUrl=array();
                                //textFileWriteNew(json_encode($data),'message.txt');
                                if(isset($m['message']['attachments'])){
                                    foreach($m['message']['attachments'] as $ma){
                                        //textFileWriteNew(json_encode($ma));
                                        $dUrl[]=array(
                                            'type'=>$ma['type'],
                                            'url'=>$ma['payload']['url']
                                        );
                                    }
                                    //textFileWriteNew(__FILE__.' line '.__LINE__ .' - '.json_encode($dUrl));
                                    $data['url']=json_encode($dUrl);
                                }
                                $insert=$db->insertEnc($general->table(9),$data);
                                $insert=$db->insertEnc($general->table(66),$data);
                                //textFileWriteNew(__FILE__.' line '.__LINE__ .$insert,'message.txt');
                            }
                            /*
                            ডেলিভারিতে এখন আর সিকোয়েন্স দেয়না তাই এটা আর দরকার নাই
                            elseif(isset($m['delivery'])){
                            foreach($m['delivery']['mids'] as $mid){
                            $data=array(
                            'seq'=>$m['delivery']['seq'],
                            );
                            $where=array('mid'=>$mid);
                            $update=$db->update($general->table(9),$data,$where);
                            textFileWriteNew(__FILE__.' line '.__LINE__ .json_encode($m));
                            }
                            //$where=array('mid'=>$m['delivery']['mids']);
                            //textFileWriteNew(array('ch'.__LINE__ => json_encode($m['delivery'])));
                            //textFileWriteNew(array('ch'.__LINE__ => json_encode($m['delivery']['mids'][0])));
                            }*/
                        }
                        else{
                            textFileWriteNew('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.' - '.' '.json_encode($ch),'jsonresponse.txt');
                        }
                    }
                }
                else{
                    //textFileWriteNew('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.' - '.' '.json_encode($i),'jsonresponse.txt');
                }
                //textFileWriteNew(__LINE__.' '.json_encode($data));
            }
        }
}