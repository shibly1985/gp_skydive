<?php
    $senderIds=$_POST['messageAssignmentSubmit'];
    $uIDs=$_POST['users'];

    foreach($senderIds as $sender_id){
        $sender=$db->get_rowData($general->table(16),'where sender_id='.$sender_id);
        if(!empty($sender)){
            $data=array(
                'assignTo'  => UID,
                'assignTime'=> TIME
            );
            $where=array('sender_id'=>$sender['sender_id']);
            $update=$db->update($general->table(16),$data,$where);
            $jArray['status']=1;
        }
    }
?>
