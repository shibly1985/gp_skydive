<?php
    $names=array();
    $threads= explode(',',$_POST['threads']);
    $users  = explode(',',$_POST['users']);
    if($_POST['assignmentSubmit']=='w'){
        $type='w';
        $assignTypeCode='wallPostAssign';

        foreach($threads as $d){
            $e=explode('-',$d);
            $a=$e[0];
            $t=$e[1];
            if($a=='p'){
                $c=$db->get_rowData($general->table(12),'post_id',$t);
                if(!empty($c)){
                    foreach($users as $uID){
                        $data=array(
                            'post_id'       => $t,
                            'uID'           => $uID,
                            'created_time'  => $c['created_time'],
                            'assign_time'   => TIME
                        );
                        $insert=$db->insert($general->table(5),$data,'','array');
                        $jArray[__LINE__][]=$insert;
                        if(!$insert){$error=__LINE__;}
                    }
                }
            }
            else if ($a=='c'){
                $c=$db->get_rowData($general->table(14),'comment_id',$t);
                if(!empty($c)){
                    foreach($users as $uID){
                        $data=array(
                            'comment_id'    => $t,
                            'uID'           => $uID,
                            'created_time'  => $c['created_time'],
                            'assign_time'   => TIME
                        );
                        $insert=$db->insert($general->table(36),$data,'','array');
                        $jArray[__LINE__][]=$insert;
                        if(!$insert){$error=__LINE__;}
                    }
                }
            }
        }


    }
    else{
        $type='c';
        $id='comment_id';
        $cTbl=13;
        $aTbl=10;
        $assignTypeCode='pagePostAssign';
        foreach($threads as $t){
            $c=$db->get_rowData($general->table($cTbl),$id,$t);
            if(!empty($c)){
                foreach($users as $uID){
                    $data=array(
                        $id             => $t,
                        'uID'           => $uID,
                        'created_time'  => $c['created_time'],
                        'assign_time'   => TIME
                    );
                    $insert=$db->insert($general->table($aTbl),$data);
                    if(!$insert){$error=__LINE__;}
                }
            }
        }

    }
    if(!isset($error)){
        $jArray=array('status'=>1);
        $rt=$social->assignmentData($type,50);
        $jArray['comments']=$rt['comments'];
        $jArray['names']=$rt['names'];
    }
    $general->jsonHeader($jArray);
?>