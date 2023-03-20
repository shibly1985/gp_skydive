<?php
    if(isset($_POST['editResponse'])){
        $jArray=array('status'=>1);
        $mtID = $_POST['mtID'];
        $title      = $_POST['title'];
        $response   = rawurldecode($_POST["response"]);
        $response   = str_replace("\'", "&#39", $response);
        $response   = str_replace('\"', "&#34", $response);
        $data = array(
            'mtTitle'    => $title,
            'mtText'    => $response,
            'modifiedOn' => TIME,
            'modifiedBy' => UID
        );
        $where = array(
            'mtID' => $mtID
        );
        $update = $db->update($general->table(23),$data,$where);
        if($update){
            $jArray=array(
                'mtID'    => $mtID,
                'mtTitle'     => $title,
                'mtText'       => $response
            );
          $general->jsonHeader($jArray);
        }
    }
?>