<?php
    $comment_id     = $_POST['postCommentHide'];
    $targetType     = $_POST['targetType'];
    $type           = $_POST['type'];if($type!='c')$type='w';
    $isClose        = intval($_POST['isClose']);if($isClose!=1)$isClose=0;
    if($targetType=='p'){
        if($comment_id==''){
            $error=__LINE__;
            SetMessage(63,'Post');
        }
        else{
            $post_id=$comment_id;
            $p=$social->getPostInfoById($post_id,'w');
            $comment_id=$p['post_id'];
            //$jArray[__LINE__]=$p;
        }
    }
    elseif($targetType=='c'){
        if($comment_id==''){
            $error=__LINE__;
            SetMessage(63,'Comment');
        }
        else{
            $c=$social->getCommentInfoById($comment_id,$type);
            if(!empty($c)){
                $post_id=$c['post_id'];
                if($c['parent_id']!=$post_id){
                    $targetCommentID=$c['parent_id'];
                    $parent=$c['parent_id'];
                }
            }
            else{
                $error=__LINE__;SetMessage(63,'Comment');
            }
        }
    }
    else{$error=__LINE__;SetMessage(63,'Targate type');}
    if(!isset($error)){
        $jArray['targetType']=$targetType;
        //$social->newLikeForTemp($comment_id,$type,$targetType,$jArray);
        $jArray['hide']=$social->hideFromFB($comment_id,$type,$targetType);
        //$social->newTempLikeDelete($comment_id,$type,$targetType,$jArray);
        if($jArray['hide']==true){
            $social->commentPostDone($type,$comment_id,$jArray);
        }
        else{
            
        }
        //textFileWrite(date('d/m/Y h:i:s').' -- '.json_encode($jArray));
    }
    if($isClose==0){
        if(isset($error)){
            $jArray['err']=$error;
        }
        $jArray['status']=1;
        $jArray['nextComment']=$social->nextPicup($type);
    }
    $general->jsonHeader($jArray);
?>