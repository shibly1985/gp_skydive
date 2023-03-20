<?php
    include_once("../class/class.db.php");
    include_once("../class/class.general.php");
    include_once("../class/class.social.php");
    include_once("../class/class.social2.php");
    include_once("../class/messages.php"); 
    $db     = new DB();
    $general= new General();
    $social = new social();
    include("../init.php");
    $a=$db->fetchQuery("select max(reportTime) as m from ".$general->table(51));
    $lastSync=$a[0]['m'];
    if(empty($lastSync)){$lastSync=0;} 
    if($lastSync==0){
        $st='17-11-2016 10:00:00 AM';
        $st=strtotime($st); 

    }
    else{
        $st=date('d-m-Y h:00:00 A',$lastSync);
        $st=strtotime($st); 
        $st=strtotime('+1 hour',$st); 
    }
    $next1Hour=strtotime('+1 hour',$st);
    $totalLoop=0;
    while($next1Hour<TIME){
        $activity=array();
        $nextStart=$st;
        $from=$st;
        $to=$next1Hour;
        $to=strtotime('-1 second',$to);
        $data=array();
        echo $general->make_date($from,'time').' - - '. $general->make_date($to,'time');echo'<br>'; 
        $a=$db->fetchQuery("select count(post_id) as t from ".$general->table(4)." where created_time between ".$from." and ".$to);
        if($a[0]['t']>0){$data['cAdminPost']=$a[0]['t'];}
        $a=$db->fetchQuery("select count(comment_id) as t from ".$general->table(13)." where created_time between ".$from." and ".$to." and sender_id!=".PAGE_ID);
        if($a[0]['t']>0){$data['cUserComment']=$a[0]['t'];}
        $a=$db->fetchQuery("select count(comment_id) as t from ".$general->table(13)." where created_time between ".$from." and ".$to." and sender_id=".PAGE_ID);
        if($a[0]['t']>0){$data['cAdminComment']=$a[0]['t'];}
        $a=$db->selectAll($general->table(13),'WHERE isDone=1 and  created_time between '.$from. ' AND '.$to,'count(comment_id) as t');
        if($a[0]['t']>0){$data['cAdminDone']=$a[0]['t'];}
        $a = $db->selectAll($general->table(41),'WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to,'count(scqID) as t');
        if($a[0]['t']>0){$data['cAdminQueue']=$a[0]['t'];}
        $a  = $db->selectAll($general->table(26),' WHERE remove_time between '.$from. ' AND '.$to,' count(comment_id) as t');
        if($a[0]['t']>0){$data['cAdminRemove']=$a[0]['t'];}
        
        $a=$db->selectAll($general->table(12),' WHERE created_time between '.$from. ' AND '.$to,' count(post_id) as t');
        if($a[0]['t']>0){$data['wUserPost']=$a[0]['t'];}
        $a=$db->selectAll($general->table(14),' WHERE created_time between '.$from. ' AND '.$to." and sender_id!=".PAGE_ID,' count(comment_id) as t');
        if($a[0]['t']>0){$data['wUserComment']=$a[0]['t'];}
        $a=$db->selectAll($general->table(14),' WHERE created_time between '.$from. ' AND '.$to." and sender_id=".PAGE_ID,' count(comment_id) as t');
        if($a[0]['t']>0){$data['wAdminComment']=$a[0]['t'];}
        $a=$db->selectAll($general->table(14),'WHERE isDone=1 and created_time between '.$from. ' AND '.$to,'count(created_time) as t');
        if($a[0]['t']>0){$data['wAdminDone']=$a[0]['t'];}
        $a  = $db->selectAll($general->table(25),' WHERE remove_time between '.$from. ' AND '.$to,' count(remove_time) as t');
        if($a[0]['t']>0){$data['wAdminRemove']=$a[0]['t'];}
        $a = $db->selectAll($general->table(42),'WHERE sendSuccess=0 and replyTime between '.$from.' and '.$to,'count(replyTime) as t');
        if($a[0]['t']>0){$data['wAdminQueue']=$a[0]['t'];}
        //$general->printArray($a);
        //$general->printArray($data);
        /*if(!empty($activity)){
        //$general->printArray($activity);
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
        $data['service']=120;
        $data['active']=120;
        }
        //echo $data['uID'].'-'. $general->make_date($data['serviceStart'],'time').' -act'.$dd['totalAct'];echo'<br>'; 
        $insert=$db->insert($general->table(38),$data);
        }
        }
        }*/
        $totalLoop++;
        if(!empty($data)){
            $data['reportTime']=$from;
            $insert=$db->insert($general->table(51),$data,'','d');
        }
        if($totalLoop>48&&!empty($data)){
            break;
        }
        $st=$next1Hour;
        $next1Hour=strtotime('+1 hour',$next1Hour);
    }

?>
