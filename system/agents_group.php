<?php
    $aStatus=$db->permission(95);
    if(isset($_GET['add'])){
        if($aStatus==false){$general->redirect($pUrl,82,'Agent Group Add');}
        if(isset($_POST['add'])){
            $title          = $_POST["title"];
            $description    = $_POST["description"];

            if(empty($title)){$error=__LINE__;SetMessage(36,'Operator Group Title');}

            if(!isset($error)){
                if(!isset($error)){
                    $data=array(
                        'ugTitle'         => $title,
                        'ugDescription'   => $description,
                    );
                    $insert = $db->insert($general->table(22),$data);
                    if($insert){$general->redirect($pUrl,29,'Operator Group');}
                    else{SetMessage(66);}
                }
            }
        }
        show_msg();
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form action="" data-parsley-validate class="form-horizontal form-label-left" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Group Title<span style="color: red;">*</span>:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input required="required" class="form-control col-md-7 col-xs-12" value="<?=@$_POST['title']?>" type="text" placeholder="Group Title" name="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Description<span style="color: red;">*</span>:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12" name="description"><?=@$_POST['description']?></textarea>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="submit" class="btn btn-success" value="Submit" name="add">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php      
    }
    elseif(isset($_GET['edit'])){
        $ugID =$_GET['edit'];
        $ugd=$db->get_rowData($general->table(22),'ugID',$ugID);
        if(empty($ugd)||$ugID==SUPERADMIN_USER){
            $general->redirect($pUrl,37,'Operator Group'); 
        }
        if(isset($_POST['sub'])){
            $title           = mysql_real_escape_string($_POST["title"]);
            $description        = mysql_real_escape_string($_POST["description"]);
            if(empty($title)){$error=__LINE__;SetMessage(36,'User Group Title');}
            if(!isset($error)){
                if(!isset($error)){
                    $where=array(
                        'ugID' =>$ugID
                    );
                    $data=array(
                        'ugTitle'         => $title,
                        'ugDescription'   => $description
                    );
                    $update = $db->update($general->table(22),$data,$where);
                    if($update){$general->redirect($pUrl,30,'Operator Group');}
                    else{SetMessage(66);}
                }
            }
        }
        show_msg();
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit  <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form action="" data-parsley-validate class="form-horizontal form-label-left" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Group Title<span style="color: red;">*</span>:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $ugd['ugTitle'] ?>"  type="text" placeholder="Group Title" name="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Description<span style="color: red;">*</span>:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12" name="description"><?php echo $general->content_show($ugd['ugDescription']) ?></textarea>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="submit" class="btn btn-success" value="Update" name="sub">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div> 
    <?php 
    }
    else{
        show_msg();
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $rModule['cmTitle'];?> </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <?php
                        if($aStatus==true){
                        ?>
                        <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
                        <?php
                        }
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-striped users_view_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sl=1;
                            $usergroup = $db->selectAll($general->table(22),'where ugID!='.SUPERADMIN_USER);
                            foreach($usergroup as $ug){
                            ?>
                            <tr>
                                <td><?=$sl++?></td>
                                <td><?=$ug['ugTitle']?></td>
                                <td><?=$ug['ugDescription']?></td>
                                <!-- <td><a class="btn btn-sm btn-default" href="<?=$pUrl?>&view=<?=$ug['ugID']?>">View</a></td>-->
                                <td><a class="btn btn-sm btn-default" href="<?=$pUrl?>&edit=<?=$ug['ugID']?>">Edit</a></td>
                                <td><?=$general->onclickChangeBTN($ug['ugID'],$general->checked(1,$ug['isActive']))?></td>
                            </tr>
                            <?php
                                $general->onclickChangeJavaScript(22,'ugID');
                            }    
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <?php
    }
?>