<?php
    $date_range=$_POST['date_range'];
    $keyword=$_POST['keyword'];
    $dates=explode('__',$date_range);
    $from=strtotime($dates[0]);
    $to=strtotime($dates[1]);
    if(date('h:i',$to)=='12:00'){
        $to=strtotime('+23 hour',$to);
        $to=strtotime('+59 minute',$to);
    }
    
    $type=$_POST['assignmentLoad'];
    if($type=='c'||$type=='w'){
        $jArray=array('status'=>1);
        $rt=$social->assignmentData($type,$from,$to,$keyword);
        $jArray['comments']=$rt['comments'];
        $jArray['names']=$rt['names'];
        $jArray['post_ids']=$rt['post_ids'];
//        $jArray['q']=$rt['q'];
    }
    $general->jsonHeader($jArray);
?>
