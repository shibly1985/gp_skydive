<?php
//sleep(1);
    $reportTable=$db->settingsValue('commentReportTable');
    $wallCommentReportTable=$db->settingsValue('commentWallReportTable');
    $uID=intval($_POST['loadResponseReportByUserid']);
    $from=$_POST['from'];
    $to=$_POST['to'];
    $expTime=strtotime('+ 1 hour');    
    if($to<strtotime('-2 hour')){
        $expTime=strtotime('+ 6 month');    
    }
    $cacheKey='operator_response_'.$uID.'_'.$from.'_'.$to;
    $c=$db->reportCacheGet($cacheKey);
    $c=false;
    if($c==true&&!isset($_GET['flash'])){
        $data=json_decode($c,true);
        $jArray['status']=1;
        $jArray['data']=$data;
    }
    else{
        $u=$db->userInfoByID($uID);
        $jArray['status']=1;
        if(!empty($u)){
            $query="
            select * from ".$general->table(18)." where (ulsStartTime between ".$from." and ".$to." or ulsLastActivity between ".$from." and ".$to.") and uID=".$uID." limit 1";
            $active=$db->fetchQuery($query);
            if(!empty($active)){
                $total_replyed_status = $db->selectAll($general->table($reportTable),'where sender_id='.PAGE_ID.' and replyBy='.$u['uID'].' and replyTime between '.$from.' and '.$to,'count(comment_id) as total','array',$jArray);
                //$total_replyed_status = $db->selectAll($general->table($reportTable),'where sender_id!='.PAGE_ID.' and replyBy='.$u['uID'].' and replyTime between '.$from.' and '.$to.' ','count(comment_id) as total');
                $total_reply=$total_replyed_status[0]['total'];
                $total_like_status = $db->selectAll($general->table(27),'where uID='.$u['uID'].' and likeTime between '.$from.' and '.$to);
                $total_done_status = $db->selectAll($general->table($reportTable),'where replyBy='.$u['uID'].' and isDone=1 and replyTime between '.$from.' and '.$to,'count(comment_id) as total'); 
                $total_done_comment = $total_done_status[0]['total'];
                $total_hide_status = $db->selectAll($general->table(31),'where uID='.$u['uID'].' and hideTime between '.$from.' and '.$to,'count(comment_id) as total');   
                $total_delete_status = $db->selectAll($general->table(26),'where uID='.$u['uID'].' and remove_time between '.$from.' and '.$to,'count(comment_id) as total'); 
                $outbxcom = $db->selectAll($general->table(41),'where sendSuccess=0 and replyBy='.$u['uID'].' and replyTime between '.$from.' and '.$to,'count(scqID) as total');
                $outbox_comment = intval($outbxcom[0]['total']);
                $ah=$sReport->ahtNart($from,$to,$u['uID']);
                $total_replied = $sReport->commentsAdminActivity($from,$to,$u['uID'],'','','array',$jArray);


                $total_replyed_status = $db->selectAll($general->table($wallCommentReportTable),'where sender_id='.PAGE_ID.' and replyBy='.$u['uID'].' and replyTime between '.$from.' and '.$to,'replyTime,assignTime ');
                $total_reply_wall = count($total_replyed_status);

                $total_like_wall = $db->selectAll($general->table(28),'where uID='.$u['uID'].' and likeTime between '.$from.' and '.$to);
                $total_like_w=count($total_like_wall);

                $total_done_status = $db->selectAll($general->table($wallCommentReportTable),'where replyBy='.$u['uID'].' and isDone=1 and replyTime between '.$from.' and '.$to,'count(comment_id) as total');
                $dopost  = $db->selectAll($general->table(12),' WHERE replyBy='.$u['uID'].' and isDone=1 and replyTime between '.$from. ' AND '.$to,' count(post_id) as total');
                $total_done_status_wall = intval($total_done_status[0]['total']+$dopost[0]['total']);

                $total_hide_post_wall = $db->selectAll($general->table(35),'where uID='.$u['uID'].' and  hideTime between '.$from.' and '.$to,'count(hideTime) as total');
                $total_hide_status_wall =  intval($total_hide_post_wall[0]['total']);
                $total_delete_post_wall = $db->selectAll($general->table(25),'where uID='.$u['uID'].' and  remove_time between '.$from.' and '.$to,'count(remove_time) as total');
                $total_delete_status_wall = intval($total_delete_post_wall[0]['total']);
                $outbxcom = $db->selectAll($general->table(42),' WHERE sendSuccess=0 and replyBy='.$u['uID'].' and replyTime between '.$from.' and '.$to,'count(replyTime) as total'); 
                $outbxcom_wall =  intval($outbxcom[0]['total']);

                $ahtNart = $sReport->ahtNartWall($from,$to,$u['uID']);
                $aht_wall = $ahtNart['aht'];
                $art_wall = $ahtNart['art'];
                $total_replied_wall = $sReport->wallAdminActivity($from,$to,$u['uID']);
                if(OPERATION_MESSAGE_ALLWO==true){
                    //select sum(a.total) as total from (SELECT count(mid) as total FROM messages m where replyBy=44 and isDone=0 and replyTime between 1495562400 and 1495648799 group by m.sender_id,m.sendType,m.targetSeq,m.replyBy,m.replyTime) a
                    $query="
                    select sum(a.total) as total from (SELECT count(mid) as total FROM ".$general->table(9)." m where m.replyBy=".$u['uID']." and m.isDone=1 and m.sendType=1 and m.replyTime between ".$from." and ".$to." group by m.sender_id,m.targetSeq,m.replyBy,m.replyTime) a
                    ";
                    $total_done_status = $db->fetchQuery($query);
                    $total_done_msg= intval($total_done_status[0]['total']);
                    $echo ='No';
                    //if($u['uID']==44){$echo='';}
                    $query="
                    select sum(a.total) as total from (SELECT count(mid) as total FROM ".$general->table(9)." m where m.replyBy=".$u['uID']." and m.isDone=0 and m.sendType=2 and m.replyTime between ".$from." and ".$to." group by m.sender_id,m.targetSeq,m.replyBy,m.replyTime) a
                    ";
                    $jArray[__LINE__]=$query;
                    $total= $db->fetchQuery($query,$echo);
                    $tRpM= intval($total[0]['total']);
                    $ahtNart = $sReport->ahtNartMsg($from,$to,$u['uID']);
                    $ahtM= $ahtNart['aht'];
                    $artM= $ahtNart['art'];
                }
                $data = array(
                    'uFullName' => $u['uFullName'],
                    'cReplied'  => $total_reply,
                    'cLike'     => count($total_like_status),
                    'cDone'     => $total_done_comment,
                    'cHide'     => intval($total_hide_status[0]['total']),
                    'cDelete'   => intval($total_delete_status[0]['total']),
                    'cOutbox'   => $outbox_comment,
                    'cAht'      => $ah['aht'],
                    'cArt'      => $ah['art'],
                    'cRepliedT' => $total_replied,
                    'wReplied'  => $total_reply_wall,
                    'wLike'     => $total_like_w,
                    'wDone'     => $total_done_status_wall,
                    'wHide'     => $total_hide_status_wall ,
                    'wDelete'   => $total_delete_status_wall,
                    'wOutbox'   => $outbxcom_wall,
                    'wAht'      => $aht_wall,
                    'wArt'      => $art_wall,
                    'wRepliedT' => $total_replied_wall,

                    'mReplied' => $tRpM,
                    'mDone' => $total_done_msg,
                    'mOutbox' => 0,
                    'mAht' => $ahtM,
                    'mArt' => $artM,
                    'mRepliedT' => $tRpM+$total_done_msg,
                );
            }
            else{
                $data = array(
                    'uFullName' => $u['uFullName'],
                    'cReplied'  => 0,
                    'cLike'     => 0,
                    'cDone'     => 0,
                    'cHide'     => 0,
                    'cDelete'   => 0,
                    'cOutbox'   => 0,
                    'cAht'      => '00:00:00',
                    'cArt'      => '00:00:00',
                    'cRepliedT' => 0,
                    'wReplied'  => 0,
                    'wLike'     => 0,
                    'wDone'     => 0,
                    'wHide'     => 0,
                    'wDelete'   => 0,
                    'wOutbox'   => 0,
                    'wAht'      => '00:00:00',
                    'wArt'      => '00:00:00',
                    'wRepliedT' => 0,
                    'mReplied'  => 0,
                    'mDone'     => 0,
                    'mOutbox'   => 0,
                    'mAht'      => '-',
                    'mArt'      => '-',
                    'mRepliedT' => 0,
                );
            }
            $jArray['data']=$data;
            $db->reportCacheSet($cacheKey,json_encode($data),$expTime);
        }

    }
    $jArray['from']=$general->make_date($from,'time');
    $jArray['to']=$general->make_date($to,'time');
    $general->jsonHeader($jArray);
?>
