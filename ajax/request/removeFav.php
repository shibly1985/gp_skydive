<?php
        if(isset($_POST['removeFav'])){
            $fmtID = $_POST['fmtID'];
                $where=array(
                    'fmtID'  => $fmtID
                );   
                $delete = $db->delete($general->table(24),$where,'as'); 
            }
?>
