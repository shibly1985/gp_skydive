<?php
    if(UGID!=SUPERADMIN_USER){$general->redirect(URL,array(52,$rModule['cmTitle']));}
    $tPTbl=1;
    if(isset($_GET['permission'])){
        $edit = intval($_GET['permission']);
        $m = $db->get_rowData($general->table($tPTbl),'cmId',$edit);
        if(empty($m)){$general->redirect($pUrl,array(37,'module'));}
        $mUrl = $pUrl.'&permission='.$edit;
        if(isset($_GET['add'])){
            if(isset($_POST['add'])){
                $title = $_POST['title'];
                if(empty($title)){SetMessage(array(36,'Description'));$error=1;}
                if(!isset($error)){
                    $data = array(
                        'cmId'  => $edit,
                        'perDesc' => $title
                    );
                    $insert = $db->insert($general->table(19),$data);
                    if($insert){
                        $general->redirect($mUrl,array(29,'permission'));
                    }
                }
            }

            $data = array($pUrl=>'Moduls',$mUrl=>'Set permission',1=>'Add permission');$general->breadcrumb($data);
        ?>
        <h3>Add permission for <?=$m['cmTitle']?></h3><br>
        <?php show_msg();?>
        <form action="" class="form-horizontal" method="POST">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Description :</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="title" maxlength="20">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                <div class="col-sm-4">
                  <input type="submit" style="float: left;" class="btn btn-success create_button" name="add" value="Submit">
                </div>
            </div>
        </form>
        <?php    
        }
        else{
            $data = array($pUrl=>'Moduls','javascript:void()'=>$m['cmTitle'],1=>'Set permission');$general->breadcrumb($data);
        ?>
        <h2 class="sub-header hTit">Set permission for <?=$m['cmTitle']?> <a href="<?=$mUrl?>&add" class="btn btn-success create_button">Add New permission</a></h2>
        <?php show_msg();?>
        <table class="table">
            <tr>
                <td>Id</td>
                <td>Description</td>
            </tr>
            <?php
                $per = $db->selectAll($general->table(19),'where cmId='.$edit);
                foreach($per as $p){
                ?>
                <tr>
                    <td><?=$p['perID']?></td>
                    <td><?=$p['perDesc']?></td>
                </tr>
                <?php
                }
            ?>
        </table>
        <?php    
        }
    }
    else{
        if(isset($_GET['add'])){
            if(isset($_POST['add'])){
                $title = $_POST["title"];
                $slug = $_POST["slug"];
                $folder = $_POST["folder"];
                $pageneame = $_POST["pageneame"];
                $parent = intval($_POST["parent"]);
                if(empty($title)){SetMessage(5);$error=1;}
                if(empty($slug)){$slug=$general->make_url($title);}

                if($parent!=0){
                    $pData = $db->get_rowData($general->table($tPTbl),'cmId',$parent);
                    if(!empty($pData)){if($pData['cmParent'] != 0){SetMessage(50);$error=1;}}
                    else{SetMessage(11);$error=1;}
                }
                if(!isset($error)){
                    $data = array(
                        'cmTitle'   => $title,
                        'cmParent'  => $parent,
                        'cmSlug'    => $slug,
                        'cmFolder'  => $folder,
                        'cmPageName'=> $pageneame
                    );
                    $insert = $db->insert($general->table($tPTbl),$data);
                    if($insert){$general->redirect($pUrl,array(29,'Module'));}
                }
            }
            $data = array($pUrl=>'Module','1'=>'Add');$general->breadcrumb($data);
        ?>
		
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
			  <div class="x_title">
				<h2>Add module</h2>
				<ul class="nav navbar-right panel_toolbox">
				  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				  </li>
				  <li><a class="close-link"><i class="fa fa-close"></i></a>
				  </li>
				</ul>
				<?php show_msg();?>
				<div class="clearfix"></div>
			  </div>
			  <div class="x_content">
				<form class="form-horizontal form-label-left" data-parsley-validate="" id="demo-form2" novalidate="" method="post">
				  <div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Title:</label>
					<div class="col-sm-4">
					  <input class="form-control" type="text" name="title" required="required" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Slug:</label>
					<div class="col-sm-4">
					  <input class="form-control" type="text" name="slug" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Folder:</label>
					<div class="col-sm-4">
					  <input class="form-control" type="text" name="folder" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Pagename:</label>
					<div class="col-sm-4">
					  <input class="form-control" type="text" name="pageneame" value="">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">Parent:</label>
					<div class="col-sm-4">
					  <select class="form-control" name="parent" class="select_box">
							<option value="">Parent</option>
							<?php
								$sqlQuery = "where cmParent=0 order by cmTitle asc";
								$mod   = $db->selectAll($general->table($tPTbl),$sqlQuery);
								foreach($mod as $mo){
								?>
								<option value="<?=$mo['cmId']?>"><?=$mo['cmTitle']?></option>
								<?php
								}
							?>
						</select>
					</div>
				</div>
				  <div class="ln_solid"></div>
				  <div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
					  <input type="submit" name="add" value="Submit" class="btn btn-success create_button">
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
            $m = $db->get_rowData($general->table($tPTbl),'cmId',$edit);
            if(empty($m)){$general->redirect($pUrl,array(37,'module'));}

            if(isset($_POST['edit'])){
                $title = $_POST["title"];
                $slug = $_POST["slug"];
                $folder = $_POST["folder"];
                $pageneame = $_POST["pageneame"];
                $parent = intval($_POST["parent"]);
                if(empty($title)){SetMessage(5);$error=1;}
                if(empty($slug)){$slug=$general->make_url($title);}
                if($parent!=0){
                    $pData = $db->get_rowData($general->table($tPTbl),'cmId',$parent);
                    if(!empty($pData)){if($pData['cmParent'] != 0){SetMessage(50);$error=1;}}
                    else{SetMessage(51);$error=1;}
                }
                if(!isset($error)){
                    $data = array(
                        'cmTitle'   => $title,
                        'cmParent'  => $parent,
                        'cmSlug'    => $slug,
                        'cmFolder'  => $folder,
                        'cmPageName'=> $pageneame
                    );
                    $where = array('cmId'=>$edit);
                    $update = $db->update($general->table($tPTbl),$data,$where);
                    if($update){$general->redirect($pUrl,30,'Module');}
                }
            }
            $data = array($pUrl=>'Module','1'=>'Edit');$general->breadcrumb($data);
        ?>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Edit module</h2>
					<ul class="nav navbar-right panel_toolbox">
					  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					  </li>
					  <li><a class="close-link"><i class="fa fa-close"></i></a>
					  </li>
					</ul>
					<?php show_msg();?>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal" method="post">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Title:</label>
							<div class="col-sm-4">
							  <input type="text" class="form-control" name="title" required="required" value="<?=$m['cmTitle']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Slug:</label>
							<div class="col-sm-4">
							  <input type="text" class="form-control" name="slug" value="<?=$m['cmSlug']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Folder:</label>
							<div class="col-sm-4">
							  <input type="text" class="form-control" name="folder" value="<?=$m['cmFolder']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Pagename:</label>
							<div class="col-sm-4">
							  <input type="text" class="form-control" name="pageneame" value="<?=$m['cmPageName']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label">Parent:</label>
							<div class="col-sm-4">
							  <select class="form-control" name="parent">
									<option value="">Parent</option>
									<?php
										$sqlQuery = "where cmParent=0 order by cmTitle asc";
										$mod   = $db->selectAll($general->table($tPTbl),$sqlQuery);
										foreach($mod as $mo){
										?>
										<option value="<?=$mo['cmId']?>" <?=$general->selected($mo['cmId'],$m['cmParent'])?>><?=$mo['cmTitle']?></option>
										<?php
										}
									?>
								</select>
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-2 control-label"></label>
							<div class="col-sm-4">
							  <input type="submit" name="edit" value="Submit" class="btn btn-success create_button">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
        <?php
        }
        else{
            $sqlQuery = "where cmParent=0 order by cmOrder asc";
            $mod   = $db->selectAll($general->table($tPTbl),$sqlQuery);
            $data = array(1=>'Moduls');$general->breadcrumb($data);
        ?>
		
		
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
			  <div class="x_title">
				<h2>Moduls <small><a href="<?=$pUrl?>&add" class="btn btn-success create_button">Add Module</a></small></h2>
				<ul class="nav navbar-right panel_toolbox">
				  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				  </li>
				  <li><a class="close-link"><i class="fa fa-close"></i></a>
				  </li>
				</ul>
				<?php show_msg();?>
				<div class="clearfix"></div>
				
			  </div>
			  <div class="x_content">

				<table  class="table table-striped users_view_table">
				  <thead>
					<tr>
						<th>cmID</th>
						<th>Title</th>
						<th>Parent</th>
						<th>Slug</th>
						<th>Folder</th>
						<th>Pagename</th>
						<th width="50">Edit</th>
						<th width="80">Permission</th>
						<th width="70">Status</th>
					</tr>
				  </thead>
				  <tbody>
					 <?php
						foreach($mod as $m){
							$sqlQuery = " where cmParent=".$m['cmId']." order by cmTitle asc";
							$smod   = $db->selectAll($general->table($tPTbl),$sqlQuery);
						?>
						<tr>
							<td><b><?=$m['cmId']?></b></td>
							<td><?=$m['cmTitle']?></td>
							<td>-</td>
							<td><?=$m['cmSlug']?></td>
							<td><?=$m['cmFolder']?></td>
							<td><?=$m['cmPageName']?></td>
							<td>
								<a href="<?=$pUrl?>&edit=<?=$m['cmId']?>">
									<button  class="btn btn-sm btn-default">Edit</button>
								</a>
							</td>
							<td>
								<a href="<?=$pUrl?>&permission=<?=$m['cmId']?>">
									<button class="btn btn-sm btn-default">Permission</button>
								</a>
							</td>
							<td><?php $general->onclickChangeBTN($m['cmId'],$general->checked($m['isActive']));?></td>
						</tr>
						<?php
							if(!empty($smod)){
								foreach($smod as $sm){
								?>
								<tr>
									<td><?=$sm['cmId']?></td>
									<td><?=$sm['cmTitle']?></td>
									<td><?=$m['cmTitle']?></td>
									<td><?=$sm['cmSlug']?></td>
									<td><?=$sm['cmFolder']?></td>
									<td><?=$sm['cmPageName']?></td>
									<td>
										<a href="<?=$pUrl?>&edit=<?=$sm['cmId']?>">
											<button>Edit</button>
										</a>
									</td>
									<td>
										<a href="<?=$pUrl?>&permission=<?=$sm['cmId']?>">
											<button>Permission</button>
										</a>
									</td>
									<td><?php $general->onclickChangeBTN($sm['cmId'],$general->checked($sm['isActive']));?></td>
								</tr>
								<?php
								}
							}
						}
					?>
				  </tbody>
				</table>

			  </div>
			</div>
		</div>
        <?php
            $general->onclickChangeJavaScript($tPTbl,'cmId');
        }
    }
?>