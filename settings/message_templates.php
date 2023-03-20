<?php
    $tPTbl=23;
    $globalTemplates=$db->permission(82);
    if(isset($_GET['add'])){
        if(isset($_POST['add'])){
            $title      = $_POST['title'];
            $template   = $_POST["template"];
            if(empty($template)){SetMessage(5);$error=1;}
            if(!isset($error)){
                $data = array(
                    'mtTitle'    => $title,
                    'mtText'    => $template,
                    'createdOn' => TIME,
                    'createdBy' => UID
                );
                $insert = $db->insert($general->table($tPTbl),$data);
                if($insert){$general->redirect($pUrl,array(29,'Message Template'));}
            }
        }
        $data = array($pUrl=>'Message Templates','1'=>'Add');$general->breadcrumb($data);
    ?>
    <?php show_msg();?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class=" col-md-3 col-sm-3 col-xs-12 control-label">Title:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" name="title" required="required" value="<?php echo @$_POST['title'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-md-3 col-sm-3 col-xs-12 control-label">Template:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea row="3" class="form-control" name="template" required="required"><?php @$_POST['template'] ?></textarea>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="submit" name="add" value="Create" class="btn btn-success create_button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
    elseif(isset($_GET['edit'])){
        $edit = intval($_GET['edit']);
        $m = $db->get_rowData($general->table($tPTbl),'mtID',$edit);
        if(empty($m)){$general->redirect($pUrl,37,$rModule['cmTitle']);} 

        if(isset($_POST['edit'])){

            $title      = $_POST['title'];
            $template   = $_POST["template"];
            if(empty($template)){SetMessage(5);$error=1;}
            if(!isset($error)){
                $data = array(
                    'mtTitle'    => $title,
                    'mtText'    => $template,
                    'modifiedOn'  => TIME,
                    'modifiedBy'=> UID
                );
                $where = array('mtID'=>$edit);
                $update = $db->update($general->table($tPTbl),$data,$where);
                if($update){$general->redirect($pUrl,30,'Message Template');}
            }
        }  
        $data = array($pUrl=>'Message Template','1'=>'Edit');$general->breadcrumb($data);
    ?>
    <?php show_msg();?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form class="form-horizontal form-label-left" method="post">
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label col-md-3 col-sm-3 col-xs-12">Title:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="text" name="title" required="required" value="<?php echo $m['mtTitle'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-md-3 col-sm-3 col-xs-12 control-label">Template:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control col-md-7 col-xs-12" name="template" required="required"><?php echo $m['mtText'] ?></textarea>
                        </div>
                    </div>
                     <div class="ln_solid"></div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="submit" name="edit" value="Update" class="btn btn-success create_button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
    else{
        $sn=1;
        $sqlQuery = "where mtType=0";
        $mTemplates   = $db->selectAll($general->table($tPTbl),$sqlQuery);
        $data = array('1'=>'Message Templates');$general->breadcrumb($data);
    ?>
    <?php show_msg();?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $rModule['cmTitle'];?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="<?php echo $pUrl;?>&add=1"><i class="fa fa-plus"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table  class="table table-striped users_view_table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Message Title</th>
                            <th>Message</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <?php
                        foreach($mTemplates as $m){
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?=$general->content_show($m['mtTitle'])?></td>
                            <td><?=$social->messageTemplateMake($general->content_show($m['mtText']))?></td>
                            <td>
                                <a href="<?=$pUrl?>&edit=<?=$m['mtID']?>">
                                    <button  class="btn btn-sm btn-default">Edit</button>
                                </a>
                            </td>
                            <td><?php $general->onclickChangeBTN($m['mtID'],$general->checked($m['isActive']));?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <?php
        $general->onclickChangeJavaScript($tPTbl,'mtID');
    }
?>