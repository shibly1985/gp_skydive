<?php
    if(isset($_POST['logoutForBreak'])){
        $textAppTime = intval($_POST['textAppTime']);
        $reason      = intval($_POST['reason']);
        $data = array(
            'ubrID'             => $reason,
            'uID'               => UID,
            'btAppReturnTime'   => strtotime('+'.$textAppTime.' minute', TIME),
            'btTime'            => TIME
        );
        $insert = $db->insert($general->table(43),$data,'','array');
        if($insert){
        $jArray['status']=1;        
        $jArray[__LINE__]=$insert;
        }
        $general->jsonHeader($jArray);
    }
?>