<?php
    if(isset($_POST['makeFav'])){
        $jArray['status']=1;
        $mtID = $_POST['mtID'];
        $exist = $db->selectAll($general->table(24),'where mtID='.$mtID.' and uID='.UID);
        if(!$exist){
            $data=array(
                'mtID'  => $mtID,
                'uID'   => UID
            );   
            $insertFavourite = $db->insert($general->table(24),$data,'getId'); 
        }
        $data = $db->get_rowData($general->table(23),'mtID',$mtID);
        $txt=$social->messageTemplateMake($data['mtText']);
        $jArray=array(
            'fmtID'     => $insertFavourite,
            'mtID'      => $data['mtID'],
            'mtTitle'   => $data['mtTitle'],
            'mtText'    =>$txt
        );
        header('Content-Type: application/json');
        echo json_encode($jArray);
    }
?>
