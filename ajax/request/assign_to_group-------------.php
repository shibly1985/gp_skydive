<?php
    if(isset($_GET['feed_set'])){
        $fID=$_GET['feed_set'];
        $sl=1;
        $group=$db->selectAll($general->table(22),'where IsActive=1 and ugID!='.SUPERADMIN_USER);
        foreach($group as $g){
            // $assignd=$db->getRowData($general->table(6)," where fID='".$fID."' and ugID=".$g['ugID']);
        ?>      
        <tr>   
            <td><?php echo $sl++; ?></td>
            <td><label><input <?php if(!empty($assignd)){?>checked="checked" <?php } ?> type="checkbox" class="group" value="<?php echo $g['ugID']; ?>"> <?=$g['ugTitle']?></label></td>
        </tr>
        <?php
        }

    }
    else{
        $ug_id=$_GET['ugID'];
        $fID=$_GET['fID'];
        $ug_array =explode(",",$ug_id);

        $where=array(
            'fID' => $fID
        );
        $delete=$db->delete($general->table(6),$where);
        foreach($ug_array as $ugID){
            $data = array(
                'ugID'          => $ugID,
                'fAssignBy'     => UID,
                'fAssignTime'   => TIME,
                'fID' => $fID
            );
            $assign=$db->insert($general->table(6),$data);
        }
    }
?>
