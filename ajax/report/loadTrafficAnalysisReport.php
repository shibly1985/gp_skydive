<?php
    $from=$_POST['from'];
    $to=$_POST['to'];
    if($from < $to && $to < strtotime('+1 hour')){
        $expTime=strtotime('+ 1 hour');    
        if($to<strtotime('-2 hour')){
            $expTime=strtotime('+ 6 month');    
        }
        $cacheKey='traffic_analysis_'.$from.'_'.$to;
        $jArray['time']=array(
            'f'=>$from,
            't'=>$to,
            'fr'=>$general->make_date($from,'time'),
            'to'=>$general->make_date($to,'time'),
        );
        $c=$db->reportCacheGet($cacheKey);
        $jArray['status']=1;
        if($c==true&&!isset($_GET['flush'])){
            $data=json_decode($c,true);
            $jArray['comment']=$data['comment'];
            $jArray['wall']=$data['wall'];
        }
        else{
            $cache=array();
            /*$reportTable=$db->settingsValue('commentReportTable');
            $wallCommentReportTable=$db->settingsValue('commentWallReportTable');*/

            /*$query="
            select r.replyTime ,c.created_time
            from  ".$general->table($wallCommentReportTable)." r
            left join ".$general->table($wallCommentReportTable)." c on c.comment_id=r.target_c_id
            where r.sender_id=".PAGE_ID."
            AND r.replyTime BETWEEN ".$from." AND ".$to."
            order by c.created_time desc
            limit 1
            ";
            $com=$db->fetchQuery($query); 
            $com=$com[0];*/

            $qReceived          =$sReport->commentsUserActivity($from,$to,'','array',$jArray);
            $totalReplyCount    =$sReport->commentsAdminActivity($from,$to,0,'','','array',$jArray);
            $totalReplyCount20Min=$sReport->commentsAdminActivity($from,$to,0,60*20);

            $ah=$sReport->ahtNart($from,$to,0);

            if($qReceived>0&&$totalReplyCount>0){
                $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                $sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
            }
            else{
                $rr='00.00';
                $sl='00.00';
            }
            /*if($qReceived>0&&$totalReplyCount>0){
                $rr=round(($totalReplyCount/$qReceived)*100,2);
                $sl=round(($totalReplyCount20Min/$totalReplyCount)*100,2);
            }
            else{
                $rr=0;
                $sl=0;
            }*/

            $data = array(
                'date'              => date('d-M-y',$from),
                'from'              => date('h:i A',$from),
                'to'                => date('h:i A',$to),
                'qReceived'         => $qReceived,
                'totalReply'        => $totalReplyCount,
                'totalReply20Min'   => $totalReplyCount20Min,
                'aht'               => $ah['aht'],
                'art'               => $ah['art'],
                'mwt'               => $ah['mwt'],
                'mwID'               => $ah['mwID'],
                'rr'                => $rr."%",
                'sl'                => $sl."%"
            );
            $jArray['comment']=$data;
            $cache['comment']=$data;

            /*$com=$db->selectAll($general->table(12),' WHERE replyTime between '.$from. ' AND '.$to,' max(replyTime-created_time) as t');
            $a=$com[0]['t'];
            $query="
            select r.replyTime ,max( ifnull(p.created_time,c.created_time) ) as created_time
            from  ".$general->table($wallCommentReportTable)." r
            left join ".$general->table($wallCommentReportTable)." c on c.comment_id=r.target_c_id
            left join ".$general->table(12)." p on p.post_id=r.target_c_id
            where r.sender_id=".PAGE_ID." and r.target_c_id!=''
            AND r.replyTime BETWEEN ".$from." AND ".$to."
            limit 1
            ";*/

            $qReceived=$sReport->wallPosts($from,$to);
            $totalReplyCount=$sReport->wallAdminActivity($from,$to);
            $totalReplyCount20Min=$sReport->wallAdminActivity($from,$to,0,array(0,60*5,'all'));
            $ah=$sReport->ahtNartWall($from,$to,0,'array',$jArray);
            if($qReceived>0){
                $tc+=$qReceived;
                $rr=str_pad(number_format(round(($totalReplyCount/$qReceived)*100,2),2),5,0,STR_PAD_LEFT);
                $sl=str_pad(number_format(round(($totalReplyCount20Min/$totalReplyCount)*100,2),2),5,0,STR_PAD_LEFT);
            }
            else{
                $rr='00.00';
                $sl='00.00';
            }
            $data = array(
                'date'              => date('d-M-y',$from),
                'from'              => date('h:i A',$from),
                'to'                => date('h:i A',$to),
                'qReceived'         => $qReceived,
                'totalReply'        => $totalReplyCount,
                'totalReply20Min'   => $totalReplyCount20Min,
                'aht'               => $ah['aht'],
                'art'               => $ah['art'],
                'mwt'               => $ah['mwt'],
                'mwID'               => $ah['mwID'],
                'rr'                => $rr."%",
                'sl'                => $sl."%"
            );
            $jArray['wall']=$data;
            $cache['wall']=$data;
            $db->reportCacheSet($cacheKey,json_encode($cache),$expTime);
        }
    }
    $general->jsonHeader($jArray);
?>
