<?php
    if($db->modulePermission(149)==true){
        $ugID = intval($_GET['get_premessions']);
        $ug=$db->groupInfoByID($ugID);
        if(empty($ug)){SetMessage(63,'Group');$error=__LINE__;}
        else{$pFor=$ug['ugTitle'];}
        if(!isset($error)){
            $pcStatus= $db->permission(88);
            $onlyDevleperMenu=array(138,141,144);
            $onlyDevleperPermission=array(80,95,PER_ADD_AGENT,PER_SERVICE_TIME,PER_USER_LICENCE,PER_BULK_REPLY_COMMENT,PER_BULK_REPLY_WALL);
            if(UGID!=SUPERADMIN_USER){
                $q='where cmID not in('.implode(',',$onlyDevleperMenu).') ';
                $pq=' and perID not in('.implode(',',$onlyDevleperPermission).') ';
            }else{$q='';$pq='';}
            $mod   = $db->selectAll($general->table(1),$q.' order by cmTitle asc');
        ?>
        <p>Permission for <b><?=$pFor?></b> Group</p>
        <style type="text/css">.mback{background-color: #9AB8CF;}.mback:hover{background-color: #9AB8CF !important;}</style>
        <div class="report_table">
            <table class="table table_fixed_header">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Permission</th>
                        <?php
                            if($pcStatus==true){
                            ?>
                            <th>Status</th>
                            <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 1;
                        foreach($mod as $m){
                            $pers = $db->selectAll($general->table(19),'where cmId='.$m['cmId'].$pq);
                            $d = $db->getRowData($general->table(29),'where cmId='.$m['cmId'].' and ugID='.$ugID);
                            if(empty($d)){$check=0;}else{$check=1;}
                        ?>
                        <tr class="mback">
                            <td><b><?=$total++?></b></td>
                            <td><?=$m['cmTitle']?>(<?=$m['cmId']?>)</td>
                            <td>&nbsp;</td>
                                <td>
                            <?php
                                if($pcStatus==true){

                                ?>
                                    <input type="checkbox" class="check_box" <?=$general->checked($check)?>
                                        onclick="change_m_permission('<?=$m['cmId']?>',this.checked);"
                                        id="act_m_<?=$m['cmId']?>"
                                        name="act_m_<?=$m['cmId']?>">
                                    <label for="act_m_<?=$m['cmId']?>"></label>
                                <?php
                                }else{echo $check==1?'Active':'Deactive';}
                            ?>
                                </td>
                        </tr>
                        <?php
                            foreach($pers as $p){
                                $per=0;
                                if($pcStatus==true){
                                    $d = $db->getRowData($general->table(21),' where perID='.$p['perID'].' and ugID='.$ugID);
                                    if(empty($d)){$check=0;}else{$check=1;}
                                }
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><?=$p['perDesc']?>(<?=$p['perID']?>)</td>
                                    <td>
                                <?php
                                    if($pcStatus==true){
                                    ?>
                                        <input type="checkbox" class="check_box" <?=$general->checked($check)?>
                                            onclick="change_permission('<?=$p['perID']?>',this.checked);"
                                            id="act_<?=$p['perID']?>"
                                            name="act_<?=$p['perID']?>">
                                        <label for="act_<?=$p['perID']?>"></label>
                                    <?php
                                    }else{echo $check==1?'Active':'Deactive';}
                                ?>
                                    </td>
                            </tr>
                            <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <script type="text/javascript">
            function change_permission(perId,perStatus){
                if(perStatus==true){var ch=1;}else{var ch=0;}
                jx.load('?ajax=1&change_premessions=<?=$ugID?>&per='+perId+'&st='+ch,function(data){});
            }
            function change_m_permission(mId,perStatus){
                if(perStatus==true){var ch=1;}else{var ch=0;}
                jx.load('?ajax=1&change_m_premessions=<?=$ugID?>&mid='+mId+'&st='+ch,function(data){
                    //alert(data);
                });
            }
        </script>
        <?php
        }
    }else{
        SetMessage(52,'Privilege');
    }
    show_msg();
?>
