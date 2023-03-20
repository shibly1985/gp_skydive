<?php

    include("class/class.db.php");
    include("class/class.general.php");
    include("class/class.social.php");
    include("class/class.report.php");
    include("class/messages.php");
    $db = new DB();
    $general    = new General();
    $social     = new social();
    $sReport    = new socialReport();
    include("init.php");

    //echo phpinfo();exit;
    if(isset($_GET['abdus_salam'])){
        $created_time=strtotime(date('d-m-Y h:i:s A'));
        if(isset($_POST['date'])){
            echo $_POST['date'];echo'<br>'; 
            $created_time=strtotime($_POST['date']);
        }
    ?>
    <form action="" method="POST">
        <input type="text" value="<?php echo date('d-m-Y h:i:s A');?>" name="date">
        <input type="submit"><br>dd-mm-yyyy hh:mm:ss<br>

    </form>

    যখন ঢুকেছে তখনি ডান হবে <br>
    <?php echo $general->make_date($created_time,'time');?> এর আগের 
    <br><b>কমেন্ট</b><br>
    update <?php echo $general->table(13);?>  set isDone=1, replyed=1, assignTo=44, replyBy=44,assignTime=created_time+5,replyTime=created_time+10 where replyed=0 and created_time<?php echo '<='.$created_time?>;<br>
    update <?php echo $general->table(63);?>  set isDone=1, replyed=1, assignTo=44, replyBy=44,assignTime=created_time+5,replyTime=created_time+10 where replyed=0 and created_time<?php echo '<='.$created_time?>;<br>
    <b>ওয়াল</b><br>
    update <?php echo $general->table(12);?>  set isDone=1, replyed=1, replyBy=44,replyTime=created_time+5 where replyed=0 and created_time<<?php echo $created_time;?>;<br>
    update <?php echo $general->table(64);?>  set isDone=1, replyed=1, replyBy=44,replyTime=created_time+5 where replyed=0 and created_time<<?php echo $created_time;?>;<br>

    update <?php echo $general->table(14);?> set isDone=1, replyed=1, replyBy=44,replyTime=created_time+5 where replyed=0 and created_time<<?php echo $created_time;?>;<br>
    update <?php echo $general->table(65);?> set isDone=1, replyed=1, replyBy=44,replyTime=created_time+5 where replyed=0 and created_time<<?php echo $created_time;?>;<br>
    <b>মেসেজ</b><br>
    update <?php echo $general->table(9);?> set replyed=1,assignTo=44,assignTime=sendTime+5,replyTime=sendTime+10,replyBy=44,isDone=1 where sendTime<<?php echo $created_time;?> and replyed=0<br>
    update <?php echo $general->table(66);?> set replyed=1,assignTo=44,assignTime=sendTime+5,replyTime=sendTime+10,replyBy=44,isDone=1 where sendTime<<?php echo $created_time;?> and replyed=0<br>
    update <?php echo $general->table(16);?> set replyed=1,assignTo=44,assignTime=sendTime+5,replyTime=sendTime+10,replyBy=44,isDone=1 where sendTime<<?php echo $created_time;?> and replyed=0<br>
    update <?php echo $general->table(67);?> set replyed=1,assignTo=44,assignTime=sendTime+5,replyTime=sendTime+10,replyBy=44,isDone=1 where sendTime<<?php echo $created_time;?> and replyed=0<br>
    delete from report_cach;<br>

    <?php 
    }
    else if(isset($_GET['db'])){
        $a=scandir('../../db_backup/');
        if(PROJECT=='gp'){
            $b=copy('../../db_backup/sslbd_db_2.sql','sslbd_db_2.sql');
            $a=scandir('../gp/');echo'<br>'; 
        }
        elseif(PROJECT=='tmm'){
            $b=copy('../../db_backup/tmm.sql','tmm.sql');                    
            $a=scandir('../tmm/');echo'<br>'; 
        }
        elseif(PROJECT=='gpmusic'){
            $b=copy('../../db_backup/gpmusic.sql','gpmusic.sql');                    
            $a=scandir('../tmm/');echo'<br>'; 
        }
        if(isset($b)){
            var_dump($b);echo'<br>';
            print_r($a);
            echo '<br>';
            print_r($a);
        }
        else{
            echo 'This project not for this';
        }
    }
    elseif(isset($_GET['debug'])){
        $from=YESTERDAY_TIME;
        $to=TODAY_TIME;
        $to=strtotime('-1 second',$to);
        $query="SELECT  count(comment_id) as t FROM comments_status  WHERE sender_id!=135237519825044 and created_time between ".$from." AND ".$to."";
        $a=$db->fetchQuery($query,'d');
        $general->printArray($a);
        $query="SELECT comment_id,created_time,assignTime,replyTime,isDone,replyed,replyBy FROM comments_status  WHERE sender_id!=135237519825044 and created_time between ".$from." AND ".$to."";
        $a=$db->fetchQuery($query,'d');
    ?>
    <table border="1">
        <?php
            $i=1;
            foreach($a as $b){
                if($i==1)$general->printArray($b);
                $d=$db->get_rowData($general->table(13),'target_c_id',$b['comment_id']);

            ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $b['comment_id'];?></td>
                <td><?php echo $b['isDone'];?></td>
                <td><?php echo $b['replyed'];?></td>
                <td><?php echo $b['replyBy'];?></td>
                <td><?php echo $general->make_date($b['created_time'],'time');?></td>
                <td><?php echo $general->make_date($b['assignTime'],'time');?></td>
                <td><?php echo $general->make_date($b['replyTime'],'time');?></td>
            </tr>
            <?php
            }
        ?>
    </table>
    <?php
    }
    elseif(isset($_GET['debuga'])){
        $from='1489946400';
        $to='1490032800';
        echo $general->make_date($from,'time');echo'<br>'; 
        echo $general->make_date($to,'time');echo'<br>'; 
        $query="SELECT  count(comment_id) as t FROM comments_wall  WHERE replyBy!=0 and sender_id!=135237519825044 and replyTime  between ".$from." AND ".$to."";
        $a=$db->fetchQuery($query,'d');
        $general->printArray($a);
        $query="SELECT  count(comment_id) as t FROM comments_wall  WHERE replyBy!=0 and sender_id!=135237519825044 and replyTime  between ".$from." AND ".$to."";
        $a=$db->fetchQuery($query,'d');
        $general->printArray($a);
        $query="SELECT comment_id,created_time,assignTime,replyTime,isDone,replyed,replyBy FROM comments_wall  WHERE replyBy!=0 and sender_id!=135237519825044 and replyTime between ".$from." AND ".$to." order by replyTime asc";
        $a=$db->fetchQuery($query,'d');
    ?>
    <table border="1">
        <?php
            $i=1;
            foreach($a as $b){
                if($i==1)$general->printArray($b);
                $d=$db->get_rowData($general->table(14),'target_c_id',$b['comment_id']);
                //$d=array();
                if(empty($d))$d=$b;
            ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $b['comment_id'];?></td>
                <td><?php echo $b['isDone'];?></td>
                <td><?php echo $b['replyed'];?></td>
                <td><?php echo $b['replyBy'];?></td>
                <td><?php echo $general->make_date($d['created_time'],'time');?></td>
                <td><?php echo $general->make_date($b['assignTime'],'time');?></td>
                <td><?php echo $general->make_date($d['replyTime'],'time');?></td>
            </tr>
            <?php
            }
        ?>
    </table>
    <?php
    }
    elseif(isset($_GET['flushcach'])){
        $query="delete from ".$general->table(40);
        $db->runQuery($query,'d');
    }
    else if(isset($_GET['a'])){
        //echo $a="delete from ".$general->table(37)." where active<".strtotime('-1 month');
        //echo'<br>'; 
        //$db->runQuery($a,'d');
        echo $st=strtotime('13-02-2017 12:59');echo'<br>'; 
        echo $general->make_date($st,'time');echo'<br>'; 
        echo $en=strtotime('13-02-2017 14:05');echo'<br>'; 
        echo $general->make_date($en,'time');echo'<br>'; 
        $query="
        select * from ".$general->table(37)." where uID=89 and active between ".$st." and ".$en;
        $activity=$db->fetchQuery($query,'d');
        echo count($activity);echo'<br>'; 
        //$general->printArray($activity);
        if(!empty($activity)){
        ?>
        <table>
            <?php
                $l=$activity[0]['active'];
                foreach($activity as $a){
                    $s=$general->timestampDiffInArray($a['active'],$l,true);
                ?>
                <tr>
                    <td><?php echo $a['active'];?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php echo $general->make_date($a['active'],'time');?></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php echo $s;?></td>

                </tr>
                <?php

                    $l=$a['active'];
                }
            ?>
        </table>
        <?php
        }

        $a=$db->fetchQuery("select * from ".$general->table(37)." where uID=89 and active>=".$st." and active<=".$en);
        if(!empty($a)){
            echo $general->make_date($st,'time');echo' to '; 
            echo $general->make_date($en,'time');echo'<br>'; 
            $activity=array();
            foreach($a as $ac){
                if(isset($activity[$ac['uID']][$st])){
                    $o=$activity[$ac['uID']][$st];
                    $n2Min=strtotime('+5 minute',$o['lsatAct']);
                    if($ac['active'] < $n2Min){
                        $o['totalAct']+=$general->timestampDiffInArray($ac['active'],$o['lsatAct'],true);
                        $o['lsatAct']=$ac['active'];
                    }
                    else{
                        $o['totalAct']+=300;
                        $o['lsatAct']=$ac['active'];
                    }

                    if($o['lsatServ']==0){
                        $o['lsatServ']=$ac['service'];
                    }else{
                        $n2Min=strtotime('+5 minute',$o['lsatServ']);
                        if($ac['service']<$n2Min){
                            if($ac['service']!=0){
                                $o['totalServ']+=$general->timestampDiffInArray($ac['service'],$o['lsatServ'],true);
                                $o['lsatServ']=$ac['service'];
                            }
                        }
                        else{
                            $o['totalServ']+=300;
                            $o['lsatServ']=$ac['service'];
                        }
                    }

                    $o['hit']+=1;
                    $activity[$ac['uID']][$st]=$o;
                }
                else{
                    $activity[$ac['uID']][$st]=array(
                        'uID'           => $ac['uID'],
                        'serviceStart'  => $st,
                        'lsatServ'      => $ac['service'],
                        'totalServ'     => 0,
                        'lsatAct'       => $ac['active'],
                        'totalAct'      => 0,
                        'hit'           => 1
                    );
                }
            }
            //$general->printArray($a);   
            if(!empty($activity)){
                $general->printArray($activity);
                foreach($activity as $d){
                    foreach($d as $dd){
                        $data=array(
                            'serviceStart'  => $dd['serviceStart'],
                            'uID'           => $dd['uID'],
                            'hit'           => $dd['hit'],
                            'service'       => $dd['totalServ'],
                            'active'        => $dd['totalAct']
                        );
                        if($dd['hit']==1){
                            echo 'hl';
                            $data['service']=120;
                            $data['active']=120;
                        }
                        $general->printArray($data);
                        //echo $data['uID'].'-'. $general->make_date($data['serviceStart'],'time').' -act'.$dd['totalAct'];echo'<br>'; 
                        //$insert=$db->insert($general->table(38),$data);
                    }
                }
            }

        }

    }
    else if(isset($_GET['alp'])){
        $fb=$social->fbInit();
        $rqUrl = '/me/feed';
        $response = $fb->get($rqUrl);
        $d=$response->getDecodedBody();
        $general->printArray($d['data']);
        echo "<br><a href='".$d['paging']['next']."'>Next</a>";
    }
    else if(isset($_GET['pd'])){
        $fb=$social->fbInit();
        try {
            //echo $post_id;
            $rqUrl = '/1880129938669118_1880292575319521/?fields=id,admin_creator,application,call_to_action,caption,created_time,description,feed_targeting,from,icon,instagram_eligibility,is_hidden,is_instagram_eligible,is_published,link,message,message_tags,name,object_id,parent_id,permalink_url,picture,place,privacy,properties,shares,source,status_type,story,story_tags,targeting,to,type,updated_time,with_tags';
            $rqUrl = '/1880129938669118_1880292575319521/?fields=id,created_time,from,message,can_hide';
            $rqUrl = '/135237519825044_1995625817119529/';
            //$rqUrl = '/243329012730796_1100000503456207/?fields=message,picture';
            //$rqUrl = '/603742072992573_1568834866483284/?fields=id,created_time,from,message';
            //$rqUrl = '/me/feed';
            echo $rqUrl;echo'<br>';
            $response = $fb->get($rqUrl);
            $general->printArray($response);
            if(isset($response->getDecodedBody()['id'])){
                //$this->general->printArray($response->getDecodedBody());
                $d=$response->getDecodedBody();
                /*$v=array(
                'value'=>array(
                'post_id'       => $d['id'],
                'created_time'  => strtotime($d['created_time']),
                'created_time2'  => $general->make_date(strtotime($d['created_time']),'time'),
                'item'          => $d['type'],
                'permalink_url' => $d['permalink_url'],
                'sender_id'     => $d['from']['id'],
                'message'       => ''
                )
                );
                if(isset($d['attachment'])){$v['value']['photo']=$d['attachment']['media']['image']['src'];}
                if(isset($d['message'])){$v['value']['message']=$d['message'];}
                if(isset($d['picture'])){$v['value']['picture']=$d['picture'];}*/
                $general->printArray($d);
                //$general->printArray($v);
            }
            else{
                $jArray['e']='Invalid post';
            }
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            $error=__LINE__; 
            echo 'Graph returned an error: a<br>'.$e->getCode().'<br>a ' . $e->getMessage();
        }
        catch(Facebook\Exceptions\FacebookSDKException $e) {
            $error=__LINE__; 
            echo  'Facebook SDK returned an error: ' . $e->getMessage();
        }
        exit();
        $a=date('01-m-Y',TIME);
        echo $a;echo'<br>';
        $b = strtotime(date('t-m-Y',TIME));
        echo $b;echo'<br>';
        echo date('t',$b);echo'<br>';
        echo $general->make_date($b,'time');echo'<br>';   

    }
    else if(isset($_GET['md'])){
        $fb=$social->fbInit();
        try {
            //echo $post_id;
            //echo PROJECT;echo'<br>'; 
            $mid='mid.$cAAJ_7eRrOBhi1xPv61cpc6PfE3TV';
            $rqUrl = '/m_'.$mid.'/?fields=message,attachments,created_time,to,from,shares{link}';
            //echo $rqUrl;echo'<br>';
            $response = $fb->get($rqUrl);
            $d=$response->getDecodedBody();
            $general->printArray($d);
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e) {
            $error=__LINE__; 
            echo 'Graph returned an error: a<br>'.$e->getCode().'<br>a ' . $e->getMessage();
        }
        catch(Facebook\Exceptions\FacebookSDKException $e) {
            $error=__LINE__; 
            echo  'Facebook SDK returned an error: ' . $e->getMessage();
        }
        exit();
        $a=date('01-m-Y',TIME);
        echo $a;echo'<br>';
        $b = strtotime(date('t-m-Y',TIME));
        echo $b;echo'<br>';
        echo date('t',$b);echo'<br>';
        echo $general->make_date($b,'time');echo'<br>';   


        if(isset($_GET['p'])){
            $fb = $social->fbInit();
            // Send the request to Graph
            try {
                $response = $fb->get('/'.PAGE_ID.'/feed?fields=message,full_picture,created_time,from&since='.strtotime('-2 days').'&limit=2');
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            $cids=array();
            //                $general->printArray($response->getDecodedBody());exit;
            $datas=$db->fetchQuery("select post_id from temp_wall_post");
            $general->arrayIndexChange($datas,'post_id');

            foreach($response->getDecodedBody()['data'] as $item) {
                if($item['from']['id']!=PAGE_ID){
                    if(!array_key_exists($item['id'],$datas)){
                        $data=array(
                            'post_id'   => $item['id'],
                            'created_time'=> strtotime($item['created_time'])
                        );
                        $insert=$db->insert('temp_wall_post',$data); 
                        /*echo $general->make_date($data['created_time'],'time');echo'<br>'; 
                        echo $item['from']['id'];echo' - s<br>'; 
                        echo PAGE_ID;echo' - p<br>'; 
                        echo  $item['id'];echo'<br>';     
                        echo  $item['created_time'];echo'<br>';     
                        echo  $item['message'];echo'<br>'; */    
                    }
                }
            } 
            if(isset($response->getDecodedBody()['paging']['next'])){
                $response=file_get_contents($response->getDecodedBody()['paging']['next']);
                $response=json_decode($response);
                if(property_exists($response,'data')){
                    //$general->printArray($response->data); 
                    foreach($response->data as $d){
                        //$general->printArray($d);
                        if($d->from->id!=PAGE_ID){
                            if(!array_key_exists($d->id,$datas)){
                                $data=array(
                                    'post_id'   => $d->id,
                                    'created_time'=> strtotime($d->created_time)
                                );
                                $insert=$db->insert('temp_wall_post',$data); 
                            }
                        }
                    }
                }
                $general->printArray($response->paging);
                $p=$response->paging;
                while(property_exists($p,'next')){
                    $response=file_get_contents($response->paging->next);
                    $response=json_decode($response);
                    if(property_exists($response,'paging')){
                        $p=$response->paging;
                    }
                    else{
                        $p=$response;
                    }
                    if(property_exists($response,'data')){
                        //$general->printArray($response->data); 
                        foreach($response->data as $d){
                            //$general->printArray($d);
                            if($d->from->id!=PAGE_ID){
                                if(!array_key_exists($d->id,$datas)){
                                    $data=array(
                                        'post_id'   => $d->id,
                                        'created_time'=> strtotime($d->created_time)
                                    );
                                    $insert=$db->insert('temp_wall_post',$data); 
                                }
                            }
                        }
                    }
                }
            } 
        }
        else if(isset($_GET['c'])){
            $datas=$db->fetchQuery("select post_id from temp_wall_post where commentDone=0 limit 2");
            //$general->arrayIndexChange($datas,'post_id');
            $fb=$social->fbInit();
            foreach($datas as $p){
                $response = $fb->get('/'.$p['post_id'].'/comments?fields=id,created_time,from,parent_id');
                $response->getDecodedBody()['data'];
                foreach($response->getDecodedBody()['data'] as $d){
                    if(isset($d['parent_id'])){
                        $pr=$d['parent_id'];
                    }
                    else{
                        $pr=$p['post_id'];
                    }
                    $data=array(
                        'comment_id'=>$d['id'],
                        'post_id'=>$p['post_id'],
                        'from'=>$d['from']['id'],
                        'parent_id'=>$pr,
                        'created_at'=>strtotime($d['created_time'])
                    );
                    $insert=$db->insert('temp_wall_comment',$data); 
                    $general->printArray($data);
                }
                // $general->printArray($response->getDecodedBody());

                while(isset($response->getDecodedBody()['paging']['next'])){
                    $response = $fb->get('/'.$p['post_id'].'/comments?fields=id,created_time,from,parent_id&after='.$response->getDecodedBody()['paging']['cursors']['after']);
                    $response->getDecodedBody()['data'];
                    foreach($response->getDecodedBody()['data'] as $d){
                        if(isset($d['parent_id'])){
                            $pr=$d['parent_id'];
                        }
                        else{
                            $pr=$p['post_id'];
                        }
                        $data=array(
                            'comment_id'=>$d['id'],
                            'post_id'=>$p['post_id'],
                            'from'=>$d['from']['id'],
                            'parent_id'=>$pr,
                            'created_at'=>strtotime($d['created_time'])
                        );
                        $insert=$db->insert('temp_wall_comment',$data); 
                    }
                }
                $data=array('commentDone'=>1);
                $where=array('post_id'=>$p['post_id']);
                $update=$db->update('temp_wall_post',$data,$where); 
            }
        }
    }
    else if(isset($_GET['ada'])){
        $from=1489773600;
        $to=1489859999;
        echo $query="SELECT  * FROM post_wall  WHERE replyBy!=0 and replyTime between ".$from." AND ".$to." order by created_time asc";
        $all_data=$db->fetchQuery($query);
        foreach($all_data as $a){
            echo $a['post_id'].' - '. $general->make_date($a['created_time'],'time').' - '.$general->make_date($a['replyTime'],'time');echo'<br>'; 
        }

    }
    else if(isset($_GET['hide'])){
        if(isset($_POST['id'])){
            echo $_POST['id'];echo'<br>'; 
            $comment_id=$_POST['id'];
            echo $comment_id.'<br><br>';
            $fb=$social->fbInit();
            try {
                //            $hide = $fb->delete('/'.$comment_id);
                $rqUrl='/'.$comment_id.'?is_hidden=true';
                echo $rqUrl;echo '<br>'; 
                $hide = $fb->post($rqUrl);
                if(isset($hide->getDecodedBody()['success'])){
                    echo'Hide Done';
                }
                else{
                    echo'Hide Failed';
                }
                $general->printArray($hide->getDecodedBody());
                $general->printArray($hide);
            }
            catch(Facebook\Exceptions\FacebookResponseException $e) {
                $status=false;
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $comment_id."\n ". $e->getCode().' description '.$e->getMessage();
                echo $er;
                //textFileWrite($er,'fb_error.txt');
                //SetMessage(4,'Graph returned an error: ' . $e->getCode().' description '.$e->getMessage().' line '.__LINE__);

            } 
            catch(Facebook\Exceptions\FacebookSDKException $e) {
                $status=false;
                $er='['.date('d/m/Y h:i:s').']'. __FILE__.'  Line '.__LINE__.":\n " . $comment_id."\n ". $e->getCode().' description '.$e->getMessage();
                echo $er;
                //textFileWrite($er,'fb_error.txt');
                //SetMessage(4,'Facebook SDK returned an error: ' . $e->getMessage().' line '.__LINE__);
            }
        }
    ?>
    <form action="" method="POST">
        135237519825044_POSTID<br>
        OR<br>
        POSTID_COMMENTID<br><br>
        <input type="text" value="" name="id">
        <input type="submit" value="Hide">

    </form>
    <?php
    }
    else{

        //echo strtotime('18-09-2017 03:01:22 PM');echo'<br>'; 
        echo $a=strtotime('+15 Minute');echo '   ';echo$general->make_date($a,'time');echo'<br>';  
        echo $a='1516538926';echo '   ';echo$general->make_date($a,'time');echo'<br>';  
        echo $a='1506145534';echo '   ';echo$general->make_date($a,'time');echo'<br>';  
        echo $a='1505822401';echo '   ';echo$general->make_date($a,'time');echo'<br>';  

        echo $a='1505796306';echo '   ';echo$general->make_date($a,'time');echo'<br>';  
        echo'<br>';echo'<br>';echo '   ';echo'<br>';  

}