<?php
    $jArray=array('status'=>1);
    $mtID = $_POST['mtID'];             
    $data=$db->get_rowData($general->table(23),'mtID',$mtID); 
    $jArray['data']=$data;
    $response = $jArray['data']['mtText'];
    $response   = str_replace("&#39", "'", $response);
    $response   = str_replace('&#34', '"', $response);
    $jArray['data']['mtText']=$response;
    //print_r($data);
    header('Content-Type: application/json');
    //echo 'sdlfldf';
    echo json_encode($jArray);
