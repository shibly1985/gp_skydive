<?php
    class social2{
        public $db;
        public $general;
        public $social;
        function __construct(){
            $this->db=new DB();
            $this->general=new General();
            $this->social=new social();
        }
        function getPostPermalinkUrlBiIds($post_ids,$type){
            $return=array();
            if(!empty($post_ids)){
                if($type=='c'){
                    $tbl=4;
                    $query="select post_id,permalink_url from ".$this->general->table($tbl)." where post_id in('".implode("','",$post_ids)."') ";
                }
                else{
                    $tbl=12;
                    $query="
                    select post_id,permalink_url from ".$this->general->table($tbl)." where post_id in('".implode("','",$post_ids)."') 
                    union
                    select post_id_2,permalink_url from ".$this->general->table($tbl)." where  post_id_2 in('".implode("','",$post_ids)."')";}

                $posts=$this->db->fetchQuery($query);
                //$return[__LINE__]=$query;
                //$return[__LINE__]=$posts;

                foreach($posts as $p){
                    $post_id=$p['post_id'];
                    //                    $return['a'.$post_id]=$post_id;
                    if($p['permalink_url']!=''){
                        $return[$post_id]=$p['permalink_url'];
                    }
                    else{
                        $fb=$this->social->fbInit();
                        try {
                            $rqUrl = '/'.$post_id.'/?fields=id,permalink_url';
                            $response = $fb->get($rqUrl);
                            if(isset($response->getDecodedBody()['id'])){
                                $permalink_url=$response->getDecodedBody()['permalink_url'];
                                $return[$post_id]=$permalink_url;
                                $data=array(
                                    'permalink_url'=>$permalink_url
                                );
                                $where=array('post_id'=>$post_id);
                                $update=$this->db->update($this->general->table($tbl),$data,$where);
                                //                                $return[__LINE__]=$update;
                            }
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {

                            $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                            $return[$p['post_id']]=$e->getCode();
                            if($e->getCode()!==100){
                                textFileWrite($er,'fb_error.txt');
                            }
                            if(SHOW_ERROR_LINE=='Yes'){
                                SetMessage('Graph returned an error: ' . $e->getMessage().' Line '.__LINE__);   
                            }
                            $error=__LINE__;
                        } 
                        catch(Facebook\Exceptions\FacebookSDKException $e) {
                            $return[$p['post_id']]=$e->getCode();
                            $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                            textFileWrite($er,'fb_error.txt');
                            if(SHOW_ERROR_LINE=='Yes'){
                                SetMessage('Facebook SDK returned an error: ' . $e->getMessage().' Line '.__LINE__);
                            }
                        }
                    }
                }
                foreach($post_ids as $p){
                    if(!array_key_exists($p,$return)){
                        $fb=$this->social->fbInit();
                        try {
                            $jArray[__LINE__]=__FILE__.' l '.__LINE__;

                            //$rqUrl = '/'.$p.'/?fields=id,permalink_url';
                            $rqUrl = '/'.$p.'/?fields=id,created_time,from,link,message,permalink_url,picture,type';
                            $response = $fb->get($rqUrl);
                            if(isset($response->getDecodedBody()['id'])){
                                $d=$response->getDecodedBody();
                                $return[$post_id]=$d['permalink_url'];
                                $v=array(
                                    'value'=>array(
                                        'post_id'       => $d['id'],
                                        'created_time'  => strtotime($d['created_time']),
                                        'created_time2'  => $this->general->make_date(strtotime($d['created_time']),'time'),
                                        'item'          => $d['type'],
                                        'permalink_url' => $d['permalink_url'],
                                        'message'       => ''
                                    )
                                );
                                if(isset($d['attachment'])){$v['value']['photo']=$d['attachment']['media']['image']['src'];}
                                if(isset($d['message'])){$v['value']['message']=$d['message'];}
                                if(isset($d['picture'])){$v['value']['picture']=$d['picture'];}

                                $this->social->newPost($v,strtotime($d['created_time']),$jArray);
                            }
                        }
                        catch(Facebook\Exceptions\FacebookResponseException $e) {

                            $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                            $return[$p['post_id']]=$e->getCode();
                            if($e->getCode()!==100){
                                textFileWrite($er,'fb_error.txt');
                            }
                            if(SHOW_ERROR_LINE=='Yes'){
                                SetMessage('Graph returned an error: ' . $e->getMessage().' Line '.__LINE__);   
                            }
                            $error=__LINE__;
                        } 
                        catch(Facebook\Exceptions\FacebookSDKException $e) {
                            $return[$p['post_id']]=$e->getCode();
                            $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                            textFileWrite($er,'fb_error.txt');
                            if(SHOW_ERROR_LINE=='Yes'){
                                SetMessage('Facebook SDK returned an error: ' . $e->getMessage().' Line '.__LINE__);
                            }
                        }
                    }
                }
            }

            return $return;
        }
        function makePostAssignUnionQuery($orderBy,$uid=0){
            $service24hours=intval($this->db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
            $service24hours=$service24hours==1?1:2;

            $timeQuery='';
            if($service24hours==2){
                $timeQuery=' and created_time<='.TIME;
            }

            $query="
            SELECT 
            p.post_id AS id,p.created_time,'p' AS activity
            FROM ".$this->general->table(64)." p
            WHERE p.replyed=0
            and p.post_id not in (select aw.post_id from ".$this->general->table(5)." aw)
            ";
            if($uid==0){
                $query.=" AND p.assignTime=0 AND p.assignTo=0";
            }
            else{
                $query.=" AND p.assignTo=".$uid;
            }
            if($service24hours==2){
                $timeQuery=' and p.created_time<='.TIME;
            }
            $query.=$orderBy;
            $query.=" limit 1";
            $next=$this->db->fetchQuery($query);
            if(!empty($next)){
                $next=$next[0];
                //                $next[__LINE__]=$query;
            }
            else{
                $query="
                SELECT 
                c.comment_id AS id,c.created_time,'c' AS activity
                FROM ".$this->general->table(65)." c
                WHERE c.replyed=0 AND c.sender_id!='".PAGE_ID."'
                and c.comment_id not in (select ac.comment_id from ".$this->general->table(36)." ac)
                ";
                if($uid==0){
                    $query.=" AND c.assignTime=0 AND c.assignTo=0";
                }
                else{
                    $query.=" AND c.assignTo=".$uid;
                }
                if($service24hours==2){
                    $timeQuery=' and c.created_time<='.TIME;
                }
                $query.=$orderBy;
                $query.=" limit 1";
                //echo $query;
                $next=$this->db->fetchQuery($query);
                if(!empty($next)){
                    $next=$next[0];
                    //$next[__LINE__]=$query;
                }

            }
            return $next;

            /* $p='p';
            $c='c';
            $where=array(
            'replyed'   => 0,
            'assignTime'=> 0,
            'assignTo'  => 0
            );
            $query="
            SELECT 
            p.post_id AS id,p.message,p.created_time,'p' AS activity
            FROM post_wall p
            WHERE ";
            $i=0;
            foreach($where as $k=>$v){
            if($i!=0){$query.=",";}
            $query.=$p.".".$k."=".$v;
            $i++;
            }

            $query.=" UNION
            SELECT 
            c.comment_id AS id,c.message, c.created_time,'c' AS activity
            FROM comments_wall c
            WHERE ";
            $i=0;
            foreach($where as $k=>$v){
            if($i!=0){$query.=",";}
            $query.=$c.".".$k."=".$v;
            $i++;
            }

            $query.="
            ORDER BY created_time DESC
            ";*/
        }
        function nextAssignPostSelect($flowType){
            if($flowType==WALL_POST_COMMENT_FLOW_FIFO){$orderBy=" order by created_time asc";}
            else{$orderBy=" order by created_time desc";}
            $targetType='';
            $target='';
            $pa=$this->db->getRowData($this->general->table(5),'where uID='.UID.$orderBy);
            $ca=$this->db->getRowData($this->general->table(36),'where uID='.UID.$orderBy);
            if(!empty($pa)&&empty($ca)){
                $targetType='p';
            }
            else if(empty($pa)&&!empty($ca)){
                $targetType='c';
            }
            else if(empty($pa)&&empty($ca)){
                if($pa['created_time']>$ca['created_time']){
                    if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
                        $targetType='p';
                    }
                    else{
                        $targetType='c';
                    }
                }
                else{
                    if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
                        $targetType='c';
                    }
                    else{
                        $targetType='p';
                    }
                }
            }
            else{
                return false;
            }
            if($targetType=='p'){
                $target=$pa['post_id'];
            }
            else if($targetType=='c'){
                $target=$ca['comment_id'];
            }
            return array('targetType'=>$targetType,'target'=>$target);
        }
        function newPostLoadFromFB($post_id,&$jArray=array()){
            $fb=$this->social->fbInit();
            try {
                $rqUrl = '/'.$post_id.'/?fields=type,from,created_time,message,permalink_url,picture';
                $response = $fb->get($rqUrl);
                if(isset($response->getDecodedBody()['id'])){
                    $d=$response->getDecodedBody();
                    $v=array(
                        'value'=>array(
                            'post_id'       => $d['id'],
                            'created_time'  => strtotime($d['created_time']),
                            'item'          => $d['type'],
                            'permalink_url' => $d['permalink_url'],
                            'sender_id'     => $d['from']['id'],
                            'message'       => ''
                        )
                    );
                    $jArray[__LINE__]=$v;
                    if(isset($d['picture'])){$v['value']['link']=$d['picture'];}
                    if(isset($d['message'])){$v['value']['message']=$d['message'];}

                    $jArray[__LINE__]=$comment['post_id'];
                    $this->social->newPost($v,strtotime($d['created_time']));
                    $jArray[__LINE__]=$v;
                }
                else{
                    $jArray['e']='Invalid post';
                }
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                textFileWrite($er,'fb_error.txt');
                if(SHOW_ERROR_LINE=='Yes'){
                    //SetMessage('Graph returned an error: ' . $e->getMessage().' Line '.__LINE__);   
                }
                if($e->getCode()==100){
                    return 'ne';
                }
                $error=__LINE__;
            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $e->getCode().'  '.$e->getMessage()."\n post_id ".$post_id;
                textFileWrite($er,'fb_error.txt');
                if(SHOW_ERROR_LINE=='Yes'){
                    //SetMessage('Facebook SDK returned an error: ' . $e->getMessage().' Line '.__LINE__);
                }
            }
        }
        function nextPicupWallPostOrComment(&$jArray){//wall post
            $flowType=$this->social->getFlowType('w');//lifo / fifo
            if($flowType==WALL_POST_COMMENT_FLOW_FIFO){$orderBy=" order by created_time asc";}
            else{$orderBy=" order by created_time desc";}
            $makeAssign=false;
            $asType=$this->social->getAssignType('w');//auto / manuyal
            $service24hours=intval($this->db->settingsValue('service24hours'));//1=24 hours and 2=custom hour
            $service24hours=$service24hours==1?1:2;

            $timeQuery='';
            if($service24hours==2){
                $timeQuery=' and created_time<='.TIME;
            }


            $cTbl=14;
            $rcTbl=65;
            $pTbl=12;
            $rpTbl=64;
            $jArray['targetType']='';
            $jArray['target']='';
            $lastCleare=intval($this->db->settingsValue('queueCleareComment'));
            if($lastCleare<strtotime('-10 minute')){
                $old=$this->db->selectAll($this->general->table($pTbl),"where replyed=0 and assignTo!=0 and assignTime<".strtotime('-15 minute'),'assignTo');
                $oldc=$this->db->selectAll($this->general->table($cTbl),"where replyed=0 and assignTo!=0 and assignTime<".strtotime('-15 minute'),'assignTo');
                if(!empty($old)){
                    $this->db->runQuery("update ".$this->general->table($pTbl)." set assignTo=0,assignTime=0 where assignTime<".strtotime('-15 minute')." and replyed=0 and assignTo!=0");
                    $this->db->runQuery("update ".$this->general->table($rpTbl)." set assignTo=0,assignTime=0 where assignTime<".strtotime('-15 minute')." and replyed=0 and assignTo!=0");
                }
                if(!empty($oldc)){
                    $this->db->runQuery("update ".$this->general->table($cTbl)." set assignTo=0,assignTime=0 where assignTime<".strtotime('-15 minute')." and replyed=0 and assignTo!=0");
                    $this->db->runQuery("update ".$this->general->table($rcTbl)." set assignTo=0,assignTime=0 where assignTime<".strtotime('-15 minute')." and replyed=0 and assignTo!=0");
                }
                $this->db->settingsUpdate(TIME,'queueCleareComment');
            }
            $query="delete from ".$this->general->table(57).' where assignTime<='.strtotime('-10 minute');
            $this->db->runQuery($query);//this query for cleare multiple reply
            $query="delete from ".$this->general->table(36).' where assign_time<='.strtotime('-30 minute');
            $this->db->runQuery($query);//this query for cleare multiple reply
            $query="delete from ".$this->general->table(64).' where replyed>0';
            $this->db->runQuery($query);
            $query="delete from ".$this->general->table(65).' where replyed>0';
            $this->db->runQuery($query);
            $query='DELETE FROM `no_rep_post_wall` WHERE post_id IN (
SELECT post_id
FROM `post_wall`
WHERE replyed in(1,2,3,4,5,6)
)
';
            //$this->db->runQuery($query);
            if($asType==ASSIGN_TYPE_AUTO&&1==11){//auto is currently inactive so avoid it
                $next=$this->db->getRowData($this->general->table($pTbl),"where replyed=0 and assignTo=".UID.$orderBy);
                if(empty($next)){
                    $next=$this->db->getRowData($this->general->table($pTbl),"where replyed=0".$orderBy);
                }
            }
            else{
                $commentType='';
                $targetID='';
                $nextQuery="where replyed=0";
                $abc=$this->makePostAssignUnionQuery($orderBy,UID);
                $jArray[__LINE__]=$abc;
                if(!empty($abc)){
                    if($abc['activity']=='p'){
                        $jArray[__LINE__][]=$abc['id'];
                        $next=$this->db->get_rowData($this->general->table($pTbl),'post_id',$abc['id']);
                        $c=$this->db->getRowData($this->general->table(5),"where uID=".UID." and post_id='".$next['post_id']."'");
                        if(empty($c)){
                            $data=array('assignTo'=>0,'assignTime'=>0);
                            $where=array('post_id'=>$next['post_id']);
                            $this->db->update($this->general->table($pTbl),$data,$where);
                            $this->db->update($this->general->table($rpTbl),$data,$where);
                        }
                        else{
                            $jArray['targetType']=$abc['activity'];
                            $jArray['target']=$abc['id'];
                        }
                    }
                    else{
                        //                        $jArray[__LINE__][]=$abc['id'];
                        $next=$this->db->get_rowData($this->general->table($cTbl),'comment_id',$abc['id']);
                        $jArray[__LINE__]=$next;
                        $c=$this->db->getRowData($this->general->table(36),"where uID=".UID." and comment_id='".$next['comment_id']."'");
                        if(empty($c)){
                            $data=array('assignTo'=>0,'assignTime'=>0);
                            $where=array('comment_id'=>$next['comment_id']);
                            $this->db->update($this->general->table($cTbl),$data,$where);
                            $this->db->update($this->general->table($rcTbl),$data,$where);
                        }
                        else{
                            $jArray['targetType']=$abc['activity'];
                            $jArray['target']=$abc['id'];
                            $got=1;
                        }
                    }
                }


                if($jArray['target']==''){
                    //take next assign post id
                    $g=$this->nextAssignPostSelect($asType);
                                        //$jArray[__LINE__][]=$g;
                    $i=0;
                    while($g!=false){
                                                //$jArray[__LINE__][]=$g;
                        if($i>50)break;$i++;
                        if($g['targetType']=='p'){
                            $next=$this->db->getRowData($this->general->table($pTbl),$nextQuery." and post_id='".$g['target']."'".$timeQuery);
                            if(empty($next)){
                                $this->db->delete($this->general->table(5),array('post_id'=>$g['target']));
                                $g=$this->nextAssignPostSelect($asType);
                            }
                            else{
                                $jArray['targetType']=$g['targetType'];
                                $jArray['target']=$g['target'];
                                break;
                            }
                        }
                        elseif($g['targetType']=='c'){
                            $next=$this->db->getRowData($this->general->table($cTbl),$nextQuery." and comment_id='".$g['target']."'".$timeQuery);
                            $jArray[__LINE__]=$next;
                            if(empty($next)){
                                $this->db->delete($this->general->table(36),array('comment_id'=>$g['target']));
                                $g=$this->nextAssignPostSelect($asType);
                            }
                            else{
                                //$jArray[__LINE__]=$next;
                                $jArray['targetType']=$g['targetType'];
                                $jArray['target']=$g['target'];
                                break;
                            }
                        }
                        else{break;}
                    }
                    //$jArray[__LINE__]=$next;

                }
                if($jArray['target']==''){
                    if($asType==ASSIGN_TYPE_HIGHBREED){
                        //$jArray[__LINE__]=__LINE__;
                        $abc=$this->makePostAssignUnionQuery($orderBy);
                        //$jArray[__LINE__]=$abc;
                        if(!empty($abc)){       
                            $jArray['targetType']=$abc['activity'];
                            $jArray['target']=$abc['id'];
                            if($abc['activity']=='p'){
                                $next=$this->db->get_rowData($this->general->table($pTbl),'post_id',$abc['id']);
                            }
                            else if($abc['activity']=='c'){
                                $next=$this->db->get_rowData($this->general->table($cTbl),'comment_id',$abc['id']);
                                //$jArray[__LINE__]=$next;
                            }
                        }   
                    }
                }
                if($jArray['target']!=''){
                    $mainCommentSenderID = $next['sender_id'];
                    $mainCommentCreated = $next['created_time'];   
                    if($jArray['targetType']=='c'){
                        $c=$next;
                        $next=$this->db->get_rowData($this->general->table($pTbl),'post_id',$c['post_id']);
                        //$jArray[__LINE__]=__LINE__;
                        if(empty($next)){
                            //$jArray[__LINE__]=__LINE__;
                            $next=$this->db->get_rowData($this->general->table($pTbl),'post_id_2',$c['post_id']);
                            //$jArray[__LINE__]=$next;
                        }
                        if(empty($next)){
                            //$jArray[__LINE__]=__LINE__;
                            $d=$this->newPostLoadFromFB($c['post_id'],$jArray);
                            if($d=='ne'){
                                $fb=$this->social->fbInit();
                                try {
                                    $rqUrl = '/'.$c['comment_id'].'/?fields=id,message';
                                    $response = $fb->get($rqUrl);
                                    if(isset($response->getDecodedBody()['id'])){}
                                } 
                                catch(Facebook\Exceptions\FacebookResponseException $e) {
                                    if($e->getCode()==100){
                                        $where=array('post_id'=>$c['post_id']);
                                        $this->db->delete($this->general->table($pTbl),$where);
                                        $this->db->delete($this->general->table($rpTbl),$where);
                                        $where=array('comment_id'=>$c['comment_id']);
                                        $this->db->delete($this->general->table($cTbl),$where);
                                        $this->db->delete($this->general->table($rcTbl),$where);
                                    }
                                }
                                catch(Facebook\Exceptions\FacebookSDKException $e) {}
                            }
                            $next=$this->db->get_rowData($this->general->table($pTbl),'post_id',$c['post_id']);
                            if(empty($next)){
                                $next=$this->db->get_rowData($this->general->table($pTbl),'post_id_2',$c['post_id']);
                                //$jArray[__LINE__]=$next;
                            }
                        }
                        //$jArray[__LINE__]=$next;
                    }
                }
            }
            //$jArray[__LINE__]=$next;



            if(isset($next)){
                //$jArray[__LINE__]=$next;
                if(!empty($next)){
                    //$jArray[__LINE__]=$jArray['targetType'];
                    if($jArray['targetType']=='p'){
                        //$jArray[__LINE__]=__LINE__;
                        //$jArray[__LINE__]=$next['message'];
                        if($next['message']==''||$next['link']!=''){
                            //$jArray[__LINE__]=__LINE__;
                            $fb=$this->social->fbInit();
                            $rqUrl = '/'.$next['post_id'].'/?fields=id,message,picture';
                            try {
                                $jArray[__LINE__]=__LINE__;
                                $response = $fb->get($rqUrl);
                                if(isset($response->getDecodedBody()['id'])){
                                    $data=array();
                                    $d=$response->getDecodedBody();
                                    if(isset($d['picture'])){
                                        //$jArray[__LINE__]=$d['picture'];
                                        if($d['picture']!=$next['link']){
                                            $data['link']=$d['picture'];
                                            $next['link']=$d['picture'];
                                        }
                                    }   
                                    if(isset($d['message'])){
                                        if($next['message']!=$d['message']){
                                            $data['message']=$d['message'];
                                            $next['message']=$d['message'];
                                        }   
                                    }
                                    if(!empty($data)){
                                        $where=array('post_id'=>$next['post_id']);
                                        $this->db->update($this->general->table(12),$data,$where);
                                        $this->db->update($this->general->table(64),$data,$where);
                                    }
                                }
                                else{
                                    $next=array();
                                }
                            } 
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
                                $jArray[__LINE__]=$e->getCode();
                                if($e->getCode()==100){
                                    $where=array('post_id'=>$next['post_id']);
                                    $data=array('replyed'=>4);
                                    $jArray[__LINE__]=$this->db->update($this->general->table($pTbl),$data,$where,'array');
                                    $jArray[__LINE__]=$this->db->update($this->general->table($rpTbl),$data,$where,'array');

                                    $next=array();
                                }
                                //SetMessage(4,'Graph returned an error: ' . $e->getCode().' '.$e->getMessage().' line '.__LINE__);
                                $error=__LINE__; 
                            }
                            catch(Facebook\Exceptions\FacebookSDKException $e) {
                                $jArray[__LINE__]=__LINE__;
                                $error=__LINE__; 
                            }

                        }
                    }
                    else{
                        $comment=$this->social->getCommentInfoById($jArray['target'],'w');
                        if($comment['message']==''||$comment['photo'!='']){
                            $fb=$this->social->fbInit();
                            try {
                                $rqUrl = '/'.$jArray['target'].'/?fields=id,attachment,message';
                                $response = $fb->get($rqUrl);
                                if(isset($response->getDecodedBody()['id'])){
                                    $data=array();
                                    $d=$response->getDecodedBody();
                                    if(isset($d['attachment'])){
                                        $photo=$d['attachment']['media']['image']['src'];
                                        if($photo!=$comment['photo']){
                                            $data['photo']=$photo;
                                        }
                                    }   
                                    if(isset($d['message'])){
                                        if($comment['message']!=$d['message']){
                                            $data['message']=$d['message'];
                                        }   
                                    }
                                    if(!empty($data)){
                                        $where=array('comment_id'=>$jArray['target']);
                                        $this->db->update($this->general->table(14),$data,$where);
                                        $this->db->update($this->general->table(65),$data,$where);
                                    }
                                }
                                else{
                                    $next=array();
                                }
                            } 
                            catch(Facebook\Exceptions\FacebookResponseException $e) {
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
            }
            if(isset($next)){
                //$jArray[__LINE__]=__LINE__;
                if(!empty($next)){
                    //$jArray[__LINE__]=__LINE__;
                    if($jArray['targetType']=='p'){
                        $cID=$next['post_id'];
                    }
                    else{
                        $cID=$jArray['target'];
                    }
                    $os=$this->db->get_rowData($this->general->table(57),'comment_id',$cID);
                    /*$makeAssign=0;
                    if(empty($os)){
                    $jArray[__LINE__]=__LINE__;
                    $data=array(
                    'uID'           => UID,
                    'comment_id'    => $cID,
                    'assignTime'    => TIME
                    );
                    $insert=$this->db->insert($this->general->table(44),$data);
                    if($insert){
                    $jArray[__LINE__]=__LINE__;
                    $makeAssign=1;
                    }
                    else{
                    $jArray[__LINE__]=__LINE__;
                    }
                    }
                    elseif($os['uID']==UID){
                    $makeAssign=1;
                    }*/
                    $makeAssign=$this->makeCommentPostAssigned($cID,'w');
                    if($makeAssign===true){
                        $data=array('assignTime'=>time(),'assignTo'=>UID);
                        if($jArray['targetType']=='p'){
                            $where=array('post_id'=>$jArray['target']);
                            $this->db->update($this->general->table($pTbl),$data,$where);
                            $this->db->update($this->general->table($rpTbl),$data,$where);
                        }
                        else{
                            $where=array('comment_id'=>$jArray['target']);
                            $this->db->update($this->general->table($cTbl),$data,$where);    
                            $this->db->update($this->general->table($rcTbl),$data,$where);    
                        }
                    }else{$next=array();}
                }
            }
            //$jArray[__LINE__]=__LINE__;
            if(isset($next)){
                //$jArray[__LINE__]=__LINE__;
                if(!empty($next)){
                    //$jArray[__LINE__]=__LINE__;
                    $jArray['status']=1;

                    if($jArray['targetType']=='p'){
                        //$jArray[__LINE__]=__LINE__;
                        $mainCommentSenderID = $next['sender_id'];
                        $mainCommentCreated = $next['created_time'];
                        //                        $jArray[__LINE__]=$next;
                        $names[$next['sender_id']]=$next['sender_id'];
                        $post_ids[$next['post_id']]=$next['post_id'];
                        $post_ids[$next['post_id_2']]=$next['post_id_2'];
                        $post_id=$next['post_id'];
                        $post_id_2=$next['post_id_2'];
                        $jArray['post']=array(
                            'post_id'       => $next['post_id'],
                            'message'       => $next['message'],
                            'type'          => $next['type'],
                            'link'          => $next['link'],
                            'permalink_url' => $next['permalink_url'],
                            'sender_id'     => $next['sender_id'],
                            'time'          => date('YmdHis',$next['created_time'])
                        );

                    }
                    else{
                        $post=$this->db->getRowData($this->general->table($pTbl),"where post_id = '".$next['post_id']."' or post_id_2='".$next['post_id']."'");
                        $post_id    = $post['post_id_2'];
                        $post_id_2  = $post['post_id'];
                        //$jArray[__LINE__]=$post;
                        $next=$post;
                        $names[$next['sender_id']]=$next['sender_id'];
                        $post_ids[$next['post_id']]=$next['post_id'];
                        $post_ids[$next['post_id_2']]=$next['post_id_2'];


                        $where=array('comment_id'=>$jArray['target']);
                        $this->db->update($this->general->table($cTbl),$data,$where);
                        $this->db->update($this->general->table($rcTbl),$data,$where);
                        //$jArray[__LINE__]=$next;
                        $jArray['post']=array(
                            'post_id'       => $next['post_id_2'],
                            'message'       => $next['message'],
                            'type'          => $next['type'],
                            'link'          => $next['link'],
                            'permalink_url' => $next['permalink_url'],
                            'sender_id'     => $next['sender_id'],
                            'time'          => date('YmdHis',$next['created_time'])
                        );
                    }
                    $jArray['target_sender_id']=$mainCommentSenderID;
                    $others=$this->db->selectAll($this->general->table($cTbl),"where post_id='".$post_id."' or post_id ='".$post_id_2."' order by created_time asc");
                    //$jArray[__LINE__]=$others;
                    if(!empty($others)){
                        //$jArray[__LINE__]=__LINE__;
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
                                'created_time'  => date('YmdHis',$next['created_time'])
                            );
                            $jArray['comments'][]=$comment; 
                        }
                    }
                    $lifetime=$this->db->selectAll($this->general->table($pTbl),"where created_time<".$mainCommentCreated." and sender_id='".$mainCommentSenderID."' order by created_time desc limit 15");
                    $lta=array();
                    if(!empty($lifetime)){
                        //$jArray[__LINE__]=__LINE__;
                        foreach($lifetime as $lt){
                            $post_ids[$lt['post_id']]=$lt['post_id'];
                            $life_time=array(
                                'comment_id'    => $lt['post_id'],
                                'post_id'       => $lt['post_id'],
                                'parent_id'     => $lt['post_id'],
                                'message'       => $lt['message'],
                                'sender_id'     => $lt['sender_id'],
                                'photo'         => $lt['link'],
                                'created_time'  => date('YmdHis',$lt['created_time'])
                            );
                            $lta[$lt['created_time']]=$life_time; 
                            //$jArray['life_time'][$lt['created_time']]=$life_time; 
                        }
                    }

                    $lifetime=$this->db->selectAll($this->general->table($cTbl),"where created_time<".$mainCommentCreated." and sender_id='".$mainCommentSenderID."' order by created_time desc limit 15");
                    if(!empty($lifetime)){
                        foreach($lifetime as $lt){
                            $post_ids[$lt['post_id']]=$lt['post_id'];
                            $life_time=array(
                                'comment_id'    => $lt['comment_id'],
                                'post_id'       => $lt['post_id'],
                                'parent_id'     => $lt['parent_id'],
                                'message'       => $lt['message'],
                                'sender_id'     => $lt['sender_id'],
                                'photo'         => $lt['photo'],
                                'created_time'  => date('YmdHis',$lt['created_time'])
                            );
                            $lta[$lt['created_time']]=$life_time; 
                            //$jArray['life_time'][$lt['created_time']]=$life_time; 
                        }
                    }
                    if(!empty($lta)){
                        $t=array_keys($lta);
                        array_multisort($lta,$t);
                        foreach($lta as $l){
                            $jArray['life_time'][]=$l;
                        }
                    }

                    if(!empty($post_ids)){   
                        //$jArray[__LINE__]=$post_ids;           
                        $jArray['post_ids']=$this->social->getPostPermalinkUrlBiIds($post_ids,'w');
                        foreach($jArray['post_ids'] as $k=>$l){
                            if($l==100){
                                $backtrace = debug_backtrace();
                                textFileWrite(json_encode($backtrace),'jsonresponse.txt');
                                if($k==$jArray['target']){$jArray['status']==0;}
                                //$jArray[__LINE__][]=$k;
                                //এখান থেকে শুধু পোস্ট ডিলিট হবে তাই টার্গেট টাইপ = p
                                //$this->social->removeComment($k,'w','p',false);
                            }
                        }
                    }
                    else{}
                    if(!empty($names)){
                        //$jArray[__LINE__]=__LINE__;
                        $senderName=$this->social->getNamesByUserId(array($jArray['target_sender_id']));
                        if(!empty($senderName)){
                            $jArray['target_sender_name']=$senderName[0]['name'];
                        }
                        $jArray['names']=$this->social->getNamesByUserId($names);
                    }


                }
            }
            if($makeAssign===true){
                $data=array('assignTime'=>time(),'assignTo'=>UID);
                if($jArray['targetType']=='p'){
                    $where=array('post_id'=>$jArray['target']);
                    $this->db->update($this->general->table($pTbl),$data,$where);
                    $this->db->update($this->general->table($rpTbl),$data,$where);
                }
                else{
                    $where=array('comment_id'=>$jArray['target']);
                    $this->db->update($this->general->table($cTbl),$data,$where);    
                    $this->db->update($this->general->table($rcTbl),$data,$where);    
                }


            }
        }
        function assignmentWallPostCommentData($from='',$to=''){
            $flowType=$this->social->getFlowType('w');
            if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
                $orderBy=" order by created_time asc";
            }
            else{
                $orderBy=" order by created_time desc";
            }
            $names=array();
            $comments=array();
            $query="
            SELECT p.post_id,p.message,p.created_time,p.sender_id,p.link,'p' AS activity
            FROM ".$this->general->table(12)." p
            WHERE p.replyed=0 AND p.assignTime=0 AND p.assignTo=0
            and p.post_id not in (select aw.post_id from ".$this->general->table(5)." aw)";
            if($from!=''&&$to!=''){
                $query.=" and p.created_time between ".$from." and ".$to;    
            }
            if($keyword!=''){$query.=" and p.message like '%".$keyword."%'";}
            //$query.=$orderBy;
            $query.=" UNION
            SELECT c.comment_id,c.message,c.created_time,c.sender_id,c.photo,'c' AS activity
            FROM ".$this->general->table(14)." c
            WHERE c.replyed=0 AND c.sender_id!='".PAGE_ID."' AND c.assignTime=0 AND c.assignTo=0
            and c.comment_id not in (select ac.comment_id from ".$this->general->table(36)." ac)";
            if($from!=''&&$to!=''){
                $query.=" and c.created_time between ".$from." and ".$to;    
            }
            if($keyword!=''){$query.=" and c.message like '%".$keyword."%'";}
            $query.=$orderBy." LIMIT 100 ";
            //echo $query;





            /*$query="
            SELECT
            p.post_id,p.sender_id,p.message,p.link,p.created_time
            FROM ".$this->general->table(12)." p
            WHERE 
            p.post_id not in
            (SELECT cd.post_id FROM ".$this->general->table(5)." cd)
            and p.assignTo=0 and p.replyed=0
            ".$orderBy." LIMIT ".$limit;*/
            $comm= $this->db->fetchQuery($query);
            foreach($comm as $c){
                $names[$c['sender_id']]=$c['sender_id'];
                $assignment_comment=array(
                    'comment_id'    => $c['post_id'],
                    'message'       => $c['message'],
                    'sender_id'     => $c['sender_id'],
                    'activity'      => $c['activity'],
                    'photo'         => $c['link'],
                    'created_time'  => date('YmdHis',$c['created_time'])
                );
                $comments[]=$assignment_comment; 
            }
            if(!empty($names)){
                $names=$this->social->getNamesByUserId($names);
            }
            $rt=array(
                'comments'=>$comments,
                'names'=>$names
            );
            return $rt;
        }
        function senderNameUpdate($id,$name){
            $o=$this->db->get_rowData($this->general->table(33),'id',$id);
            if(empty($o)){
                $data=array(
                    'id'=>$id,
                    'name'=>$name,
                );
                $this->db->insert($this->general->table(33),$data);
                textFileWrite(__LINE__." NO Change \n".json_encode($data),basename(__FILE__).'.text');
            }
            else if($name!=$o['name']){
                $data=array('name'=>$name);
                $where=array('id'=>$id);
                $this->db->update($this->general->table(33),$data,$where);
                textFileWrite(__LINE__.' '.json_encode($data).' '.$o['name'],basename(__FILE__).'.text');
            }
        }
        function makeCommentPostAssigned($comment_id,$type){
            if($type=='c'){
                $tbl=44;
            }
            else if($type=='w'){
                $tbl=57;
            }
            else{return false;}
            $makeAssign=false;
            $os=$this->db->get_rowData($this->general->table($tbl),'comment_id',$comment_id);
            if(empty($os)){
                //$jArray[__LINE__]=__LINE__;
                $data=array(
                    'uID'           => UID,
                    'comment_id'    => $comment_id,
                    'assignTime'    => TIME
                );
                $insert=$this->db->insert($this->general->table($tbl),$data);
                if($insert){
                    $jArray[__LINE__]=__LINE__;
                    $makeAssign=true;
                }else{$jArray[__LINE__]=__LINE__;}
            }
            elseif($os['uID']==UID){$makeAssign=true;}
            return $makeAssign;
        }
        function cronLog($fileName){
            $data=array(
                'clPage'        => $fileName,
                'clTimeStart'   => $this->general->make_date(TIME,'time'),
                'clTimeEnd'     => $this->general->make_date(time(),'time'),
                'clRunSecond'   => $this->general->timestampDiffInArray(TIME,time(),true)
            );
            $this->db->insert($this->general->table(59),$data);
        }
    }
?>
