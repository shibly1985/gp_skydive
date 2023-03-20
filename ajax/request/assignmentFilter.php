<?php
    $q=array();
    if(isset($_GET['quantity'])){
        $limit =  $_GET['quantity'];
    }
    else{
        $limit =  50; 
    }
    $posType = $_GET['type'];
    if($posType==0){
        $type='c';
    }
    else{
        $type='w';
    }
    if(isset($_GET['dateFrom'])){
        $from = strtotime($_GET['dateFrom']);
        $to   = strtotime($_GET['dateTo']);
        $q[]  = "c.created_time BETWEEN ".$from." and ".$to;
    }
    if(isset($_GET['flowType'])){
        $flowType = $_GET['flowType'];
        if($flowType!=WALL_POST_COMMENT_FLOW_FIFO&&$flowType==WALL_POST_COMMENT_FLOW_LIFO){
            $flowType= $social->getFlowType($type);
        }
    }
    if(!isset($flowType)){
        $flowType= $social->getFlowType($type);
    }
        if($flowType==WALL_POST_COMMENT_FLOW_FIFO){
            $orderBy=" order by c.created_time asc";
        }
        else if($flowType==WALL_POST_COMMENT_FLOW_LIFO){
            $orderBy=" order by c.created_time desc";
        }
    if(isset($_GET['keyword'])){
        $keyword=$_GET['keyword'];
        if($keyword!=''){
            $q[]="c.message like '%".$keyword."%'";
        }
    }
    // $query = $db->selectAll($general->table(13))
    if($type == 'c'){
        $q[]="c.comment_id not in (SELECT c.comment_id FROM ".$general->table(13)." c inner join ".$general->table(10)." a on(a.comment_id=c.comment_id) )";
        $q[]="assignTo=0";
        $q[]="c.replyed=0";
        $sq="where ".implode(' and ',$q);
        $query="SELECT
        c.comment_id,c.sender_id,c.message,c.photo,c.created_time
        FROM ".$general->table(13)." c
        left join ".$general->table(10)." a on(a.comment_id=c.comment_id) 
        ".$sq." ".$orderBy." LIMIT ".$limit;
    }
    else if($type == 'w'){
        $q[]="c.comment_id not in
        (SELECT c.comment_id FROM ".$general->table(14)." c 
        inner join ".$general->table(5)." a on(a.post_id=c.comment_id) )";
        $q[]="assignTo=0";
        $q[]="c.replyed=0";
        $sq="where ".implode(' and ',$q);
        $query="SELECT
        c.comment_id,c.sender_id,c.message,c.photo,c.created_time
        FROM ".$general->table(14)." c
        left join ".$general->table(5)." a on(a.post_id=c.comment_id) 
        ".$sq." ".$orderBy." LIMIT ".$limit;
    }
    //echo $query;
    $comm= $db->fetchQuery($query);
    if($comm){
        $jArray=array('status'=>1);
    }

    $names=array();
    foreach($comm as $c){
        $names[$c['sender_id']]=$c['sender_id'];
        $assignment_comment=array(
            'comment_id'    => $c['comment_id'],
            'message'       => $c['message'],
            'sender_id'     => $c['sender_id'],
            'photo'         => $c['photo'],
            'created_time'  => date('YmdHis',$c['created_time'])
        );
        $jArray['comments'][]=$assignment_comment; 
    }
    if(!empty($names)){
        $jArray['names']=$social->getNamesByUserId($names);
    }
    $general->jsonHeader($jArray);
?>