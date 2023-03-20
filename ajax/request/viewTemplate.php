<?php
    $jArray=array('status'=>1);
    $mtID = $_GET['mtID'];             
    $data=$db->get_rowData($general->table(23),'mtID',$mtID); 
    $jArray['data']=$data;
    //print_r($data);
    header('Content-Type: application/json');
    //echo 'sdlfldf';
    echo json_encode($jArray);
