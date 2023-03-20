<?php
    if(isset($_POST['addResponse'])){
        $jArray['status']=1;
        $title      = $_POST['title'];
        $response   = rawurldecode($_POST["response"]);
  /*      $response   = str_replace('\"', "&#34", $response);
        $response   = str_replace('\#', "&#35", $response);
        $response   = str_replace('\@', "&#64", $response);   
        $response   = str_replace('\*', "&#42", $response);    */
        $response   = str_replace("\'", "&#39", $response);
        $response   = str_replace('\"', "&#34", $response);
        $data = array(
            'mtTitle'    => $title,
            'mtText'    => $response,
            'mtType'    => 1,
            'createdOn' => TIME,
            'createdBy' => UID
        );
        $insert = $db->insert($general->table(23),$data,'getId');

        $jArray=array(
            'mtID'    => $insert,
            'mtTitle'     => $title,
            'mtText'       => $response
        );
       $general->jsonHeader($jArray);        
    }
?>