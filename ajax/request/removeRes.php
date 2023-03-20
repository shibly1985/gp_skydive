<?php
    if(isset($_POST['removeRes'])){
        $mtID = $_POST['mtID'];
            $where=array(
                'mtID'  => $mtID
            );   
            $delete = $db->delete($general->table(23),$where); 
        }
?>
