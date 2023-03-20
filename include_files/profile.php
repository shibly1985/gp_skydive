<?php

    $u = $userData; 
    $cFullNmae=$db->permission(PROFILE_CHANGE_FULL_N);
    $cPassword=$db->permission(PROFILE_CHANGE_PASSWORD);
    $cDisplayName=$db->permission(PROFILE_CHANGE_DISPLAY_N);
    $cCommentFlow=false;
    $cWallpostFlow=false;
    if(isset($_POST['edit'])){
        if($cFullNmae==true){
            $name           = $_POST['name'];
        }
        else{
            $name       = $userData['uFullName'];
        }
        if($cDisplayName==true){
            $displayname  =$_POST['displayname'];
        }
        else{
            $displayname  =$userData['uDisplayName'];  
        }
        /*  if($cCommentFlow==true){
        $uCommentFlow = $_POST['uCommentFlow'];
        if($uCommentFlow==0){
        $uCommentFlowDate = strtotime($_POST['uCommentFlowDate']);
        }
        else{
        $uCommentFlowDate = $u['uCommentFlowDate'];  
        }
        }
        else{
        $uCommentFlow = $u['uCommentFlow']; 
        }
        if($cWallpostFlow==true){
        $uWallpostFlow = $_POST['uWallpostFlow'];
        if($uWallpostFlow==0){
        $uWallpostFlowDate = strtotime($_POST['uWallpostFlowDate']);
        }
        else{
        $uWallpostFlowDate = $u['uWallpostFlowDate'];  
        }
        }
        else{
        $uWallpostFlow = $u['uWallpostFlow']; 
        }*/

        if(empty($name)){SetMessage(array(36,'Full name'));$error=1;}
        $encPas = $u['uPassword'];
        if($cPassword==true){
            if(isset($_POST['chp'])){
                $password   = $_POST['password'];
                $re_pass    = $_POST['re_password'];   
                if(empty($password)){SetMessage(36,'Password');$error=1;}
                elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@^#$%]{8,120}$/', $password)) {
                    $error=__LINE__;SetMessage(8);
                }
                elseif($password!=$re_pass){SetMessage(54);$error=1;}
                $encPas = hash('sha512',md5($password.$u['uPassSalt']));
            }
        }

        $imageName=$u['uImage']; 
        if(isset($_FILES["file"]["name"])){
            $fileName   = $_FILES["file"]["name"];
            if(!empty($fileName)){
                $tempImage  = $_FILES['file']['tmp_name']; 
                $extension=explode('.', $fileName);
                $extension=end($extension);
                $extension=strtolower($extension);
                if(
                    $extension == 'jpeg' || 
                    $extension == 'jpg' || 
                    $extension == 'png'){
                    if($_FILES['file']['size']<1024*1000){
                        $imageName = UID.'_'.rand(10,99).'.'.$extension;
                        $destination= 'images/operators/';
                        $move=move_uploaded_file($tempImage,$destination.$imageName);
                        if($move){
                            $nf=1;
                        }
                    }
                    else{
                        $general->SetMessage('Please upload only following size: Max 1mb','e');$error = 1; 
                    }
                }
                else{
                    $general->SetMessage('Please upload only following type file: jpeg,jpg,png','e');$error = 1; 
                }
            }
        }
        else{
            $imageName=$u['uImage']; 
        }
        if(!isset($error)){
            $data = array(
                'uFullName'         => $name,
                'uDisplayname'      => $displayname,
                /*'uCommentFlow'      => $uCommentFlow,
                'uCommentFlowDate'  => $uCommentFlowDate,
                'uWallpostFlow'     => $uWallpostFlow,
                'uWallpostFlowDate' => $uWallpostFlowDate,*/
                'uImage'            => $imageName,
                'uPassword'         => $encPas,
                'modifiedBy'        => UID,
                'modifiedOn'        => TIME,
            );
            $where=array('uID'=>UID);
            $update=$db->update($general->table(17),$data,$where);
            if($update){
                if(isset($nf)){if(!empty($u['uImage'])){unlink($destination.$u['uImage']); }}
                $general->redirect($pUrl,30,'User');
            }
            else{
                if(isset($nf)){unlink($destination.$imageName);}
                SetMessage(66);
            }
        }
    }
    $data = array($pUrl=>$rModule['cmTitle'],1=>'Agent Profie');$general->breadcrumb($data);

    show_msg();
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Agent Profile</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data" method="POST">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Full name :<?php if($cFullNmae==true){echo '<span class="required">*</span>';}?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                            if($cFullNmae==true){
                            ?><input class="form-control col-md-7 col-xs-12" id="title" type="text" name="name" required="required" value="<?php echo $u['uFullName'];?>"><?php
                            }
                            else{echo $u['uFullName'];}
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Display Name :<?php if($cDisplayName==true){echo '<span class="required">*</span>';}?></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                            if($cDisplayName==true){
                            ?><input class="form-control col-md-7 col-xs-12" id="title" type="text" name="displayname" required="required" value="<?php echo $u['uDisplayName'];?>"><?php
                            }
                            else{echo $u['uDisplayName'];}
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Username:</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo $u['uLoginName'];?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Change Image :</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" class="form-control col-md-7 col-xs-12" name="file">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Comment Flow :</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                            /* if($cCommentFlow==true){
                            ?>
                            <select name="uCommentFlow" class="form-control">
                            <option value="0" <?php if($u['uCommentFlow']==0){echo 'selected';}?>>FIFO</option>
                            <option value="1" <?php if($u['uCommentFlow']==1){echo 'selected';}?>>LIFO</option>
                            </select>
                            <?php
                            }
                            else{*/
                            if($u['uCommentFlow']==WALL_POST_COMMENT_FLOW_FIFO){echo 'FIFO';}else{echo "LIFO";}
                            /*}  */
                        ?>
                    </div>
                </div> 
                <?php
                    if($u['uCommentFlow']==0){
                    ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Comment Flow Date:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php    if($cCommentFlow==true){  ?>
                                <input type="text" name="uCommentFlowDate" style="position:initial;" class="daterangepicker form-control" value="<?php echo $general->make_date($u['uCommentFlowDate']);?>">
                                <?php }else{echo $u['uCommentFlowDate'];} ?>
                        </div>
                    </div>
                    <?php
                    }
                ?>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Wall Post Flow :</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                            /*if($cWallpostFlow==true){
                            ?>
                            <select name="uWallpostFlow" class="form-control">
                            <option value="0" <?php if($u['uWallpostFlow']==0){echo 'selected';}?>>FIFO</option>
                            <option value="1" <?php if($u['uWallpostFlow']==1){echo 'selected';}?>>LIFO</option>
                            </select>
                            <?php
                            }
                            else{
                            if($u['uWallpostFlow']==0){echo 'FIFO';}
                            if($u['uWallpostFlow']==1){echo 'LIFO';}
                            } */ 
                            if($u['uWallpostFlow']==WALL_POST_COMMENT_FLOW_FIFO){echo 'FIFO';}else{echo "LIFO";}
                        ?>
                    </div>
                </div> 
                <?php
                    /*if($u['uWallpostFlow']==0){
                    ?>
                    <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Wall Post Flow Date:</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php    if($cWallpostFlow==true){  ?>
                    <input type="text" name="uWallpostFlowDate" style="position:initial;" class="daterangepicker form-control" value="<?php echo $general->make_date($u['uWallpostFlowDate']);?>">
                    <?php }else{echo $u['uWallpostFlowDate'];} ?>
                    </div>
                    </div>
                    <?php
                    }*/
                ?>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Currrent Image :</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                            if(!empty($u['uImage'])){
                            ?>

                            <img  style="width: 100px;" class="col-md-7 col-xs-12" src="images/operators/<?php echo $u['uImage'] ?>" alt="">
                            <?php
                            }else{
                            ?>
                            <span class="col-md-7 col-xs-12"><?php  echo 'No Image'; ?></span>
                            <?php

                            }
                        ?>

                    </div>
                </div>
                <?php
                    if($cPassword==true){
                    ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Change Password </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="checkbox" id="change_password" name="chp" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">New Password:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="password" value=""  name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Confirm New Password:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="password"  value="" name="re_password">
                        </div>
                    </div>
                    <?php
                    }
                ?>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <input type="submit" class="btn btn-success" value="Update" name="edit">
                    </div>
                </div>

            </form>
        </div>
    </div>
    </div>