<?php
    $jArray=array('status'=>1);
    if(isset($_GET['ug_id'])){
        $ugID=$_GET['ug_id'];
        $where=' WHERE ugID='.$ugID;
    }else{
        $where=' WHERE ugID!='.SUPERADMIN_USER;
    }

    $data=$db->selectAll($general->table(17),$where);
    $user_data=array();
    foreach($data as $d){
        $user_data[] =array('id'=>$d['uID'],'name'=>$d['uFullName']) ;
    }
    $jArray['user_data']=$user_data;
    $general->jsonHeader($jArray);
?>