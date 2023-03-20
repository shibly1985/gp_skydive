<?php
    include("crn_config.php");
    $reportTable=$db->settingsValue('commentReportTable');
    $wallCommentReportTable=$db->settingsValue('commentWallReportTable');

    $from=YESTERDAY_TIME;
    $to=strtotime('+1 day',$from);
    $to=strtotime('-1 second',$to);
    $expTime=strtotime('+6 month');
    $cacheKey='x_sec_report_'.$from.'_'.$to;
    $jArray=array(
        'commentData'=>array(),
        'commentTotal'=>array(),
        'wallData'=>array(),
        'wallTotal'=>array(),
    );
    $tua=0;
    $tr=0;
    $trMin0=0;
    $trMin6=0;
    $trMin16=0;
    $trMin21=0;
    $trMin31=0;
    $trMin61=0;
    $trMin361=0;
    $wtua=0;
    $wtr=0;
    $wtrMin0=0;
    $wtrMin6=0;
    $wtrMin16=0;
    $wtrMin21=0;
    $wtrMin31=0;
    $wtrMin61=0;
    $wtrMin361=0;

    //$ddd=0;
    $dates = range($from, $to,3600);
    foreach($dates as $from){
        //if(date('h:i A',$from)=='11:00 AM'){
        /*if($ddd>4)break;
        $ddd++;*/
        $to=strtotime('+ 1 hour',$from);
        $to=strtotime('-1 second',$to);
        $echo='No';
        //$echo='o';
        $totalUserActivity=$sReport->commentsUserActivity($from,$to,$echo);
        $tua+=$totalUserActivity;
        $totalReplyCount=$sReport->commentsAdminActivity($from,$to,0,'',$echo);
        $tr+=$totalReplyCount;
        $totalReplyCount0=$sReport->commentsAdminActivity($from,$to,0,array(0,60*5,'all'),$echo);
        $trMin0+=$totalReplyCount0;
        $totalReplyCount6=$sReport->commentsAdminActivity($from,$to,0,array(60*5+1,60*15,'all'),$echo);
        $trMin6+=$totalReplyCount6;
        $totalReplyCount21=$sReport->commentsAdminActivity($from,$to,0,array(60*15+1,60*20,'all'),$echo);
        $trMin21+=$totalReplyCount21;
        $totalReplyCount16=$sReport->commentsAdminActivity($from,$to,0,array(60*20+1,60*30,'all'),$echo);
        $trMin16+=$totalReplyCount16;
        $totalReplyCount31=$sReport->commentsAdminActivity($from,$to,0,array(60*30+1,60*60,'all'),$echo);
        $trMin31+=$totalReplyCount31;
        $totalReplyCount61=$sReport->commentsAdminActivity($from,$to,0,array(60*60+1,60*360,'all'),$echo);
        $trMin61+=$totalReplyCount61;
        $totalReplyCount361=$sReport->commentsAdminActivity($from,$to,0,array(60*360+1,0,'all'),$echo);
        $trMin361+=$totalReplyCount361;
        $ah=$sReport->ahtNart($from,$to);
        $data=array(
            'date'=>date('d-M-y',$from),
            'from'=>date('h:i A',$from),
            'to'=>date('h:i A',$to),
            'ua'=>$totalUserActivity,
            'ar'=>$totalReplyCount,
            'art'=>$ah['art'],
            'min0'=>$totalReplyCount0,
            'min6'=>$totalReplyCount6,
            'min16'=>$totalReplyCount16,
            'min21'=>$totalReplyCount21,
            'min31'=>$totalReplyCount31,
            'min61'=>$totalReplyCount61,
            'min361'=>$totalReplyCount361
        );
        $jArray['commentData'][]=$data;

        $totalUserActivity=$sReport->wallPosts($from,$to);
        $wtua+=$totalUserActivity;
        $totalReplyCount=$sReport->wallPosts($from,$to);
        $totalReplyCount=$sReport->wallAdminActivity($from,$to);
        $wtr+=$totalReplyCount;
        $totalReplyCount0=$sReport->wallAdminActivity($from,$to,0,array(0,60*5,'all'));
        $wtrMin0+=$totalReplyCount0;
        $totalReplyCount6=$sReport->wallAdminActivity($from,$to,0,array(60*5+1,60*15,'all'));
        $wtrMin6+=$totalReplyCount6;
        $totalReplyCount16=$sReport->wallAdminActivity($from,$to,0,array(60*15+1,60*20,'all'));
        $wtrMin16+=$totalReplyCount16;
        $totalReplyCount21=$sReport->wallAdminActivity($from,$to,0,array(60*20+1,60*30,'all'));
        $wtrMin21+=$totalReplyCount21;
        $totalReplyCount31=$sReport->wallAdminActivity($from,$to,0,array(60*30+1,60*60,'all'));
        $wtrMin31+=$totalReplyCount31;
        $totalReplyCount61=$sReport->wallAdminActivity($from,$to,0,array(60*60+1,60*360,'all'));
        $wtrMin61+=$totalReplyCount61;
        $totalReplyCount361=$sReport->wallAdminActivity($from,$to,0,array(60*360+1,0,'all'));
        $wtrMin361+=$totalReplyCount361;
        $ah=$sReport->ahtNartWall($from,$to);

        $data=array(
            'date'  => date('d-M-y',$from),
            'from'  => date('h:i A',$from),
            'to'    => date('h:i A',$to),
            'ua'    => $totalUserActivity,
            'ar'    => $totalReplyCount,
            'art'   => $ah['art'],
            'min0'  => $totalReplyCount0,
            'min6'  => $totalReplyCount6,
            'min16' => $totalReplyCount16,
            'min21' => $totalReplyCount21,
            'min31' => $totalReplyCount31,
            'min61' => $totalReplyCount61,
            'min361'=> $totalReplyCount361
        );
        $jArray['wallData'][]=$data;

        //}
    }
    $jArray['commentTotal']=array(
        'ua'    => $tua,
        'ar'    => $tr,
        'min0'  => $trMin0,
        'min6'  => $trMin6,
        'min16' => $trMin16,
        'min21' => $trMin21,
        'min31' => $trMin31,
        'min61' => $trMin61,
        'min361'=> $trMin361

    );
    $jArray['wallTotal']=array(
        'ua'    => $wtua,
        'ar'    => $wtr,
        'min0'  => $wtrMin0,
        'min6'  => $wtrMin6,
        'min16' => $wtrMin16,
        'min21' => $wtrMin21,
        'min31' => $wtrMin31,
        'min61' => $wtrMin61,
        'min361'=> $wtrMin361

    );
    //$general->printArray($jArray);
    $db->reportCacheSet($cacheKey,json_encode($jArray),$expTime);

    mysqli_close($GLOBALS['connection']);
?>
