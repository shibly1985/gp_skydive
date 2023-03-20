<?php
    $commentIds=$_POST['hideNdeleteAssignmentThreads'];
    $type=$_POST['type'];
    //$jArray[__LINE__]=__LINE__;
    if(!empty($commentIds)){
        //$jArray[__LINE__]=__LINE__;
        $idForBan=array();
        $commentForDelete=array();
        foreach($commentIds as $comment_id=>$sender_id){
            if($type=='c'){
                //$jArray[__LINE__]=__LINE__;
                $c=$social->getCommentInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['sender_id']==$sender_id){
                        $idForBan[$comment_id]=$c['sender_id'];
                        $commentForDelete[$comment_id]=$c;
                    }
                }
            }
            else if($type=='w'){
                $c=$social->getPostInfoById($comment_id,$type);
                if(!empty($c)){
                    if($c['sender_id']==$sender_id){
                        $idForBan[$comment_id]=$c['sender_id'];
                    }
                } 
            }
        }
        if(!empty($idForBan)){
            //$jArray[__LINE__]=__LINE__;
            $jArray['ban']=$idForBan;
            if($type=='c'){//ওয়ালে কিছু কাজ করতে হবে তাই আপাতত অফ করে রাখা হল
                $jArray['banStatus']=$social->banFromFB($type,$idForBan,'','c',$jArray);
                foreach($commentForDelete as $c){
                    //$jArray[__LINE__]=__LINE__;
                    $social->hideFromFB($c['comment_id'],$type);
                    $social->commentPostDone($type,$c['comment_id']);
                }
            }
            $rt=$social->assignmentData($type);
            $jArray['comments']=$rt['comments'];
            $jArray['names']=$rt['names'];

        }
        else{
            //$jArray[__LINE__][]=$comment_id;
        }
        $jArray['status']=1;
    }
    $general->jsonHeader($jArray);
?>
