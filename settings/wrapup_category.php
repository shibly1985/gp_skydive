<?php
    $tPTbl=34;
    if(isset($_GET['add'])){
        if(isset($_POST['add'])){
            $title   = $_POST["c_title"];
            if(empty($title)){SetMessage(31,'Category Title');$error=1;}
            if(!isset($error)){
                $data = array(
                    'wcTitle'   => $title,
                    'createdOn' => TIME,
                    'createdBy' => UID
                );
                $insert = $db->insert($general->table($tPTbl),$data,'','d');
                if($insert){$general->redirect($pUrl,array(29,'Wrapup Category'));}
                else{SetMessage(66);}
            }
        }
        $data = array($pUrl=>$rModule['cmTitle'],'1'=>'Add');$general->breadcrumb($data);
        
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php
                    show_msg();
                ?>
                <form action="" data-parsley-validate class="form-horizontal form-label-left" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Category Title<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" value="<?=@$_POST['c_title']?>" name="c_title" required="required" value="">
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="add" class="btn btn-success">Submit</button>
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

        $wrap = $db->get_rowData($general->table($tPTbl),'wcID',$edit);
        if(empty($wrap)){$general->redirect($pUrl,array(37,'Wrapup Category'));}
        if(isset($_POST['sub'])){
            $title   = $_POST["c_title"];
            if(empty($title)){SetMessage(31,'Category Title');$error=1;}

            if(!isset($error)){
                $data = array(
                    'wcTitle'   => $title,
                    'createdOn' => TIME,
                    'createdBy' => UID
                );
                $where = array('wcID'=>$edit);
                $update = $db->update($general->table($tPTbl),$data,$where);
                if($update){$general->redirect($pUrl,30,'Wrapup Category');}
            }
        }
        $data = array($pUrl=>'Wrapup','1'=>'Edit');$general->breadcrumb($data);
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit <?php echo $rModule['cmTitle'];?></h2>
                <div class="clearfix"></div>
            </div>
            <?php show_msg() ?>
            <div class="x_content">
                <br />
                <form action="" data-parsley-validate class="form-horizontal form-label-left" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Category Title<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" value="<?= $wrap['wcTitle']?>" name="c_title" required="required" value="">
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="submit" name="sub" class="btn btn-success" value="Update">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php 
    }
    else{
        $wrapup   = $db->selectAll($general->table($tPTbl));
        $data = array('1'=>$rModule['cmTitle']);$general->breadcrumb($data);
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
                <table class="table table-striped users_view_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Edit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($wrapup as $w){
                            ?>
                            <tr>
                                <td scope="row"><?php echo $i++; ?></td>
                                <td><?=$w['wcTitle']?></td>
                                <td><a class="btn btn-sm btn-default" href="<?=$pUrl?>&edit=<?=$w['wcID']?>">Edit</a></td>
                                <td><?php $general->onclickChangeBTN($w['wcID'],$general->checked($w['isActive']));?></td>
                            </tr>
                            <?php
                            }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <?php
    $general->onclickChangeJavaScript($tPTbl,'wcID');
    }
?>