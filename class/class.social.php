<?php
    class social{
        public $db;
        public $general;
        function __construct(){
            $this->db=new DB();
            $this->general=new General();
        }
        function newPost($postData,$rqTime,&$jArray=array()){
            $a=debug_backtrace();
            if(function_exists('textFileWrite')){
//                textFileWrite('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__."\n".json_encode($a)."\n".json_encode($postData),'newpost.txt');
            }
            else{
//            textFileWrite('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__."\n".json_encode($a)."\n".json_encode($postData),'newpost.txt');
            }

            $v=$postData['value'];
            if(!isset($v['verb'])){$v['verb']='add';}
            if(isset($v['from'])&&!isset($v['sender_id'])){
                $v['sender_id']=$v['from']['id'];
            }
            $type=$this->getTypeIdByItem($v['item']);
            if($type==POST_TYPE_STATUS){
                if($v['sender_id']==PAGE_ID){
                    $tbl=4;$t='c';
                }
                else{
                    $tbl=12;$t='w';
                }
            }
            elseif($type==POST_TYPE_POST){$tbl=12;$t='w';}
            elseif($type==POST_TYPE_PHOTO||$type==POST_TYPE_VIDEO||$type==POST_TYPE_LINK||$type==POST_TYPE_NOTE){
                if($v['sender_id']==PAGE_ID){$tbl=4;$t='c';}
                else{$tbl=12;$t='w';}
            }
            if(isset($t)){
                if($v['verb']=='add'){
                    $data=array(
                        'post_id'               => $v['post_id'],
                        'created_time'          => $rqTime,
                        'created_time_actual'   => $rqTime,
                        'type'                  => $type,
                        'message'               => $v['message'],
                        'landingTime'           => TIME
                    );
                    if(isset($v['link'])){
                        $data['link']=$v['link'];
                    }
                    if(isset($v['permalink_url'])){
                        $data['permalink_url']=$v['permalink_url'];
                    }
                    else if(isset($v['photos'])){
                        $data['link']='1';
                    }
                    if($tbl==12){
                        
                        if($data['created_time']<=strtotime('-2 day')){// ২ দিনের বেশী আগের কমেন্ট যদি আসে সেগুলো অটো ডান হয়ে যাবে।
                            $data['isDone']=1;
                            $data['replyed']=1;
                            $data['assignTo']=44;
                            $data['replyBy']=44;
                            $data['assignTime']=strtotime('+1 second',$data['created_time']);
                            $data['replyTime']=strtotime('+2 second',$data['created_time']);
                        }
                        if($data['created_time']<strtotime('-2 hour')&&$data['created_time']>strtotime('-2 day')){
                            $data['rcvTime']=$data['created_time'];//রিসিভ টাইমে আসল ক্রিয়েট টাইম রাখা হচ্ছে
                            if($data['replyed']!=1){
                                $data['created_time']=strtotime('-1 min');//fb প্রবলেমের কারনে অনেক দেরীতে লোড হলে রিপোর্টে যেন কোন ঝামেলা না হয় তার জন্য কৃত্তিম ক্রিয়েট টাইম বানানো হয়।
                            }
                        }
                        $data['sender_id']=$v['sender_id'];//এটা শুধু ওয়াল পোস্টে লাগে
                        $tp=explode('_',$data['post_id']);
                        if($tp[0]==PAGE_ID){
                            $data['post_id_2']=$v['sender_id'].'_'.$tp[1];
                        }
                        else{
                            $data['post_id_2']=$data['post_id'];
                            $data['post_id']=PAGE_ID.'_'.$tp[1];     
                        }
                    }
                    //                    textFileWrite(array('ins'.$tbl.' '.__LINE__=>json_encode($data)));
                    $insert=$this->db->insert($this->general->table($tbl),$data);
                    if($insert==true){
                        if($tbl==12){
                            $insert=$this->db->insert($this->general->table(64),$data);
                        }
                        if($t=='c'){
                            //only admin post can multiple image
                            if(isset($v['photos'])){
                                foreach($v['photos'] as $f){
                                    $a=explode('/',$f);     $a=end($a);
                                    $a=explode('.jpg',$a);  $a=$a[0];
                                    $a=explode('_',$a);
                                    $link_post_id = @$a[1];
                                    $data=array(
                                        'post_id'       => $v['post_id'],
                                        'link'          => $f,
                                        'link_post_id'  => PAGE_ID.'_'.$link_post_id
                                    );
                                    $this->db->insert($this->general->table(30),$data);
                                }
                            }
                        }
                    }
                    else{
                        $a= debug_backtrace();
                        textFileWrite('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__."\n".json_encode($a)."\n".json_encode($postData).'->'.$this->db->lastError(),'newpost.txt');
                    }
                    //textFileWrite(array('err'.$tbl.' '.__LINE__=>$insert.' ---'.$tbl));
                }
                else if($v['verb']=='edited'||$v['verb']=='edit'){
                    $post=$this->getPostInfoById($v['post_id'],$t);
                    //textFileWrite(__LINE__);
                    if(!empty($post)){
                        //textFileWrite(__LINE__);
                        if($post['link']==1){
                            //textFileWrite(__LINE__);
                            $othoerPost=$this->db->selectAll($this->general->table(30),"where post_id='".$v['post_id']."'");
                            if(isset($v['photos'])){
                                if(!empty($othoerPost)){
                                    foreach($othoerPost as $op){
                                        //textFileWrite('post id '.$op['post_id']);
                                        if(!in_array($op['link'],$v['photos'])){
                                            //textFileWrite(__LINE__);
                                            $this->db->delete($this->general->table($tbl),array('post_id'=>$op['post_id']));
                                            $this->db->delete($this->general->table(13),array('post_id'=>$op['post_id']));
                                        }
                                        else{

                                            //textFileWrite(__LINE__);
                                        }
                                    }
                                }
                            }
                            else{
                                if(!empty($othoerPost)){
                                    //textFileWrite('remove images and comments');
                                    foreach($othoerPost as $op){
                                        $this->db->delete($this->general->table($tbl),array('post_id'=>$op['post_id']));
                                        $this->db->delete($this->general->table(13),array('post_id'=>$op['post_id']));
                                    }
                                }
                                //                                $data=array('link'=>'');
                                //                                $where=array('post_id'=>$v['post_id']);
                                //                                $update=$this->db->update($this->general->table($tbl),$data,$where); 
                            }
                        }
                        $data=array('message' => $v['message']);
                        $where=array('post_id' => $v['post_id']);
                        if(isset($v['link'])){
                            $data['link']=$v['link'];
                        }
                        $this->db->update($this->general->table($tbl),$data,$where);
                    }
                    else{
                        textFileWrite(__LINE__);
                        $v['verb']='add';
                        $this->newPost(array('value'=>$v),$rqTime);
                    }
                }

                else if($v['verb']=='remove'){
                    //textFileWrite('remove '.__LINE__.' '.$v['post_id']);
                    $this->removeComment($v['post_id'],$t,'p');
                }
                else if($v['verb']=='hide'){
                }
                else{
                    $backtrace = debug_backtrace();
                    textFileWrite(json_encode($backtrace),'needCheck.txt');
                }
            }
            else{
                $backtrace = debug_backtrace();
                textFileWrite(json_encode($backtrace),'needCheck.txt');
            }
        }
        function newCommenti($commentData){
            $v=$commentData['value'];
            //textFileWrite($commentData);
            if($v['verb']=='add'){
                $cm=$this->db->get_rowData($this->general->table(13),'comment_id',$v['comment_id']);
                if(empty($cm)){
                    //textFileWrite(array('ins'.__LINE__=>json_encode($v)));
                    $data=array(
                        'comment_id'            => $v['comment_id'],
                        'post_id'               => $v['post_id'],
                        'parent_id'             => $v['parent_id'],
                        'sender_id'             => $v['sender_id'],
                        'created_time'          => $v['created_time'],
                        'created_time_actual'   => $v['created_time'],
                        'message'               => @$v['message'],
                        'landingTime'           => TIME
                    );
                    if($data['created_time']<strtotime('-10 minute')){
                        $data['rcvTime']=$data['created_time'];//রিসিভ টাইমে আসল ক্রিয়েট টাইম রাখা হচ্ছে
                        $data['created_time']=strtotime('-1 minute');//fb প্রবলেমের কারনে অনেক দেরীতে লোড হলে রিপোর্টে যেন কোন ঝামেলা না হয় তার জন্য কৃত্তিম ক্রিয়েট টাইম বানানো হয়।
                    }
                    if($v['sender_id']==PAGE_ID){
                        $data['replyed']=1;
                        $data['replyTime']=TIME;
                    }

                    if(isset($v['photo'])){$data['photo']=$v['photo'];}

                    if($v['post_sender_id']==PAGE_ID){
                        $type='c';
                        $pTbl=4;
                        $tbl=13;
                        $nrtbl=63;

                    }
                    else{
                        $data['post_sender_id']=$v['post_sender_id'];
                        $type='w';
                        $tbl=14;
                        $pTbl=12;
                        $nrtbl=65;
                    }
                    if($data['created_time']<=strtotime('-2 day')){// ২ দিনের বেশী আগের কমেন্ট যদি আসে সেগুলো অটো ডান হয়ে যাবে।
                        $data['isDone']=1;
                        $data['replyed']=1;
                        $data['assignTo']=44;
                        $data['replyBy']=44;
                        $data['assignTime']=strtotime('+1 second',$data['created_time']);
                        $data['replyTime']=strtotime('+2 second',$data['created_time']);
                    }
                    if($data['created_time']<strtotime('-2 hour')&&$data['created_time']>strtotime('-2 day')){
                        $data['rcvTime']=$data['created_time'];//রিসিভ টাইমে আসল ক্রিয়েট টাইম রাখা হচ্ছে
                        $data['created_time']=strtotime('-1 min');//fb প্রবলেমের কারনে অনেক দেরীতে লোড হলে রিপোর্টে যেন কোন ঝামেলা না হয় তার জন্য কৃত্তিম ক্রিয়েট টাইম বানানো হয়।
                    }

                    $service24hours=intval($this->db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
                    $service24hours=$service24hours==1?1:2;
                    if($service24hours==2){
                        $startTime=explode(':',$this->db->settingsValue('serviceStartTime'));
                        $startHour=str_pad(intval(@$startTime[0]),2,0,STR_PAD_LEFT);
                        $startMin=str_pad(intval(@$startTime[1]),2,0,STR_PAD_LEFT);
                        $endTime=explode(':',$this->db->settingsValue('serviceEndTime'));
                        $endHour=intval(@$endTime[0]);$endMin=intval(@$endTime[1]);
                        $todayStartTime=strtotime(date('d-m-Y '.$startHour.':'.$startMin.':00'));
                        $todayEndTime=strtotime('+ '.$endHour.' hour',$todayStartTime);
                        $todayEndTime=strtotime('+ '.$endMin.' minute',$todayEndTime);
                        $yesterdayEndTime=strtotime('-1 day',$todayEndTime);
                        $tommorowStartTime=strtotime('+1 day',$todayStartTime);
                        $ct=$data['created_time'];
                        if($ct<$todayStartTime){
                            if($ct>$yesterdayEndTime){
                                $ct=$todayStartTime;
                            }
                        }
                        elseif($ct>$todayEndTime){
                            $ct=$tommorowStartTime;
                        }
                        $data['created_time']=$ct;
                    }
                    $insert=$this->db->insert($this->general->table($tbl),$data);
                    if($insert==true){
                        $insert=$this->db->insert($this->general->table($nrtbl),$data);
                        if($v['sender_id']==PAGE_ID){
                            if($type=='c'){
                                if(!isset($v['parent_id'])){
                                    textFileWrite(json_encode($v));
                                }
                                elseif($v['parent_id']==''){
                                    textFileWrite(json_encode($v));
                                }
                                $p=$this->getCommentInfoById($v['parent_id'],$type);
                                if(!empty($p)){
                                    if($p['replyed']==0){
                                        $data=array(
                                            'replyed'=>1,
                                            'isDone'=>1,
                                            'replyTime'=>TIME
                                        );
                                        $where=array('comment_id'=>$v['parent_id']);
                                        $this->db->update($this->general->table(13),$data,$where);
                                        $this->db->update($this->general->table($nrtbl),$data,$where);
                                        //                                        textFileWrite(__FILE__.' '.__LINE__.' '.$v['parent_id'],'replyed.txt');
                                    }
                                }
                                else{
                                    //need to download main comment
                                }
                            }
                            else{
                                //wallpost not done yet
                            }
                            $data=array('replyed'=>1,'replyTime'=>TIME);
                            $where=array('post_id'=>$v['post_id']);
                            $this->db->update($this->general->table($pTbl),$data,$where); 
                            //                            textFileWrite(__FILE__.' '.__LINE__.' '.$v['post_id'],'replyed.txt');
                            $where=array('comment_id'=>$v['parent_id']);
                            $this->db->update($this->general->table($tbl),$data,$where); 
                            $this->db->update($this->general->table($nrtbl),$data,$where); 
                            //                            textFileWrite(__FILE__.' '.__LINE__.' '.$v['parent_id'],'replyed.txt');

                        }
                    }
                }
            }
            else if($v['verb']=='edited'){
                $data=array('message'=> @$v['message'],'photo'=>'');
                if(isset($v['photo'])){
                    $data['photo']=$v['photo'];
                }

                if($v['post_sender_id']==PAGE_ID){
                    $where=array('comment_id' => $v['comment_id']);
                    $this->db->update($this->general->table(13),$data,$where);
                }
                else{
                    $where=array('comment_id' => $v['comment_id']);
                    $this->db->update($this->general->table(14),$data,$where);
                }
            }
            else if($v['verb']=='remove'){
                if($v['post_sender_id']==PAGE_ID ){
                    $this->removeComment($v['comment_id'],'c','c');
                }
                else{
                    $this->removeComment($v['comment_id'],'w','c');
                }
            }
        }
        /**
        * Remove comment info if remove by facebook
        * @param $comment_id (string) Comment ID/Post ID
        * @param $type (string) post type c / w
        * @public
        */
        function removeComment($comment_id,$type,$targetType='c',$replyBy=true){
            $this->commentDeleteAfterAction($type,$comment_id,$targetType,$replyBy);
            /*if($type=='c'){
            $data=array('replyed'=>3);
            $where=array('comment_id' => $comment_id);
            $this->db->update($this->general->table(13),$data,$where);


            }
            else{
            if($targetType=='p'){
            $data=array('replyed'=>3,'replyTime'=>TIME);
            if(defined('UID')){
            $data['replyBy']=UID;
            }
            $where=array('post_id' => $comment_id);
            $this->db->update($this->general->table(12),$data,$where);
            $this->commentDeleteAfterAction($type,$comment_id,$targetType);
            }
            else{
            $data=array('replyed'=>3,'replyTime'=>TIME);
            if(defined('UID')){
            $data['replyBy']=UID;
            }
            $where=array('comment_id' => $comment_id);
            $this->db->update($this->general->table(14),$data,$where);
            $this->commentDeleteAfterAction($type,$comment_id,$targetType);
            }
            }*/
        }
        /**
        * Remove comment info if remove by agent
        * @param $c (array) Comment or Post row data
        * @param $type (string) post type c / w
        * @public
        */
        function deleteCommentByAgent($c,$type,$targetType='c',&$jArray=array()){
            $backtrace = debug_backtrace();
            $a=__FILE__."/".__LINE__." \n".json_encode($backtrace);
            textFileWrite($a,'jsonresponse.txt');
            if($targetType=='c'){$comment_id=$c['comment_id'];}else{$comment_id=$c['post_id'];}
            $return=false;
            $fb=$this->fbInit();
            try {
                //$jArray[__LINE__][]=$comment_id;
                //$jArray[__LINE__][]='/'.$comment_id.'/';
                $d=$fb->delete('/'.$comment_id.'/',array('access_token' => ACCESS_TOKEN));
                if(isset($d->getDecodedBody()['success'])){
                    //$jArray[__LINE__]=$d;
                    if($type=='c'){
                        $data=array(
                            'comment_id'    => $comment_id,
                            'message'       => $c['message'],
                            'created_time'  => $c['created_time'],
                            'remove_time'   => TIME,
                            'uID'           => UID
                        );
                        $insert=$this->db->insert($this->general->table(15),$data);
                        if($insert==true){
                            $return=true;
                        }
                    }
                    else{
                        if($targetType=='p'){
                            $data=array(
                                'post_id'       => $comment_id,
                                'message'       => $c['message'],
                                'created_time'  => $c['created_time'],
                                'remove_time'   => TIME,
                                'uID'           => UID
                            );
                            $insert=$this->db->insert($this->general->table(25),$data);
                            if($insert==true){
                                $return=true;
                            }
                        }
                        else{
                            $data=array(
                                'comment_id'    => $comment_id,
                                'message'       => $c['message'],
                                'created_time'  => $c['created_time'],
                                'remove_time'   => TIME,
                                'uID'           => UID
                            );
                            $insert=$this->db->insert($this->general->table(39),$data);
                            if($insert==true){
                                $return=true;
                            }
                        }
                    }
                }
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                SetMessage(4,'Graph returned an error: ' . $e->getCode());
                if($e->getCode()==100){
                    $return=true;
                }
            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
                SetMessage('Facebook SDK returned an error: ' . $e->getMessage());
            }
            if($return==true){
                if($type=='c'){
                    $data=array('replyed'=>3,'replyTime'=>TIME,'replyBy'=>UID);
                    $where=array('comment_id' => $comment_id);
                    $this->db->update($this->general->table(13),$data,$where);
                    $this->commentDeleteAfterAction($type,$comment_id,$targetType);

                }
                else{
                    if($targetType=='p'){
                        $data=array('replyed'=>3,'replyTime'=>TIME,'replyBy'=>UID);
                        $where=array('post_id' => $comment_id);
                        $this->db->update($this->general->table(12),$data,$where);
                    }
                    else{
                        $data=array('replyed'=>3,'replyTime'=>TIME,'replyBy'=>UID);
                        $where=array('comment_id' => $comment_id);
                        $this->db->update($this->general->table(14),$data,$where);
                        $this->commentDeleteAfterAction($type,$comment_id,$targetType);
                    }
                }

            }
            return $return;
        }
        function commentDeleteAfterAction($type,$comment_id,$targetType,$replyBy=true){
            //            $a=debug_backtrace();
            //            textFileWrite(json_encode($a),'jsonresponse.txt');
            //if(!defined('UID')){define('UID',0);}
            $set="set replyed=3,replyTime=IF(replyTime>0,replyTime,".TIME.")";//যেন প্রথম এ্যাকশনের সময় থাকে তাই রিপ্লাই টাইম এভাবে রাখা হল
            if(defined('UID')&&$replyBy!==false){if(UID>0){$set.=",replyBy=".UID;}}
            if($type=='c'){
                $this->db->runQuery("update ".$this->general->table(13)." ".$set." where comment_id='".$comment_id."'");
                $this->db->runQuery("update ".$this->general->table(63)." ".$set." where comment_id='".$comment_id."'");
                $childs=$this->db->selectAll($this->general->table(13),"where parent_id='".$comment_id."'",'comment_id,replyed');
                if(!empty($childs)){
                    $done=array();
                    $del=array();
                    foreach($childs as $c){
                        if($c['replyed']!=0){$done[]=$c['comment_id'];}
                        else{$del[]=$c['comment_id'];}
                    }
                    if(!empty($done)){
                        $this->db->runQuery("update ".$this->general->table(13)." set replyed=3 where comment_id in('".implode("','",$done)."')");
                        $this->db->runQuery("update ".$this->general->table(63)." set replyed=3 where comment_id in('".implode("','",$done)."')");
                    }
                    if(!empty($del)){
                        $this->db->runQuery("delete from ".$this->general->table(13)." where comment_id in('".implode("','",$del)."')");
                        $this->db->runQuery("delete from ".$this->general->table(63)." where comment_id in('".implode("','",$del)."')");
                    }
                }

            }
            else{
                if($targetType=='p'){
                    $this->db->runQuery("update ".$this->general->table(12)." ".$set." where post_id='".$comment_id."'");
                    $this->db->runQuery("update ".$this->general->table(64)." ".$set." where post_id='".$comment_id."'");


                    /*$data=array('replyed'=>3,'replyTime'=>TIME);
                    if(defined('UID')){
                    $data['replyBy']=UID;
                    }
                    $where=array('post_id' => $comment_id);
                    $this->db->update($this->general->table(12),$data,$where);*/
                    $childs=$this->db->selectAll($this->general->table(14),"where post_id='".$comment_id."'",'post_id,replyed');
                    //textFileWrite(__LINE__.' - '.$comment_id.' - '.json_encode($childs));
                    if(!empty($childs)){
                        $done=array();
                        $del=array();
                        foreach($childs as $c){
                            if($c['replyed']!=0){$done[]=$c['post_id'];}
                            else{$del[]=$c['post_id'];}
                        }
                        if(!empty($done)){
                            $this->db->runQuery("update ".$this->general->table(14)." set replyed=3 where post_id in('".implode("','",$done)."')");
                            $this->db->runQuery("update ".$this->general->table(65)." set replyed=3 where post_id in('".implode("','",$done)."')");
                        }
                        if(!empty($del)){
                            $this->db->runQuery("delete from ".$this->general->table(14)." where post_id in('".implode("','",$del)."')");
                            $this->db->runQuery("delete from ".$this->general->table(65)." where post_id in('".implode("','",$del)."')");
                        }
                    }

                }
                else{
                    $this->db->runQuery("update ".$this->general->table(14)." ".$set." where comment_id='".$comment_id."'");
                    $this->db->runQuery("update ".$this->general->table(65)." ".$set." where comment_id='".$comment_id."'");

                    /*$data=array('replyed'=>3,'replyTime'=>TIME);
                    if(defined('UID')){
                    $data['replyBy']=UID;
                    }
                    $where=array('comment_id' => $comment_id);
                    $this->db->update($this->general->table(14),$data,$where);*/
                    $childs=$this->db->selectAll($this->general->table(14),"where parent_id='".$comment_id."'",'comment_id,replyed');
                    //textFileWrite(__LINE__.' - '.$comment_id.' - '.json_encode($childs));
                    if(!empty($childs)){
                        $done=array();
                        $del=array();
                        foreach($childs as $c){
                            if($c['replyed']!=0){$done[]=$c['comment_id'];}
                            else{$del[]=$c['comment_id'];}
                        }
                        if(!empty($done)){
                            $a=$this->db->runQuery("update ".$this->general->table(14)." set replyed=3 where comment_id in('".implode("','",$done)."')");
                            $a=$this->db->runQuery("update ".$this->general->table(65)." set replyed=3 where comment_id in('".implode("','",$done)."')");
                            //textFileWrite(__LINE__.' - '.$comment_id.' - '.$a);
                        }
                        if(!empty($del)){
                            $a=$this->db->runQuery("delete from ".$this->general->table(14)." where comment_id in('".implode("','",$del)."')");
                            $a=$this->db->runQuery("delete from ".$this->general->table(65)." where comment_id in('".implode("','",$del)."')");
                            //textFileWrite(__LINE__.' - '.$comment_id.' - '.$a);
                        }
                    }

                }
            }
        }
        function messageTemplateMake($txt){
            $u=$this->db->get_rowData($this->general->table(17),'uID',UID);
            $msgDevine=array(
                '{{user}}'=>$u['uFullName']
            );
            foreach($msgDevine as $k=>$v){
                $txt=str_ireplace($k,$v,$txt);
            }
            //                $txt = str_replace("'", "&#39;",$txt);
            return $this->general->content_show($txt);
        }
        function getAssignType($for=''){
            $assignType=ASSIGN_TYPE_AUTO;
            if($for=='c'){
                $assignType=$this->db->settingsValue('pagePostAssign');
            }
            else if($for=='w'){
                $assignType=$this->db->settingsValue('wallPostAssign');
            }
            else if($for=='m'){
                $assignType=$this->db->settingsValue('messageAssign');
            }
            if($assignType!=ASSIGN_TYPE_AUTO){
                if($assignType!=ASSIGN_TYPE_MANUAL){
                    $assignType=ASSIGN_TYPE_HIGHBREED;
                }
            }
            return $assignType;
        }
        function getFlowType($for=''){
            $assignType=WALL_POST_COMMENT_FLOW_FIFO;
            $u=$this->db->get_rowData($this->general->table(17),'uID',UID);
            if($for=='c'){
                $assignType=$u['uCommentFlow'];
            }
            else if($for=='w'){
                $assignType=$u['uWallpostFlow'];
            }
            elseif($for=='m'){
                $assignType=$this->db->settingsValue('messageFlowType');
            }
            if($assignType!=WALL_POST_COMMENT_FLOW_LIFO){$assignType=WALL_POST_COMMENT_FLOW_FIFO;}
            return $assignType;
        }
        function getPostInfoById($post_id,$type){
            if($type=='c'){
                return $this->db->get_rowData($this->general->table(4),'post_id',$post_id);
            }
            elseif($type=='w'){
                $rt=$this->db->get_rowData($this->general->table(12),'post_id',$post_id);
                if(!empty($rt)){return $rt;}
                else{
                    return $this->db->get_rowData($this->general->table(12),'post_id_2',$post_id);
                }
            }
            else{
                return false;
            }
        }
        function getCommentInfoById($comment_id,$type){
            if($type=='c'){
                return $this->db->get_rowData($this->general->table(13),'comment_id',$comment_id);
            }
            elseif($type=='w'){
                return $this->db->get_rowData($this->general->table(14),'comment_id',$comment_id);
            }
            else{
                return false;
            }
        }
        function getNamesByUserId($ids){
            $names=array();
            $i=0;//it's can't reset beacoust it use for name array index encrement
            if(!empty($ids)){
                $needs=array();
                $old=$this->db->selectAll($this->general->table(33),"where id in ('".implode("','",$ids)."')");
                if(!empty($old)){
                    $this->general->arrayIndexChange($old,'id');
                    foreach($ids as $id){
                        if(array_key_exists($id,$old)){
                            if($old[$id]['name']!=''){
                                $names[$i]['id']=$old[$id]['id'];
                                $names[$i]['name']=$old[$id]['name'];
                                $i++;
                            }else{$needs[]=$id;}
                        }else{$needs[]=$id;}
                    }
                }
                else{
                    $needs=$ids;
                }
                if(!empty($needs)){
                    foreach($needs as $key=>$val){if($val==''){unset($needs[$key]);}}
                    if(count($needs)<50){
                        $fbN=$this->getNameFromFacebook($needs);
                        foreach($fbN as $d){
                            $names[$i]['id']=$d['id'];
                            $names[$i]['name']=$d['name'];
                            $i++;
                        }
                    }
                    else{
                        $a=0;
                        $b=array();
                        foreach($needs as $d){
                            $b[]=$d;
                            if(count($b)>48){
                                $fbN=$this->getNameFromFacebook($b);
                                foreach($fbN as $d){
                                    $names[$i]['id']=$d['id'];
                                    $names[$i]['name']=$d['name'];
                                    $i++;
                                }
                                $b=array();
                            }
                        }
                    }
                }
            }
            return $names;
        }
        function getMsgSenderNamesByUserId($ids){
            $names=array();
            $i=0;//it's can't reset beacoust it use for name array index encrement
            if(!empty($ids)){
                $needs=array();
                $old=$this->db->selectAll($this->general->table(16),"where sender_id in ('".implode("','",$ids)."')");
                if(!empty($old)){
                    $this->general->arrayIndexChange($old,'sender_id');
                    foreach($ids as $id){
                        if(array_key_exists($id,$old)){
                            $names[$id]=$old[$id]['senderName'];
                        }else{
                            $names[$id]['name']='';
                        }
                        $i++;
                    }
                }
                else{
                    $needs=$ids;
                }
            }
            return $names;
        }
        function getNameFromFacebook($needs){
            $names=array();
            $fb=$this->fbInit();
            $i=0;
            try {
                $response = $fb->get('/?fields=name&ids='.implode(',',$needs));
                foreach($response->getDecodedBody() as $d){
                    $names[$i]['id']=$d['id'];
                    $names[$i]['name']=$d['name'];
                    $i++;
                    $data=array('id'=>$d['id'],'name'=>$d['name']);
                    $this->db->insert($this->general->table(33),$data);
                }
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                $backtrace = debug_backtrace();
                $er=__FILE__.'  Line '.__LINE__.' ' .date('d-m-y h:i:s A').'. Graph returned an error: ' . $e->getCode().'  '.$e->getMessage().' bac '.json_encode($backtrace);
                textFileWrite($er.' data '.serialize($ids),'fb_error.txt');

                SetMessage(4,'Graph returned an error: ' . $e->getCode().' description '.$e->getMessage());

            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                $er=__FILE__.'  Line '.__LINE__.'. Facebook SDK returned an error: ' . $e->getCode().'  '.$e->getMessage();
                textFileWrite($er.' data '.serialize($ids),'fb_error.txt');
                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage());
            }
            return $names;
        }
        function wholeThreatData($post_id,$cp=1){

            $jArray=array('status'=>1);
            $query="where  parent_id='".$post_id."' order by created_time asc";
            $mp=$this->general->pagination_init($this->general->table(13),30,$cp,$query);
            //            $jArray[__LINE__]=$mp;
            $wholeThread=$this->db->selectAll($this->general->table(13),$query.$mp['limit']);
            if(!empty($wholeThread)){
                foreach($wholeThread as $wt){
                    $names[$wt['sender_id']]=$wt['sender_id'];
                    $whole_thread=array(
                        'c'             => 0,
                        'comment_id'    => $wt['comment_id'],
                        'post_id'    => $wt['post_id'],
                        'parent_id'     => $wt['parent_id'],
                        'message'       => $wt['message'],
                        'sender_id'     => $wt['sender_id'],
                        'photo'         => $wt['photo'],
                        'created_time'  => date('YmdHis',$wt['created_time'])
                    );
                    $jArray['whole_thread'][]=$whole_thread; 
                    $wholeThreadChilds=$this->db->selectAll($this->general->table(13),"where  parent_id='".$wt['comment_id']."' order by created_time desc");
                    foreach($wholeThreadChilds as $wtc){
                        $names[$wtc['sender_id']]=$wtc['sender_id'];
                        $whole_thread=array(
                            'c'             => 1,
                            'comment_id'    => $wtc['comment_id'],
                            'post_id'    => $wtc['post_id'],
                            'parent_id'     => $wtc['parent_id'],
                            'message'       => $wtc['message'],
                            'sender_id'     => $wtc['sender_id'],
                            'photo'         => $wtc['photo'],
                            'created_time'  => date('YmdHis',$wtc['created_time'])
                        );
                        $jArray['whole_thread'][]=$whole_thread; 
                    }
                }
            }
            if($mp['currentPage']<$mp['TotalPage']){
                $jArray['nextPage']=$mp['currentPage']+1;
            }
            if(!empty($names)){
                $jArray['names']=$this->getNamesByUserId($names);
            }
            $jArray['post_ids']=$this->getPostPermalinkUrlBiIds(array($post_id=>$post_id),'c');
            return $jArray;




            /*$fb=$this->fbInit();
            if(!empty($after)){
            $paging="?after=".$after;
            }
            else{
            $paging  = '';    
            }
            try {
            $response = $fb->get('/'.$post_id.'/comments'.$paging);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            }

            $after =  $response->getDecodedBody()['paging']['cursors']['after'];
            // $cids=array();
            $data=array();
            foreach($response->getDecodedBody()['data'] as $item) {

            $id = $item['id'];
            $msg = $item['message'];
            $created_time = $item['created_time'];
            $full_picture = isset($item['full_picture'])?$item['full_picture']:'';
            $userid = $item['from']['id'];
            $fTime1=explode('T',$created_time);
            $fTime2=explode('+',$fTime1[1]);
            $date=explode('-',$fTime1[0]);
            $fTime3=strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$fTime2[0]);
            $fTime=strtotime('+6 hour',$fTime3);
            $data[]=array(
            'comment_id'    => $id,
            'message'       => $msg,
            'created_time'  => $created_time,
            'photo'         => $full_picture,
            'sender_id'     => $userid,
            'parent_id'     => $post_id
            );
            try {
            $response = $fb->get('/'.$id.'/comments');

            } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            }
            $names[$userid]=$userid;
            $parent = $id;
            // var_dump($response->getDecodedBody());
            // $response->getDecodedBody()['paging'])

            foreach($response->getDecodedBody()['data'] as $item) {
            $id = $item['id'];
            $msg = $item['message'];
            $created_time = $item['created_time'];
            $full_picture = isset($item['full_picture'])?$item['full_picture']:'';
            $userid = $item['from']['id']; 
            $fTime1=explode('T',$created_time);
            $fTime2=explode('+',$fTime1[1]);
            $date=explode('-',$fTime1[0]);
            $fTime3=strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$fTime2[0]);
            $fTime=strtotime('+6 hour',$fTime3);
            $data[]=array(
            'comment_id'    => $id,
            'message'       => $msg,
            'created_time'  => $created_time,
            'photo'         => $full_picture,
            'sender_id'     => $userid,
            'parent_id'     => $parent
            );
            $names[$userid]=$userid;
            }
            }
            $jArray['whole_thread']=$data;
            $jArray['after']=$after;*/
            $jArray['names']=$this->getNamesByUserId($names);
            return $jArray;
        }
        function messageSendersName($senderIds){
            $senderNames=array();
            if(!empty($senderIds)){
                $senders=$this->db->fetchQuery("select sender_id,senderName,senderPictureLink from ".$this->general->table(16)." where sender_id in(".implode(',',$senderIds).")");
                //$this->general->printArray($senders);
                if(!empty($senders)){
                    $this->general->arrayIndexChange($senders,'sender_id');
                    $senderNames=$senders;
                }
            }
            return $senderNames;
        }
        function nextPicup($type){
            $times=array();
            $jArray=array('status'=>0);
            //$jArray[__LINE__]=__LINE__;
            $names= array();
            $post_ids= array();
            if($type=='w'){
                $times[__LINE__]=microtime().' '.date('h:i:s');
                $social2=new social2();
                $social2->nextPicupWallPostOrComment($jArray);
            }
            else if($type=='c'){//Page post comments
                $times[__LINE__]=microtime().' '.date('h:i:s');
                $flowType=$this->getFlowType($type);//lifo / fifo
                if($flowType==WALL_POST_COMMENT_FLOW_FIFO){$orderBy=" order by created_time asc";}
                else{$orderBy=" order by created_time desc";}
                $asType=$this->getAssignType($type);//auto / manual
                $cTbl=13;
                $rcTbl=63;
                $pTbl=4;
                $lastCleare=intval($this->db->settingsValue('queueCleareComment'));//Need Update
                if($lastCleare<strtotime('-30 minute')){
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    //$jArray[__LINE__]=__LINE__;
                    $old=$this->db->selectAll($this->general->table($rcTbl),"where replyed=0 and assignTo!=0 and assignTime<".strtotime('-30 minute'),'assignTo','array',$jArray);
                    if(!empty($old)){
                        $query="update ".$this->general->table($rcTbl)." set assignTo=0,assignTime=0 where assignTime<".strtotime('-30 minute')." and replyed=0 and assignTo!=0";
                        $this->db->runQuery($query,'array',$jArray);
                    }
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    $this->db->settingsUpdate(TIME,'queueCleareComment');
                }//if anybody no response last 30 minute then free that
                //$jArray[__LINE__]=__LINE__.' - '.microtime();

                $query="delete from ".$this->general->table(44).' where assignTime<='.strtotime('-10 minute');
                $this->db->runQuery($query);//this query for cleare multiple reply
                $query="delete from ".$this->general->table(10).' where assign_time<='.strtotime('-30 minute');
                $this->db->runQuery($query);//this query for cleare multiple reply
                $query="delete from ".$this->general->table(63).' where replyed>0';
                //textFileWrite($query);
                $this->db->runQuery($query);//copy table cleare for system faster

                $service24hours=intval($this->db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
                $service24hours=$service24hours==1?1:2;

                $times[__LINE__]=microtime().' '.date('h:i:s');
                $timeQuery='';
                if($service24hours==2){
                    $timeQuery=' and created_time<='.TIME;
                }
                $jArray[1][__LINE__]=__LINE__.' - '.microtime();
                if($asType==ASSIGN_TYPE_AUTO){
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    //                    $jArray[__LINE__]=__LINE__;
                    $next=$this->db->getRowData($this->general->table($rcTbl),"where replyed=0 and assignTo=".UID.$timeQuery.$orderBy);
                    if(empty($next)){
                        $next=$this->db->getRowData($this->general->table($rcTbl),"where replyed=0 and assignTo=0".$timeQuery.$orderBy);
                    }
                }
                else{
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    $got=0;
                    $nextQuery="where replyed=0";
                    $next=$this->db->getRowData($this->general->table($rcTbl),$nextQuery." and assignTo=".UID.$timeQuery.$orderBy);
                    if(!empty($next)){
                        $c=$this->db->getRowData($this->general->table(10),"where uID=".UID." and comment_id='".$next['comment_id']."'".$timeQuery.$orderBy);
                        if(!empty($c)){$got=1;}
                        else{
                            $times[__LINE__]=microtime().' '.date('h:i:s');
                            $data=array('assignTo'=>0,'assignTime'=>0);
                            $where=array('comment_id'=>$next['comment_id']);
                            $this->db->update($this->general->table($cTbl),$data,$where);
                            $this->db->update($this->general->table($rcTbl),$data,$where);
                        }
                    }
                    if($got==0){
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        $c=$this->db->getRowData($this->general->table(10),'where uID='.UID.$orderBy);
                        if(!empty($c)){
                            $next=$this->db->getRowData($this->general->table($rcTbl),$nextQuery." and comment_id='".$c['comment_id']."' and assignTo=0 ".$timeQuery);
                            while(empty($next)){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $this->db->delete($this->general->table(10),array('comment_id'=>$c['comment_id']));
                                $c=$this->db->getRowData($this->general->table(10),'where uID='.UID.$orderBy);
                                if(!empty($c)){
                                    $next=$this->db->getRowData($this->general->table($rcTbl),$nextQuery." and comment_id='".$c['comment_id']."' and assignTo=0 ".$timeQuery);
                                }else{break;}
                            }
                        }
                    }
                }

                if(!isset($next)){$next=array();}
                if(empty($next)){
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    if($asType==ASSIGN_TYPE_HIGHBREED){
                        $query="select * from ".$this->general->table($rcTbl)." where comment_id not in(select comment_id from ".$this->general->table(10).") and replyed=0 and assignTo=0 ".$timeQuery.$orderBy.' limit 1';
                        $times[__LINE__]=$query;
                        $n=$this->db->fetchQuery($query);
                        if(!empty($n)){$next=$n[0];}
                    }
                }
                $makeAssign=0;
                if(isset($next)){
                    $times[__LINE__]=microtime().' '.date('h:i:s');
                    if(!empty($next)){
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        $os=$this->db->get_rowData($this->general->table(44),'comment_id',$next['comment_id']);
                        if(empty($os)){
                            $times[__LINE__]=microtime().' '.date('h:i:s');
                            $query="delete from ".$this->general->table(44).' where uID='.UID;
                            $this->db->runQuery($query);//this query for cleare multiple reply

                            $data=array(
                                'uID'           => UID,
                                'comment_id'    => $next['comment_id'],
                                'assignTime'    => TIME
                            );

                            $insert=$this->db->insert($this->general->table(44),$data);
                            if($insert){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $makeAssign=1;
                            }
                            else{
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                            }
                        }
                        elseif($os['uID']==UID){
                            $makeAssign=1;
                        }
                    }
                    if($makeAssign==1){
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        $data=array('assignTime'=>time(),'assignTo'=>UID);
                        $where=array('comment_id'=>$next['comment_id']);
                        $this->db->update($this->general->table($cTbl),$data,$where);
                        $this->db->update($this->general->table($rcTbl),$data,$where);
                    }
                    else{
                        $next=array();
                    }
                }
                if(isset($next)){
                    if(!empty($next)){
                        $times[__LINE__]=microtime().' '.date('h:i:s');
                        // It's for comment reload from facebook
                        $assignd=$next['comment_id'];
                        $post_id=$next['post_id'];
                        if($next['message']==''||$next['photo']!=''){
                            $fb=$this->fbInit();
                            try {
                                $rqUrl = '/'.$next['comment_id'].'/?fields=id,attachment,message';
                                $response = $fb->get($rqUrl);
                                if(isset($response->getDecodedBody()['id'])){
                                    $times[__LINE__]=microtime().' '.date('h:i:s');
                                    $data=array();
                                    $d=$response->getDecodedBody();
                                    if(isset($d['attachment'])){
                                        $photo=$d['attachment']['media']['image']['src'];
                                        if($photo!=$next['photo']){
                                            $data['photo']=$photo;
                                            $next['photo']=$photo;
                                        }
                                    }   
                                    if(isset($d['message'])){
                                        if($next['message']!=$d['message']){
                                            $data['message']=$d['message'];
                                            $next['message']=$d['message'];
                                        }   
                                    }
                                    if(!empty($data)){
                                        $where=array('comment_id'=>$next['comment_id']);
                                        $this->db->update($this->general->table($cTbl),$data,$where);
                                        $this->db->update($this->general->table($rcTbl),$data,$where);
                                    }
                                }
                                else{
                                    $next=array();
                                }
                            } 
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                $jArray[__LINE__]='Graph returned an error: a<br>'.$e->getCode().'<br>a ' . $e->getMessage();
                                if($e->getCode()==100){
                                    $data=array('replyed'=>5);//deleted but we don't know
                                    $where=array('comment_id'=>$next['comment_id']);
                                    $this->db->update($this->general->table($cTbl),$data,$where);
                                    $this->db->update($this->general->table($rcTbl),$data,$where);
                                }
                                $error=__LINE__; 
                                //echo 'Graph returned an error: a<br>'.$e->getCode().'<br>a ' . $e->getMessage();
                            }
                            catch(Facebook\Exceptions\FacebookSDKException $e) {
                                $error=__LINE__; 
                                //echo  'Facebook SDK returned an error: ' . $e->getMessage();
                            }

                        }
                    }
                }
                if(isset($next)){
                    if(!empty($next)){
                        $times[__LINE__]=microtime().' '.date('h:i:s');

                        $post=$this->db->get_rowData($this->general->table($pTbl),'post_id',$post_id);
                        if(!empty($post)){
                            $jArray['post']=array(
                                'post_id'   => $post['post_id'],
                                'message'   => $post['message'],
                                'time'      => date('YmdHis',$post['created_time_actual']),
                                'type'      => $post['type'],
                                'item'      => $post['type'],
                                'link'      => $post['link']
                            );
                            if(isset($post['sender_id'])){
                                $jArray['post']['sender_id']=$post['sender_id'];
                            }
                        }
                        else{
                            $fb = $this->fbInit();
                            try {
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                //echo $post_id;
                                $rqUrl = '/'.$post_id.'/?fields=id,message,created_time,from,type,full_picture';
                                $response = $fb->get($rqUrl);
                                if(isset($response->getDecodedBody()['id'])){
                                    //$this->general->printArray($response->getDecodedBody());
                                    $d=$response->getDecodedBody();
                                    $post_ids[$d['id']]=$d['id'];
                                    $jArray['post']=array(
                                        'post_id'       => $d['id'],
                                        'created_time'  => strtotime($d['created_time_actual']),
                                        'message'       => $d['message'],
                                        'type'          => $this->getTypeIdByItem($d['type']),
                                        'item'          => $d['type'],
                                        'sender_id'     => $d['from']['id']
                                    );

                                    if(isset($d['full_picture'])){
                                        $jArray['post']['link']=$d['full_picture'];
                                    }
                                    //textFileWrite('['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " .'new post '.$d['id']);
                                    $this->newPost(array('value'=>$jArray['post']),strtotime($d['created_time']));
                                    $times[__LINE__]=microtime().' '.date('h:i:s');
                                }
                                else{
                                    $jArray['e']='Invalid post';
                                }
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post id".$post_id;
                                textFileWrite($er,'fb_error.txt');
                                if(SHOW_ERROR_LINE=='Yes'){
                                    SetMessage('Graph returned an error: ' . $e->getMessage().' Line '.__LINE__);   
                                }
                                //$error=__LINE__;
                                $jArray['post']=array(
                                    'post_id'       => $post_id,
                                    'created_time'  => '',
                                    'message'       => 'Post may be delete',
                                    'type'          => '',
                                    'item'          => '',
                                    'sender_id'     => ''
                                );
                            } 
                            catch(Facebook\Exceptions\FacebookSDKException $e) {
                                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post id".$post_id;
                                textFileWrite($er,'fb_error.txt');
                                if(SHOW_ERROR_LINE=='Yes'){
                                    SetMessage('Facebook SDK returned an error: ' . $e->getMessage().' Line '.__LINE__);
                                }
                            }
                        }

                        if(!isset($error)){
                            $times[__LINE__]=microtime().' '.date('h:i:s');
                            $allCommentId=array();
                            $jArray['status']=1;
                            $names[$next['sender_id']]=$next['sender_id'];
                            $post_ids[$next['post_id']]=$next['post_id'];
                            $jArray['target']=$next['comment_id'];
                            $jArray['target_sender_id']=$next['sender_id'];
                            //$allCommentId[]=$next['comment_id'];
                            $mainComment=array(
                                'comment_id'    => $next['comment_id'],
                                'post_id'       => $next['post_id'],
                                'parent_id'     => $next['parent_id'],
                                'message'       => $next['message'],
                                'sender_id'     => $next['sender_id'],
                                'photo'         => $next['photo'],
                                'created_time'  => date('YmdHis',$next['created_time_actual'])
                            );

                            if($next['parent_id']!=$next['post_id']){
                                $pc=$this->db->get_rowData($this->general->table($cTbl),'comment_id',$next['parent_id']);
                                if(!empty($pc)){
                                    $comment=array(
                                        'comment_id'    => $pc['comment_id'],
                                        'post_id'       => $next['post_id'],
                                        'parent_id'     => $pc['parent_id'],
                                        'message'       => $pc['message'],
                                        'sender_id'     => $pc['sender_id'],
                                        'photo'         => $pc['photo'],
                                        'created_time'  => date('YmdHis',$pc['created_time_actual'])
                                    );
                                    $allCommentId[]=$pc['comment_id'];
                                    $jArray['comments'][]=$comment;
                                    //$jArray['target_sender_id']=$next['sender_id'];
                                    $names[$next['sender_id']]=$next['sender_id'];
                                    $post_ids[$next['post_id']]=$next['post_id'];
                                }
                                else{
                                    $fb = $this->fbInit();
                                    $times[__LINE__]=microtime().' '.date('h:i:s');
                                    try {
                                        $times[__LINE__]=microtime().' '.date('h:i:s');
                                        $rqUrl = '/'.$next['parent_id'].'/?fields=id,message,created_time,from,attachment';
                                        $response = $fb->get($rqUrl);
                                        if(isset($response->getDecodedBody()['id'])){
                                            $times[__LINE__]=microtime().' '.date('h:i:s');
                                            $d=$response->getDecodedBody();
                                            $data=array(
                                                'comment_id'    => $d['id'],
                                                'post_id'       => $next['post_id'],
                                                'message'       => $d['message'],
                                                'created_time'  => strtotime($d['created_time']),
                                                'sender_id'     => $d['from']['id'],
                                                'parent_id'     => $next['post_id'],
                                                'replyed'       => 1,
                                                'isDone'        => 1,
                                                'assignTime'    => strtotime($d['created_time'])+2,
                                                'replyTime'     => strtotime($d['created_time'])+5,
                                                'replyBy'       => 44,
                                                'assignTo'      => 44
                                            );
                                            if(isset($d['attachment']['media']['image'])){
                                                $data['link']=$d['attachment']['media']['image']['src'];
                                            }
                                            $insert=$this->db->insert($this->general->table($cTbl),$data);
                                            if($insert==true){
                                                $allCommentId[]=$data['comment_id'];
                                                $jArray['comments'][]=$data;
                                            }
                                            $jArray['target_sender_id']=$data['sender_id'];
                                            $names[$next['sender_id']]=$next['sender_id'];
                                            $post_ids[$next['post_id']]=$next['post_id'];
                                        }
                                        else{
                                            $jArray['e']='Invalid post';
                                        }
                                    } catch(Facebook\Exceptions\FacebookResponseException $e) {
                                        $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n".json_encode($next);
                                        textFileWrite($er,'fb_error.txt');
                                        if(SHOW_ERROR_LINE=='Yes'){
                                            SetMessage('Graph returned an error: ' . $e->getMessage().' Line '.__LINE__);   
                                        }
                                        $error=__LINE__;
                                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                        $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n".json_encode($next);
                                        textFileWrite($er,'fb_error.txt');
                                        if(SHOW_ERROR_LINE=='Yes'){
                                            SetMessage('Facebook SDK returned an error: ' . $e->getMessage().' Line '.__LINE__);
                                        }
                                    }
                                }
                            }
                            //$jArray[__LINE__]=__LINE__.' - '.microtime();
                            $mainCommentSenderID = $next['sender_id'];
                            $mainCommentCreated = $next['created_time'];
                            if($next['parent_id']!=$next['post_id']){
                                $others=$this->db->selectAll($this->general->table($cTbl),"where parent_id='".$next['parent_id']."' order by created_time asc");
                            }
                            else{
                                $others=$this->db->selectAll($this->general->table($cTbl),"where parent_id='".$next['comment_id']."' order by created_time asc");
                            }
                            $times[__LINE__]=microtime().' '.date('h:i:s');
                            if(!empty($others)){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                foreach($others as $next){
                                    $names[$next['sender_id']]=$next['sender_id'];
                                    $post_ids[$next['post_id']]=$next['post_id'];
                                    $comment=array(
                                        'comment_id'    => $next['comment_id'],
                                        'post_id'       => $next['post_id'],
                                        'parent_id'     => $next['parent_id'],
                                        'message'       => $next['message'],
                                        'sender_id'     => $next['sender_id'],
                                        'photo'         => $next['photo'],
                                        'created_time'  => date('YmdHis',$next['created_time']),
                                        //                                        'line'          => __LINE__
                                    );
                                    $allCommentId[]=$next['comment_id'];
                                    $jArray['comments'][]=$comment; 
                                }
                            }
                            else{
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $names[$next['sender_id']]=$next['sender_id'];
                                $post_ids[$next['post_id']]=$next['post_id'];
                                $comment=array(
                                    'comment_id'    => $next['comment_id'],
                                    'post_id'       => $next['post_id'],
                                    'parent_id'     => $next['parent_id'],
                                    'message'       => $next['message'],
                                    'sender_id'     => $next['sender_id'],
                                    'photo'         => $next['photo'],
                                    'created_time'  => date('YmdHis',$next['created_time']),
                                    //'line'          => __LINE__
                                );
                                $allCommentId[]=$next['comment_id'];
                                $jArray['comments'][]=$comment; 
                            }
                            if(!in_array($jArray['target'],$allCommentId)){
                                //$jArray[__LINE__]=__LINE__;
                                //it's for if main comment not load
                                array_unshift($jArray['comments'],$mainComment);
                            }
                            if(0){//এটা অফ করলাম বেশি এক্সিকিউশনের জন্য
                                $lifetime=$this->db->selectAll($this->general->table($cTbl),"where created_time<".$mainCommentCreated." and sender_id='".$mainCommentSenderID."' order by created_time desc limit 5");
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                if(!empty($lifetime)){
                                    foreach($lifetime as $lt){
                                        //$names[$next['sender_id']]=$lt['sender_id'];
                                        $post_ids[$lt['post_id']]=$lt['post_id'];
                                        $life_time=array(
                                            'comment_id'    => $lt['comment_id'],
                                            'post_id'       => $next['post_id'],
                                            'parent_id'     => $lt['parent_id'],
                                            'message'       => $lt['message'],
                                            'sender_id'     => $lt['sender_id'],
                                            'photo'         => $lt['photo'],
                                            'created_time'  => date('YmdHis',$lt['created_time'])
                                        );
                                        $jArray['life_time'][]=$life_time; 
                                    }
                                }
                            }
                            $inPostActivity=$this->db->selectAll($this->general->table($cTbl),"where created_time<".$mainCommentCreated." and parent_id='".$post_id."' order by created_time desc Limit 5");
                            $times[__LINE__]=microtime().' '.date('h:i:s');
                            if(!empty($inPostActivity)){
                                foreach($inPostActivity as $ia){
                                    $names[$ia['sender_id']]=$ia['sender_id'];
                                    $post_ids[$ia['post_id']]=$ia['post_id'];
                                    $post_activity=array(
                                        'comment_id'    => $ia['comment_id'],
                                        'post_id'       => $next['post_id'],
                                        'parent_id'     => $ia['parent_id'],
                                        'message'       => $ia['message'],
                                        'sender_id'     => $ia['sender_id'],
                                        'photo'         => $ia['photo'],
                                        'created_time'  => date('YmdHis',$ia['created_time'])
                                    );
                                    $jArray['post_activity'][]=$post_activity; 
                                }
                            }
                            $jArray['target_sender_name']='';
                            if(!empty($names)){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $senderName=$this->getNamesByUserId(array($jArray['target_sender_id']));
                                if(!empty($senderName)){
                                    $jArray['target_sender_name']=$senderName[0]['name'];
                                }
                                $jArray['names']=$this->getNamesByUserId($names);
                            }
                            if(!empty($post_ids)){
                                $jArray['post_i']=$post_ids;
                                $jArray['post_ids']=$this->getPostPermalinkUrlBiIds($post_ids,$type);
                            }
                            else{
                                $jArray['post_i']='empty';
                            }
                        }
                        else{
                            if(isset($assignd)){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $data=array('assignTo'=>0,'assignTime'=>0);
                                $where=array('comment_id'=>$assignd);
                                $this->db->update($this->general->table($cTbl),$data,$where);
                            }
                        }
                        if(!isset($error)){
                            if($makeAssign==1){
                                $times[__LINE__]=microtime().' '.date('h:i:s');
                                $data=array('assignTime'=>time(),'assignTo'=>UID);
                                $where=array('comment_id'=>$jArray['target']);
                                $this->db->update($this->general->table($cTbl),$data,$where);
                            }
                        }
                    }
                }
            }
            $this->messageEmoji($jArray);
            if(isset($error)){
                $jArray[__LINE__]=$error;
            }
            $times[__LINE__]=microtime().' '.date('h:i:s');
            $jArray['times']=$times;
            return $jArray;
        }
        function newLike($comment_id,$type,$targetType='p'){
            $status=false;
            if($type=='c'){
                $c=$this->db->get_rowData($this->general->table(13),'comment_id',$comment_id);
                if(!empty($c)){
                    $lData=$this->db->get_rowData($this->general->table(27),'comment_id',$comment_id);
                    if(empty($lData)){
                        $fb=$this->fbInit();
                        try {
                            $status=true;
                            $d=$fb->post('/'.$comment_id.'/likes', 
                                array(
                                    'access_token' => ACCESS_TOKEN
                            ));
                            $data=array(
                                'comment_id'=> $comment_id,
                                'likeTime'  => TIME,
                                'uID'       => UID
                            );
                            $this->db->insert($this->general->table(27),$data);
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {
                            SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                        }
                    }
                }
            }
            elseif($type=='w'){
                if($targetType=='p'){
                    $c=$this->db->get_rowData($this->general->table(12),'post_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(28),'post_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->post('/'.$comment_id.'/likes');
                                $data=array(
                                    'post_id'=> $comment_id,
                                    'likeTime'  => TIME,
                                    'uID'       => UID
                                );
                                $insert=$this->db->insert($this->general->table(28),$data);
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
                else{
                    $c=$this->db->get_rowData($this->general->table(14),'comment_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(8),'comment_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->post('/'.$comment_id.'/likes'/*, 
                                    array(
                                    'access_token' => ACCESS_TOKEN
                                )*/);
                                $data=array(
                                    'comment_id'=> $comment_id,
                                    'likeTime'  => TIME,
                                    'uID'       => UID
                                );
                                $insert=$this->db->insert($this->general->table(8),$data);
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
            }
            return $status;
        }
        function newLikeForTemp($comment_id,$type,$targetType='p'){
            $status=false;
            if($type=='c'){
                $c=$this->db->get_rowData($this->general->table(13),'comment_id',$comment_id);
                if(!empty($c)){
                    $lData=$this->db->get_rowData($this->general->table(27),'comment_id',$comment_id);
                    if(empty($lData)){
                        $fb=$this->fbInit();
                        try {
                            $status=true;
                            $d=$fb->post('/'.$comment_id.'/likes', 
                                array(
                                    'access_token' => ACCESS_TOKEN
                            ));
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {
                            SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                        }
                    }
                }
            }
            elseif($type=='w'){
                if($targetType=='p'){
                    $c=$this->db->get_rowData($this->general->table(12),'post_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(28),'post_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->post('/'.$comment_id.'/likes');
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
                else{
                    $c=$this->db->get_rowData($this->general->table(14),'comment_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(8),'comment_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->post('/'.$comment_id.'/likes'/*, 
                                    array(
                                    'access_token' => ACCESS_TOKEN
                                )*/);
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
            }
            return $status;
        }
        function newTempLikeDelete($comment_id,$type,$targetType='p',&$jArray=array()){
            $status=false;
            if($type=='c'){
                $c=$this->db->get_rowData($this->general->table(13),'comment_id',$comment_id);
                if(!empty($c)){
                    $lData=$this->db->get_rowData($this->general->table(27),'comment_id',$comment_id);
                    if(empty($lData)){
                        $fb=$this->fbInit();
                        try {
                            $status=true;
                            $d=$fb->delete('/'.$comment_id.'/likes', 
                                array(
                                    'access_token' => ACCESS_TOKEN
                            ));
                            $jArray[__LINE__]=$d->getDecodedBody();
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {
                            SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                        }
                    }
                }
            }
            elseif($type=='w'){
                if($targetType=='p'){
                    $c=$this->db->get_rowData($this->general->table(12),'post_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(28),'post_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->delete('/'.$comment_id.'/likes');
                                $jArray[__LINE__]=$d->getDecodedBody();
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
                else{
                    $c=$this->db->get_rowData($this->general->table(14),'comment_id',$comment_id);
                    if(!empty($c)){
                        $lData=$this->db->get_rowData($this->general->table(8),'comment_id',$comment_id);
                        if(empty($lData)){
                            $fb=$this->fbInit();
                            try {
                                $status=true;
                                $d=$fb->delete('/'.$comment_id.'/likes'/*, 
                                    array(
                                    'access_token' => ACCESS_TOKEN
                                )*/);
                                $jArray[__LINE__]=$d->getDecodedBody();
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__); 
                            }
                        }
                    }
                }
            }
            return $status;
        }
        function getPostPermalinkUrlBiIds($post_ids,$type){
            $s=new social2();
            return $s->getPostPermalinkUrlBiIds($post_ids,$type);
        }
        function getScentimentTitleById($scentiment){
            if($scentiment==SCENTIMENT_TYPE_NEGETIVE){$type='Negative';}
            else if($scentiment==SCENTIMENT_TYPE_NUTRAL){$type='Neutral';}
                else if($scentiment==SCENTIMENT_TYPE_POSITIVE){$type='Positive';}
                    else{$type='Unknown.';}
            return $type;
        }
        function getTypeIdByItem($item){
            $type=0;
            if($item=='status'){$type=POST_TYPE_STATUS;}
            else if($item=='photo'){$type=POST_TYPE_PHOTO;}
                else if($item=='post'){$type=POST_TYPE_POST;}
                    else if($item=='video'){$type=POST_TYPE_VIDEO;}
                        else if($item=='link'){$type=POST_TYPE_LINK;}
                            else if($item=='note'){$type=POST_TYPE_NOTE;}
                                return $type;
        }
        function commentPostDone($type,$comment_id,&$jArray=array()){
            $data=array('replyed'=>1,'replyTime'=>TIME,'replyBy'=>UID,'isDone'=>1);
            if(isset($_POST['scentiment'])){
                $data['scentiment']=intval($_POST['scentiment']);
            }
            if(isset($_POST['wrapupId'])){
                $data['wuID']=intval($_POST['wrapupId']);
            }
            if($type=='c'){
                $where=array('comment_id'=>$comment_id);
                $update=$this->db->update($this->general->table(13),$data,$where);
                $jArray[__LINE__]=$update;
                $update=$this->db->update($this->general->table(63),$data,$where);
                //                textFileWrite(__FILE__.' '.__LINE__.' '.$comment_id,'replyed.txt');
                //$a=debug_backtrace();
                //                textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($a),'replyed.txt');
                //                textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($_POST),'replyed.txt');
                $jArray[__LINE__]=$update;
                $jArray['status']=$update;
                return true;
            }
            else if($type=='w'){
                if($jArray['targetType']=='p'){
                    $where=array('post_id'=>$comment_id);
                    $update=$this->db->update($this->general->table(12),$data,$where);
                    $jArray[__LINE__]=$update;
                    $update=$this->db->update($this->general->table(64),$data,$where);
                    //                    textFileWrite(__FILE__.' '.__LINE__.' '.$comment_id,'replyed.txt');
                    //$a=debug_backtrace();
                    //                    textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($a),'replyed.txt');
                    //                    textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($_POST),'replyed.txt');
                    $jArray[__LINE__]=$update;
                    $jArray['status']=$update;
                }
                else{
                    $where=array('comment_id'=>$comment_id);
                    $update=$this->db->update($this->general->table(14),$data,$where);
                    $jArray[__LINE__]=$update;
                    $update=$this->db->update($this->general->table(65),$data,$where);
                    //                    textFileWrite(__FILE__.' '.__LINE__.' '.$comment_id,'replyed.txt');
                    $jArray[__LINE__]=$update;
                    $jArray['status']=$update;
                }
                return true;
            }
            return false;
        }
        function wallPostCommentDone($comment_id){
            $data=array('replyed'=>1,'replyTime'=>TIME,'replyBy'=>UID,'isDone'=>1);
            $where=array('comment_id'=>$comment_id);
            return $this->db->update($this->general->table(14),$data,$where);

        }
        function hideFromFB($comment_id,$type,$targetType='p'){
            $status=false;
            if($type=='c'){
                $columnNmae='comment_id';
                $cTbl=13;
                $hTbl=31;
            }
            else if ($type=='w'){
                if($targetType=='p'){
                    $columnNmae='post_id';
                    $cTbl=12;
                    $hTbl=35;
                }
                else{
                    $columnNmae='post_id';
                    $cTbl=14;
                    $hTbl=32;
                }
            }
            else{
                $status=false;
            }
            $fb=$this->fbInit();
            try {
                $hide = $fb->post('/'.$comment_id.'?is_hidden=true');
                if(isset($hide->getDecodedBody()['success'])){
                    //textFileWrite('['.date('d/m/Y h:i:s').']'.__DIR__.'/'.__FILE__.' : '.__LINE__.'-> hide success '.$comment_id,'fb_error.txt');
                    //SetMessage(5,'Hide Success'); 
                    $old=$this->db->get_rowData($this->general->table($hTbl),$columnNmae,$comment_id);
                    if(empty($old)){
                        $data=array(
                            $columnNmae=> $comment_id,
                            'uID'       => UID,
                            'hideTime'  => TIME
                        );
                        $this->db->insert($this->general->table($hTbl),$data);
                        //SetMessage(5,$a);
                    }
                    $status=true;
                }
                else{
                    $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . json_decode($hide->getDecodedBody())."\n ";
                    textFileWrite($er,'fb_error.txt');
                }
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                $status=false;
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $comment_id." ".$type." ".$targetType."  \n ". $e->getCode().' description '.$e->getMessage();
                textFileWrite($er,'fb_error.txt');
                textFileWrite(json_encode($_POST),'fb_error.txt');
                $a=debug_backtrace();;
                textFileWrite(json_encode($a),'fb_error.txt');
                SetMessage(4,'Graph returned an error: ' . $e->getCode().' description '.$e->getMessage().' line '.__LINE__);

            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                $status=false;
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $comment_id."\n ". $e->getCode().' description '.$e->getMessage();
                textFileWrite($er,'fb_error.txt');
                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__);
            }
            return $status;
        }
        function banFromFB($type,$banData,$doneAlso=true,$targetType='p',&$jArray=array()){
            $status=false;
            if($type=='c'){
                $jArray[__LINE__]=__LINE__;
                foreach($banData as $comment_id=>$sender_id){
                    $comment=$this->db->get_rowData($this->general->table(13),'comment_id',$comment_id);
                    if(!empty($comment)){
                        $jArray[__LINE__]=__LINE__;
                        if($comment['sender_id']==$sender_id){
                            $jArray[__LINE__][]=$sender_id;
                            $fb=$this->fbInit();
                            try {
                                $jArray[__LINE__]=APPID;
                                $ban = $fb->post('/'.APPID.'/banned', array(
                                    //                                    'access_token' => ACCESS_TOKEN,
                                    'access_token' =>'572769266255230|YEtTfC6M69YZMoBguY63JbPMEeQ',
                                    'uid' => $sender_id
                                    )
                                );
                                if(isset($ban->getDecodedBody()[$sender_id])){
                                    if($ban->getDecodedBody()[$sender_id]==1){
                                        $jArray[__LINE__]=__LINE__;
                                        $old=$this->db->get_rowData($this->general->table(20),'sender_id',$sender_id);
                                        if(empty($old)){
                                            $data=array(
                                                'sender_id'   => $sender_id,
                                                'type'        => $type,
                                                'type_id'     => $comment_id,
                                                'uID'         => UID,
                                                'banTime'     => TIME
                                            );
                                            $this->db->insert($this->general->table(20),$data);
                                        }
                                        $status=true;
                                        if($doneAlso===true){
                                            $this->commentPostDone($type,$comment_id);
                                        }
                                    }
                                }
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                $jArray[__LINE__]=$e->getMessage();
                                $status=false;
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' description '.$e->getMessage().' line '.__LINE__);
                                break;

                            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                                $jArray[__LINE__]=$e->getMessage();
                                $status=false;
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__);
                                break;
                            }
                        }
                    }
                }
            }
            else if($type=='w'){
                foreach($banData as $comment_id=>$sender_id){
                    if(is_array($sender_id)){
                        $targetType=$sender_id[1];
                        $sender_id=$sender_id[0];
                    }
                    else{
                        $targetType='p';
                    }
                    if($targetType=='p'){
                        $tbl=12;
                        $columnID='post_id';
                    }
                    else{
                        $tbl=14;
                        $columnID='comment_id';
                    }
                    $comment=$this->db->get_rowData($this->general->table($tbl),$columnID,$comment_id);
                    if(!empty($comment)){
                        if($comment['sender_id']==$sender_id){
                            $fb=$this->fbInit();
                            try {
                                $ban = $fb->post('/'.PAGE_ID.'/blocked', array(
                                    'uid' => $sender_id
                                    )
                                );
                                if(isset($ban->getDecodedBody()[$sender_id])){
                                    if($ban->getDecodedBody()[$sender_id]==1){
                                        $old=$this->db->get_rowData($this->general->table(20),'sender_id',$sender_id);
                                        if(empty($old)){
                                            $data=array(
                                                'sender_id'   => $sender_id,
                                                'type'        => $type.'_'.$targetType,
                                                'type_id'     => $comment_id,
                                                'uID'         => UID,
                                                'banTime'     => TIME
                                            );
                                            $this->db->insert($this->general->table(20),$data);
                                        }
                                        $status=true;
                                        if($doneAlso===true){
                                            $jArray['targetType']=$targetType;
                                            $this->commentPostDone($type,$comment_id,$jArray);
                                        }
                                    }
                                }else{
                                    textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".json_encode($comment));
                                }
                            }
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                $status=false;
                                textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".$e->getCode().' description '.$e->getMessage());
                                SetMessage(4,'Graph returned an error: ' . $e->getCode().' description '.$e->getMessage());
                                break;

                            } 
                            catch(Facebook\Exceptions\FacebookSDKException $e) {
                                $status=false;
                                textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".$e->getCode().' description '.$e->getMessage());
                                SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage());
                                break;
                            }
                        }
                        else{
                            textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".json_encode($banData));
                        }
                    }
                    else{
                        textFileWrite("[".date("d-m-Y h:i:s")."]".__FILE__." line ".__LINE__."\n".json_encode($sender_id));
                    }
                }
            }
            return $status;
        }
        function fbInit(){
            require_once ROOT_PATH . '/vendor/autoload.php';
            $v ='v2.10';
            $fb = new Facebook\Facebook([
                'app_id' => APPID,
                'app_secret' => APPSECRET,
                'default_graph_version' => $v,
            ]);
            $fb->setDefaultAccessToken(ACCESS_TOKEN);
            return $fb;
        }
        function assignmentData($type,$from='',$to='',$keyword=''){
            if($type=='w'){
                $socisl2=new social2();
                return $socisl2->assignmentWallPostCommentData($from,$to,$keyword);
            }
            else if($type=='m'){
                $socisl2=new social2();
                return $socisl2->assignmentWallPostCommentData($from,$to,$keyword);
            }
            else{
                $flowType=$this->getFlowType($type);
                if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
                    $orderBy=" order by created_time asc";
                }
                else{
                    $orderBy=" order by created_time desc";
                }
                $names=array();
                $comments=array();
                $post_ids=array();
                $query="SELECT
                c.comment_id,c.post_id,c.parent_id,c.sender_id,c.message,c.photo, c.created_time
                FROM ".$this->general->table(13)." c
                WHERE 
                c.comment_id not in
                (SELECT cd.comment_id FROM ".$this->general->table(10)." cd)
                and assignTo=0
                and c.replyed=0";
                if($from!=''&&$to!=''){
                    $query.=" and c.created_time between ".$from." and ".$to;    
                }
                if($keyword!=''){$query.=" and c.message like '%".$keyword."%'";}
                $query.=$orderBy." LIMIT 100
                ";
                $comm= $this->db->fetchQuery($query);
                foreach($comm as $c){
                    $names[$c['sender_id']]=$c['sender_id'];
                    $post_ids[$c['post_id']]=$c['post_id'];
                    $assignment_comment=array(
                        'comment_id'    => $c['comment_id'],
                        'post_id'       => $c['post_id'],
                        'parent_id'     => $c['parent_id'],
                        'message'       => $this->general->content_show($c['message'],'d'),
                        'sender_id'     => $c['sender_id'],
                        'photo'         => $c['photo'],
                        'created_time'  => date('YmdHis',$c['created_time'])
                    );
                    $comments[]=$assignment_comment; 
                }
                if(!empty($names)){
                    $names=$this->getNamesByUserId($names);
                }
                $post_ids=$this->getPostPermalinkUrlBiIds($post_ids,$type);
                $rt=array(
                    'comments'  => $comments,
                    'names'     => $names,
                    'post_ids'  => $post_ids
                );
                $rt['q']=$query;
                return $rt;
            }
        }
        function lastTime(&$jArray){$jArray['lastTime']=date('YmdHis',TIME);}
        function messageEmoji(&$jArray){
            if(isset($jArray['post'])){
                if(isset($jArray['post']['message'])){
                    $jArray['post']['message']=$this->stringToEmoji($jArray['post']['message']);
                }
            }
            if(isset($jArray['comments'])){
                foreach($jArray['comments'] as $k=>$v){
                    if(isset($jArray['comments'][$k]['message'])){
                        $jArray['comments'][$k]['message']=$this->stringToEmoji($jArray['comments'][$k]['message']);
                    }
                }
            }
            if(isset($jArray['life_time'])){
                foreach($jArray['life_time'] as $k=>$v){
                    if(isset($jArray['life_time'][$k]['message'])){
                        $jArray['life_time'][$k]['message']=$this->stringToEmoji($jArray['life_time'][$k]['message']);
                    }
                }
            }
            if(isset($jArray['post_activity'])){
                foreach($jArray['post_activity'] as $k=>$v){
                    if(isset($jArray['post_activity'][$k]['message'])){
                        $jArray['post_activity'][$k]['message']=$this->stringToEmoji($jArray['post_activity'][$k]['message']);
                    }
                }
            }
        }
        function stringToEmoji($string){
            $string=str_ireplace(':)','<img src="images/emo/smile.png">',$string);
            $string=str_ireplace(':(','<img src="images/emo/sad.png">',$string);
            $string=str_ireplace('<3','<img src="images/emo/heart.png">',$string);
            $string=str_ireplace(':D','<img src="images/emo/laugh.png">',$string);
            $string=str_ireplace('(y)','<img src="images/emo/like.png">',$string);
            return $string;
        }
        function commentInfoUpdateAfterReply($targetDataUpdate,$targetCommentID,$type,$comment_id=''){
            if($type=='c'){
                $cTbl=13;
                $where=array('comment_id'=>$targetCommentID);
                $update=$this->db->update($this->general->table($cTbl),$targetDataUpdate,$where);
                $update=$this->db->update($this->general->table(63),$targetDataUpdate,$where);
                //                textFileWrite(__FILE__.' '.__LINE__.' '.$targetCommentID,'replyed.txt');
                //                $a=debug_backtrace();
                //                    textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($a),'replyed.txt');
                //                    textFileWrite(__FILE__.' '.__LINE__.' '.json_encode($_POST),'replyed.txt');
            }
            elseif($type=='w'){

            }
        }
        function commentQueueResend($q){
            $return=array('status'=>0);
            $comment_id=$q['target_c_id'];
            $rqType=$q['sendType'];
            $fb = $this->fbInit();
            try {
                $d=$fb->post('/'.$comment_id.'/'.$rqType,array('message' => $this->general->content_show($q['message'])));
                if(isset($d->getDecodedBody()['id'])){
                    $return['status']=1;
                    $newCommentID=$d->getDecodedBody()['id'];
                    $newCommentData=array(
                        'post_id'       => $q['post_id'],
                        'parent_id'     => $q['parent_id'],
                        'target_c_id'   => $q['target_c_id'],
                        'wuID'          => $q['wuID'],
                        'message'       => $q['message'],
                        'scentiment'    => $q['scentiment'],
                        'replyed'       => 1,
                        'replyBy'       => $q['replyBy'],
                        'replyTime'     => $q['replyTime'],
                    );
                    if($rqType!='comments'){
                        $newCommentData['message']='<b>Private</b>: '.$newCommentData['message'];
                    }
                    $newCommentData['comment_id']=$newCommentID;
                    $newCommentData['sender_id']=PAGE_ID;
                    $newCommentData['created_time']=TIME;
                    $insert=$this->db->insert($this->general->table(13),$newCommentData);
                    $targetDataUpdate=array('replyed'=>1);
                    $this->commentInfoUpdateAfterReply($targetDataUpdate,$q['target_c_id'],'c');
                    if($q['deleteTarget']==1){ 
                        $cm=$this->getCommentInfoById($q['target_c_id'],'c');
                        $this->deleteCommentByAgent($cm,'c');
                    }
                    $data=array('sendSuccess'=>1);
                    $where=array('scqID'=>$q['scqID']);
                    $this->db->update($this->general->table(41),$data,$where);
                }
                else{
                    $return['totalTry']=$q['totalTry']+1;
                    $return['errorCode']=$e->getCode();
                    $return['errorMessage']=$e->getMessage();
                    $data=array(
                        'totalTry'=>$q['totalTry']+1,
                        'errorCode'=>$e->getCode(),
                        'errorMessage'=>$e->getMessage()
                    );
                    $where=array('scqID'=>$q['scqID']);
                    $this->db->update($this->general->table(41),$data,$where);
                }
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                $return['totalTry']=$q['totalTry']+1;
                $return['errorCode']=$e->getCode();
                $return['errorMessage']=$e->getMessage();
                $data=array(
                    'totalTry'=>$q['totalTry']+1,
                    'errorCode'=>$e->getCode(),
                    'errorMessage'=>$e->getMessage()
                );
                $where=array('scqID'=>$q['scqID']);
                $this->db->update($this->general->table(41),$data,$where);

            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
                $return['totalTry']=$q['totalTry']+1;
                $return['errorCode']=$e->getCode();
                $return['errorMessage']=$e->getMessage();
                $data=array(
                    'totalTry'=>$q['totalTry']+1,
                    'errorCode'=>$e->getCode(),
                    'errorMessage'=>$e->getMessage()
                );
                $where=array('scqID'=>$q['scqID']);
                $this->db->update($this->general->table(41),$data,$where);

            }
            return $return;
        }
        function senderNameUpdate($id,$name){
            $s=new social2();
            return $s->senderNameUpdate($id,$name);
        }
        function getAgentTodayActivity($uID){
            $logins=$this->db->selectAll($this->general->table(18),'where uID='.$uID.' and ulsStartTime between '.TODAY_TIME.' and '.TOMORROW_TIME);
            $duration=0;
            if(!empty($logins)){
                foreach($logins as $l){
                    $duration+=$l['ulsService'];
                }
            }
            return $duration;
        }
        function getCurrentAssigned($type='c',$echo='No'){
            if($type=='c'){
                $query="
                SELECT 
                c.comment_id,c.message,c.post_id,c.parent_id,c.created_time,c.assignTime,c.photo,'c' as targetType,
                u.uFullName
                FROM comments_status c
                LEFT JOIN useraccount u ON u.uID=c.assignTo
                WHERE c.assignTo!=0 AND c.replyed=0;
                ";
                return $this->db->fetchQuery($query,$echo);
            }
            else if($type=='w'){
                $query="
                SELECT 
                c.post_id as comment_id,c.message,c.post_id,'' as parent_id,c.created_time,c.assignTime,c.link,'p' as targetType,
                u.uFullName
                FROM post_wall c
                LEFT JOIN useraccount u ON u.uID=c.assignTo
                WHERE c.assignTo!=0 AND c.replyed=0 
                UNION
                SELECT 
                c.comment_id,c.message,c.post_id,c.parent_id,c.created_time,c.assignTime,c.photo,'c' as targetType,
                u.uFullName
                FROM comments_wall c
                LEFT JOIN useraccount u ON u.uID=c.assignTo
                WHERE c.assignTo!=0 AND c.replyed=0
                ";
                return $this->db->fetchQuery($query,$echo);
            }
            elseif($type=='m'){
                //$echo='d';
                $query="
                SELECT 
                c.mid as comment_id,".$this->general->mDec('c.text','message').",c.sendTime as created_time,c.assignTime,c.url as photo,
                u.uFullName
                FROM ".$this->general->table(9)." c
                LEFT JOIN useraccount u ON u.uID=c.assignTo
                WHERE c.assignTo!=0 AND c.replyed=0
                order by c.assignTo,c.seq asc
                ";
                return $this->db->fetchQuery($query,$echo);
            }
            else{return array();}
        }
        function messageClearOldAssigned(){
            $query="update ".$this->general->table(16)." set assignTo=0,assignTime=0 where replyed=0 and assignTo!=0 and assignTime<".strtotime('- 20 minute')." and assignLastCheck<".strtotime('- 20 minute');
            $this->db->runQuery($query);
            $query="update ".$this->general->table(67)." set assignTo=0,assignTime=0 where replyed=0 and assignTo!=0 and assignTime<".strtotime('- 20 minute')." and assignLastCheck<".strtotime('- 20 minute');
            $this->db->runQuery($query);
            $this->db->runQuery("delete from ".$this->general->table(67)." where replyed>0 and replyTime<".strtotime(' - 1 hour')." and lastUpdate < ".strtotime(' - 1 hour'));
            $this->db->runQuery("delete from ".$this->general->table(66)." where replyed>0 and replyTime<".strtotime(' - 1 hour'));
        }
        function messageNewSenderMsgLoad(&$jArray=array(),$currentService=array()){
            $times=array();
            $times[__LINE__]=microtime().' '.date('m:i:s');
            $this->messageClearOldAssigned();
            $messageMaxService=intval($this->db->settingsValue('messageMaxService'));
            //$jArray[__LINE__]=$messageMaxService;
            $wq='';
            if($messageMaxService>0){
                $times[__LINE__]=microtime().' '.date('m:i:s');
                //$jArray[__LINE__]=$messageMaxService;
                if(count($currentService)>0){
                    $query="UPDATE ".$this->general->table(16)." SET assignLastCheck=".TIME." WHERE  sender_id in (".implode(',',$currentService).");";
                    //$jArray[__LINE__][]=$query;
                    $this->db->runQuery($query,'array',$jArray);
                    $query="UPDATE ".$this->general->table(67)." SET assignLastCheck=".TIME." WHERE  sender_id in (".implode(',',$currentService).");";
                    //$jArray[__LINE__][]=$query;
                    $this->db->runQuery($query,'array',$jArray);
                    /*$query="UPDATE ".$this->general->table(62)." SET assignTime=".TIME." WHERE  sender_id in (".implode(',',$currentService).");";
                    $this->db->runQuery($query,'array',$jArray);*/
                    $wq=' and sender_id not in('.implode(',',$currentService).')';
                }
                $messageMaxService-=count($currentService);
                $limit=' limit '.$messageMaxService;
            }

            $query="delete from ".$this->general->table(62)." WHERE  assignTime<".strtotime('-10 minute');
            $query="delete from ".$this->general->table(62)." WHERE  assignTime<".strtotime('-10 second');
            $this->db->runQuery($query,'array',$jArray);

            //$jArray[__LINE__]=__LINE__;
            $flowType=$this->getFlowType('m');
            if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
                $orderBy=' order by lastUpdate asc';
            }
            else{
                $orderBy=' order by lastUpdate desc';
            }
            //$senders=$this->db->selectAll($this->general->table(67),'where assignTo='.UID.' and sender_id not in(select sender_id from '.$this->general->table(62).' where uID='.UID.') and replyed=0'.$wq.$orderBy.$limit,'','array',$jArray);
            $times[__LINE__]=microtime().' '.date('m:i:s');
            $jArray['senders']=array();
            //$senders=array();
            if(!empty($senders)&&0){
                $ts=array();
                if(count($currentService)>0){
                    foreach($currentService as $cr){
                        $ts[]=$cr;
                    }
                }
                foreach($senders as $sender){
                    $ts[]=$sender['sender_id'];
                }
                foreach($senders as $sender){
                    $times[__LINE__]=microtime().' '.date('m:i:s');
                    $message=$this->db->getRowDataWithColumn($this->general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc",'sendTime,'.$this->general->mDec('text').',url');
                    $times[__LINE__]=microtime().' '.date('m:i:s');
                    $attachment=array();
                    if(json_decode($message['url'])!=null){
                        $attachment=json_decode($message['url'],true);
                        $message['url']='';
                    }
                    $jArray[__LINE__]=$sender;
                    $sd=array(
                        'sender_id'         => $sender['sender_id'],
                        'senderName'        => $sender['senderName'],
                        'senderPictureLink' => $sender['senderPictureLink'],
                        'text'              => $message['text'],
                        'url'               => $message['url'],
                        'at'                => $attachment,
                        'sendTime'          => date('d-m-Y h:i:s A',$message['sendTime']),
                        'sendTimestamp'     => $message['sendTime'],
                    );
                    $jArray['senders'][]=$sd;
                    $data=array(
                        'uID'       =>UID,
                        'sender_id' =>$sender['sender_id'],
                        'assignTime'=>TIME
                    );
                    $this->db->insert($this->general->table(62),$data,'','array');
                }
            }
            //$jArray[__LINE__]=count($jArray['senders']);
            if(count($jArray['senders'])<$messageMaxService){
                if($messageMaxService>0){
                    $limit=$messageMaxService-count($jArray['senders']);
                }
                $senders=$this->db->selectAll($this->general->table(67),'where assignTo=0 and sender_id not in(select sender_id from '.$this->general->table(62).') and replyed=0'.$wq.$orderBy.' limit '.$limit,'','array',$jArray);
                $jArray[__LINE__]=$senders;
                if(!empty($senders)){
                    $times[__LINE__]=microtime().' '.date('m:i:s');
                    foreach($senders as $sender){
                        $times[__LINE__]=microtime().' '.date('m:i:s');
                        $message=$this->db->getRowDataWithColumn($this->general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc",'sendTime,'.$this->general->mDec('text').',url');
                        $times[__LINE__]=microtime().' '.date('m:i:s');
                        $attachment='';
                        if(json_decode($message['url'])!=null){
                            $attachment=json_decode($message['url'],true);
                            $message['url']='';
                        }
                        //$jArray[__LINE__][]=$sender;
                        if($sender['senderName']==''){
                            $sInfo=$this->db->get_rowData($this->general->table(16),'sender_id',$sender['sender_id']);
                            if(!empty($sInfo)){
                                if($sInfo['senderName']!=''){
                                    $sender['senderName']=$sInfo['senderName'];
                                    $sender['senderPictureLink']=$sInfo['senderPictureLink'];
                                    $data=array(
                                        'senderName'        => $sInfo['senderName'],
                                        'senderPictureLink' => $sInfo['senderPictureLink']
                                    );
                                    $where=array(
                                        'sender_id'=>$sender['sender_id']
                                    );
                                    $this->db->update($this->general->table(67),$data,$where);
                                }
                            }
                        }
                        $sd=array(
                            'sender_id'         => $sender['sender_id'],
                            'senderName'        => $sender['senderName'],
                            'senderPictureLink' => $sender['senderPictureLink'],
                            'text'              => $message['text'],
                            'url'               => $message['url'],
                            'at'                => $attachment,
                            'sendTime'          => date('d-m-Y h:i:s A',$message['sendTime']),
                            'sendTimestamp'     => $message['sendTime'],
                        );
                        $data=array(
                            'uID'       =>UID,
                            'sender_id' =>$sender['sender_id'],
                            'assignTime'=>TIME
                        );
                        $insert=$this->db->insert($this->general->table(62),$data);
                        if($insert===true){
                            $jArray['senders'][]=$sd;
                        }

                    }
                }
            }
            else{
                $jArray[__LINE__][]=count($jArray['senders']).' - '.$messageMaxService;
            }
            if(!empty($jArray['senders'])){
                $jArray['status']=1;
                if(!empty($jArray['senders'])){
                    $times[__LINE__]=microtime().' '.date('m:i:s');
                    foreach($jArray['senders'] as $sender){
                        $times[__LINE__][]=microtime().' '.date('m:i:s');
                        $messages=$this->db->selectAll($this->general->table(9),"where sender_id='".$sender['sender_id']."' order by sendTime desc limit 50",'mid,sendTime,sender_id,'.$this->general->mDec('text').',url,sendType');
                        if(!empty($messages)){
                            krsort($messages);
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
                                $jArray['message'][$sender['sender_id']][]=$message;
                            }

                            $data=array(
                                'assignTo'  => UID,
                                'assignTime'=> TIME
                            );
                            $where=array('sender_id'=>$sender['sender_id']);
                            $update=$this->db->update($this->general->table(16),$data,$where);
                            $update=$this->db->update($this->general->table(67),$data,$where);

                            $query="
                            update ".$this->general->table(9)." set assignTo=".UID.",assignTime=".TIME." where sender_id=".$m['sender_id']." and sendTime<=".TIME." and sendType=1 and replyed=0";
                            $update=$this->db->runQuery($query);
                            $times[__LINE__]=microtime().' '.date('m:i:s');
                            $query="
                            update ".$this->general->table(66)." set assignTo=".UID.",assignTime=".TIME." where sender_id=".$m['sender_id']." and sendTime<=".TIME." and sendType=1 and replyed=0";
                            $update=$this->db->runQuery($query);
                        }
                    }
                }
            }
            $times[__LINE__]=microtime().' '.date('m:i:s');
            $jArray['t']=$times;
            textFileWrite(json_encode($times),'m.txt');
            //print_r($jArray);
            //echo __LINE__;exit;
            return true;
        }
    }
?>
