<?php
    $tPTbl=17;
    $aStatus=$db->permission(PER_ADD_AGENT);
    $dStatus=$db->permission(PER_USER_DELETE);
    $licence=intval($db->settingsValue('userLicence'));
    $totalUser=count($db->allUsers('and isActive in(0,1)'));
    if(isset($_GET['add'])){
        if($aStatus==false){$general->redirect($pUrl,82,'Add '.$rModule['cmTitle']);}
        if($licence<=$totalUser){if(UGID!=SUPERADMIN_USER){$general->redirect($pUrl,7);}}
        $data = array($pUrl=>$rModule['cmTitle'],1=>'Add');$general->breadcrumb($data);

        if(isset($_POST['add'])){
            $name       = $_POST['name'];
            $displayname= $_POST['displayname'];
            $username   = $_POST['username'];
            $group      = $_POST['group'];
            $password   = $_POST['password'];
            $re_pass    = $_POST['re_password'];
            $contact    = $_POST['contact'];
            $email      = $_POST['email'];

            if(empty($name)){SetMessage(36,'Full name');$error=1;}
            if(empty($username)){SetMessage(36,'Username');$error=1;}
            else{
                $check = $db->check_available($general->table($tPTbl)," where uLoginName = '".$username."'");
                if($check==false){SetMessage(127,'Username');$error=1;}
            }
            $ug=$db->get_rowData($general->table(22),'ugID',$group);
            if(empty($ug)){$error=__LINE__;SetMessage(63,'Group'); }
            if(empty($password)){SetMessage(array(36,'Password'));$error=1;}
            elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@^#$%]{8,120}$/', $password)) {
                $error=__LINE__;SetMessage(8);
            }
            elseif($password!=$re_pass){SetMessage(54);$error=1;}

            $imageName='';
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
                        if($_FILES['file']['size']<1024*5000){
                            $imageName = str_ireplace('.'.$extension,'',$fileName).'_'.time().'-'.rand(9,99).'.'.$extension;
                            $destination= 'images/operators/';
                            $move=move_uploaded_file($tempImage,$destination.$imageName);
                            $nf=1;
                        }
                        else{
                            $general->SetMessage('Please upload only following size: Max 5md','e');$error = 1; 
                        }
                    }
                    else{
                        $general->SetMessage('Please upload only following type file: jpeg,jpg,png','e');$error = 1; 
                    }
                }
            }


            if(!isset($error)){
                $salt = md5(rand(0,9).'S'.rand(0,9).'a@'.rand(0,9).'l'.rand(0,9).'a'.rand(0,9).'m');
                $encPas = hash('sha512',md5($password.$salt));
                $data = array(
                    'uFullName' => $name,
                    'uDisplayName' => $displayname,
                    'uLoginName'=> $username,
                    'uPassword' => $encPas,
                    'uImage'    => $imageName,
                    'ugID'      => $group,
                    'uPassSalt' => $salt,
                    'uContact'  => $contact,
                    'uEmail'    => $email,
                );
                $general->arrayUserInfoAdd($data);
                $insert = $db->insert($general->table($tPTbl),$data);
                if($insert){
                    $general->redirect($pUrl,29,'Operator');
                }
                else{SetMessage(66);}
            }
        }
    ?>
    <?php show_msg()?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Agent</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Full name :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="name" required="required" value="<?=@$_POST['name']?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Display Name:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="displayname" required="required" value="<?=@$_POST['displayname']?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Username:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="username" required="required" value="<?=@$_POST['username']?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">User Group::<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="group" class="form-control col-md-7 col-xs-12" required="required">
                                <option value="">Select</option>
                                <?php
                                    $group= $db->allGroups();
                                    foreach($group as $g){
                                    ?><option <?php echo  $general->selected($g['ugID'],@$_POST['group'])?>  value="<?=$g['ugID']?>"><?=$g['ugTitle']?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Password:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="password" required="required" value=""  name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Confirm password:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" type="password" required="required" value="" name="re_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Contact info :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input value="<?php echo @$_POST['contact'] ?>" class="form-control col-md-7 col-xs-12" type="text"  name="contact">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Email :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input value="<?php echo @$_POST['email'] ?>" class="form-control col-md-7 col-xs-12" type="text"  name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Image :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" class="form-control col-md-7 col-xs-12" name="file">
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
        //if($eStatus==false){$general->redirect(URL,59,$rModule['cmTitle']);}
        $uID = intval($_GET['edit']);
        //"select * from useraccount where uID=$uID";
        //query
        //fectch_assoc
        $u = $db->get_rowData($general->table($tPTbl),'uID',$uID);
        if(empty($u)){$general->redirect($pUrl,37,'agent');}
        elseif($u['isActive']==3){$general->redirect($pUrl,37,'agent');}
        if(isset($_POST['edit'])){
            $name           = $_POST['name'];
            $displayname    = $_POST['displayname'];
            $username       = $_POST['username'];
            $contact        = $_POST['contact'];
            $email          = $_POST['email'];
            $group          = $_POST['group'];
            if(empty($name)){SetMessage(array(36,'Full name'));$error=1;}
            if(empty($username)){SetMessage(array(36,'Username'));$error=1;}
            else{
                $check = $db->check_available($general->table($tPTbl)," where uLoginName = '".$username."' and uID!=".$uID);
                if($check==false){SetMessage(127,'Username');$error=1;}
            }
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
            else{
                $encPas = $u['uPassword'];
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
                        if($_FILES['file']['size']<1024*5000){
                            $imageName = str_ireplace('.'.$extension,'',$fileName).'_'.time().'-'.rand(9,99).'.'.$extension;
                            $destination= 'images/operators/';
                            $move=move_uploaded_file($tempImage,$destination.$imageName);
                            if($move){
                                if(!empty($u['uImage'])){
                                    unlink($destination.$u['uImage']); 
                                }
                            }
                            $nf=1;
                        }
                        else{
                            $general->SetMessage('Please upload only following size: Max 5md','e');$error = 1; 
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
            $cWallpostFlow = $_POST['uCommentFlow'];
            if($cWallpostFlow!=WALL_POST_COMMENT_FLOW_FIFO)$cWallpostFlow=WALL_POST_COMMENT_FLOW_LIFO;
            $uWallpostFlow = $_POST['uWallpostFlow'];
            if($uWallpostFlow!=WALL_POST_COMMENT_FLOW_FIFO)$uWallpostFlow=WALL_POST_COMMENT_FLOW_LIFO;
            if(!isset($error)){
                $data = array(
                    'uFullName'     => $name,
                    'uDisplayName'  => $displayname,
                    'uContact'      => $contact,
                    'ugID'          => $group,
                    'uEmail'        => $email,
                    'uImage'        => $imageName,
                    'uLoginName'    => $username,
                    'uCommentFlow'  => $cWallpostFlow,
                    'uWallpostFlow' => $uWallpostFlow,
                    'uPassword'     => $encPas,
                    'modifiedBy'    => UID,
                    'modifiedOn'    => TIME,
                );
                $where=array('uID'=>$uID);
                $update=$db->update($general->table($tPTbl),$data,$where);
                if($update){
                    $general->redirect($pUrl,30,'Agent');
                }
                else{SetMessage(66);}
            }
        }
        $data = array($pUrl=>$rModule['cmTitle'],'javascript:void()'=>$u['uFullName'],1=>'Edit');$general->breadcrumb($data);
    ?>
    <?php show_msg()?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Agent</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form action="" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Full Name :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="name" required="required" value="<?php echo $u['uFullName'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Display Name :<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="displayname" required="required" value="<?php echo $u['uDisplayName'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Comment Flow :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="uCommentFlow" class="form-control">
                                <option value="<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>" <?php if($u['uCommentFlow']==WALL_POST_COMMENT_FLOW_FIFO){echo 'selected';}?>>FIFO</option>
                                <option value="<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>" <?php if($u['uCommentFlow']==WALL_POST_COMMENT_FLOW_LIFO){echo 'selected';}?>>LIFO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Wall Post Flow :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="uWallpostFlow" class="form-control">
                                <option value="<?php echo WALL_POST_COMMENT_FLOW_FIFO;?>" <?php if($u['uWallpostFlow']==WALL_POST_COMMENT_FLOW_FIFO){echo 'selected';}?>>FIFO</option>
                                <option value="<?php echo WALL_POST_COMMENT_FLOW_LIFO;?>" <?php if($u['uWallpostFlow']==WALL_POST_COMMENT_FLOW_LIFO){echo 'selected';}?>>LIFO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Username:<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" id="title" type="text" name="username" required="required" value="<?=$u['uLoginName']?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">User Group::<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="group" class="form-control col-md-7 col-xs-12" required="required">
                                <option value="">Select</option>
                                <?php
                                    $group= $db->allGroups();
                                    foreach($group as $g){
                                    ?><option <?php if($u['ugID']==$g['ugID']){?>selected="selected" <?php } ?> value="<?=$g['ugID']?>"><?=$g['ugTitle']?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Image :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" class="form-control col-md-7 col-xs-12" name="file">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Image :</label>
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

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Change Password</label>
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
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Contact info :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" value="<?=$u['uContact']?>" type="text"  name="contact">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Email :</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control col-md-7 col-xs-12" value="<?=$u['uEmail']?>" type="text"  name="email">
                        </div>
                    </div>
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
    <?php 
    }
    elseif(isset($_GET['remove'])){
        if($dStatus==false){$general->redirect($pUrl,82,'Remove '.$rModule['cmTitle']);}
        $uID = intval($_GET['remove']);
        if(UID==$uID){$general->redirect($pUrl,5,'Self remove not possible.');}
        $u = $db->get_rowData($general->table($tPTbl),'uID',$uID);
        if(empty($u)){$general->redirect($pUrl,63,'Agent delete request');}
        elseif($u['isActive']==3){$general->redirect($pUrl,63,'Agent delete request');}
        $data = array(
            'isActive'      => 3,
            'uPassword'     => rand(9,99999),
            'uPassSalt'     => rand(9,99999),
            'modifiedBy'    => UID,
            'modifiedOn'    => TIME,
        );
        $where=array('uID'=>$uID);
        $update=$db->update($general->table($tPTbl),$data,$where);
        if($update){
            $general->redirect($pUrl,30,'Agent');
        }
        else{$general->redirect($pUrl,66);}
    }
    else{
        $data=array();
        $archive=0;if(isset($_GET['archive'])){$archive=1;}
        $data[$pUrl]=$rModule['cmTitle'];
        if($archive==1){
            $data[1]='Archive';
        }
        //$data = array('1'=>$rModule['cmTitle']);

        if($archive==0){
            $sq=' and u.isActive in(0,1)';
        }
        else{
            $sq=' and u.isActive in(3)';
        }
        $general->breadcrumb($data);
        $query="SELECT 
        u.uFullName,u.uDisplayName,u.uLoginName,u.uID,ug.ugID,ug.ugTitle,u.IsActive
        FROM ".$general->table(17)." u
        left join ".$general->table(22)." ug on(ug.ugID=u.ugID)
        where u.ugID!=".SUPERADMIN_USER.$sq;
        $rowPP=50;
        if(isset($_GET[PAGE_INATION_CURRENT_PAGE_NAME])){$cp=$_GET[PAGE_INATION_CURRENT_PAGE_NAME];}else{$cp=1;}
        $pageination    = $general->pagination_init_customQuery($query,$rowPP,$cp);
        $users          = $db->fetchQuery($query.$pageination['limit'],$general->showQuery());
        $total          = $pageination['start'];
        $link           = $pUrl;
        if($archive==1){$link= '&archive';}
        $link.='&'.PAGE_INATION_CURRENT_PAGE_NAME.'=';
    ?>
    <?php show_msg()?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $rModule['cmTitle'];?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a href="<?php echo $pUrl;?>&archive">Archive</a></li>
                    <?php
                        if($aStatus==true&&$licence>$totalUser||UGID==SUPERADMIN_USER){
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
                            <th>Full Name</th>
                            <th>Display Name</th>
                            <th>Operators Name</th>
                            <th>Operators Group</th>
                            <?php
                                if($archive==0){
                                ?>
                                <th width="40">Edit</th>
                                <?php
                                    if($dStatus==true){
                                    ?>
                                    <th width="40">Delete</th>
                                    <?php
                                    }
                                ?>
                                <th width="75">Status</th>
                                <?php
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($users as $u){
                            ?>
                            <tr>
                                <td><?=$total++?></td>
                                <td><?=$u['uFullName']?></td>
                                <td><?=$u['uDisplayName']?></td>
                                <td><?=$u['uLoginName']?></td>
                                <td><?=$u['ugTitle']?></td>
                                <?php
                                    if($archive==0){
                                    ?>
                                    <td>
                                        <a href="<?=$pUrl?>&edit=<?=$u['uID']?>"  class="btn btn-sm btn-default">
                                            Edit
                                        </a>
                                    </td>
                                    <?php
                                        if($dStatus==true){
                                        ?>
                                        <td>
                                            <a href="<?=$pUrl?>&remove=<?=$u['uID']?>"  class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to remove this agent?')">
                                                Delete
                                            </a>
                                        </td>
                                        <?php
                                        }
                                    ?>
                                    <td><?=$general->onclickChangeBTN($u['uID'],$general->checked(1,$u['IsActive']))?></td>
                                    <?php
                                    }
                                ?>
                            </tr>
                            <?php
                                $general->onclickChangeJavaScript($tPTbl,'uID');
                            }    
                        ?>
                    </tbody>
                </table>
                <ul class="pagination"><?php $general->pagination($pageination['currentPage'],$pageination['TotalPage'],$link.'&'.PAGE_INATION_CURRENT_PAGE_NAME.'=');?></ul>
            </div>
        </div>
    </div>
    <?php
    }
?>