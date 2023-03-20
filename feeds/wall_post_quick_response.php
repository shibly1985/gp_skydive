<div class="col-md-3">
    <div class="x_panel wall_post">
        <div data-example-id="togglable-tabs" role="tabpanel" class="">
            <div class="x_title">
                <h2>Quick Response</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="add-on">
                    <input type="text" onkeyup="filter(this,'.responsText')" class="form-control responseSearch" placeholder="Search" name="">
                </div>
                <ul role="tablist" class="nav nav-tabs bar_tabs" id="myTab">
                    <li class="active" role="presentation"><a aria-expanded="true" data-toggle="tab" role="tab" id="home-tab" href="#tab_content1">Favourite</a>
                    </li>
                    <li class="" role="presentation"><a aria-expanded="true" data-toggle="tab" id="profile-tab" role="tab" href="#tab_content2">Response</a>
                    </li>
                    <li class="" title="Own Response" role="presentation"><a aria-expanded="false" data-toggle="tab" id="profile-tab2" role="tab" href="#tab_content3">OR</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div aria-labelledby="home-tab" id="tab_content1" class="tab-pane fade active in" role="tabpanel">
                        <ul id="fav_msg_tem" class="responsText">
                            <?php
                                $favourite =   $db->selectAll($general->table(24),'where uID='.UID);
                                $favID=array();
                                foreach($favourite as $f){
                                    $templates = $db->selectAll($general->table(23),'where  mtID='.$f['mtID']);
                                    foreach($templates as $t){
                                        $txt=$social->messageTemplateMake($t['mtText']);
                                        $favID[$f['mtID']]=$f['mtID'];
                                    ?>
                                    <li id="fmt_<?php echo $f['fmtID'];?>">
                                        <i class="fa fa-remove" onclick="removeFav('<?php echo $f['fmtID'];?>')" style="cursor:pointer"></i>
                                        <a href="javascript:void(0);" id="fav_<?php echo $f['mtID'];?>" class="template" data-title="<?php echo $t['mtTitle']; ?>" data-text="<?php echo $general->content_show($txt);?>" onclick="useTemplate('<?php echo $t['mtID'];?>',2)"><?php echo $t['mtTitle']; ?></a>
                                    </li>
                                    <?php
                                    }   
                                }

                            ?>
                        </ul>
                    </div>
                    <div aria-labelledby="profile-tab" id="tab_content2" class="tab-pane fade" role="tabpanel">
                        <!--<div class="quick_response_search">
                            <form role="search">
                                <div class="add-on">
                                    <input type="text" onkeyup="filter(this,'#templateList')" class="form-control responseSearch" placeholder="Search" name="">
                                </div>
                            </form>
                        </div>-->
                        <ul id="templateList" class="responsText">
                            <?php
                                $templates =   $db->selectAll($general->table(23),'where mtType=0 and isActive=1');
                                foreach($templates as $t){
                                ?>
                                <li>
                                    <i class="fa fa-eye" onclick="viewTemplate(<?php echo $t['mtID']; ?>)" style="cursor:pointer"> </i>
                                    <?php
                                        if(!in_array($t['mtID'],$favID)){
                                        ?>
                                        <i class="fa fa-heart" onclick="makeFav('<?php echo $t['mtID'];?>')" style="cursor:pointer"> </i>
                                        <?php
                                        }
                                    ?>
                                    <?php
                                        $txt=$social->messageTemplateMake($t['mtText']);
                                        $text = str_ireplace("'","&#39;",$txt);
                                        $text = str_ireplace('"',"&#34;",$text);
                                    ?> 
                                    <a href="javascript:void(0);" class="template" data-title="<?php echo $t['mtTitle']; ?>" data-text="<?php echo $general->content_show($text,'n');?>" id="tmp_<?php echo $t['mtID']; ?>" onclick="useTemplate('<?php echo $t['mtID'];?>',0)"><?php echo $t['mtTitle'];?></a>
                                </li>
                                <?php 
                                }
                            ?>
                        </ul>
                    </div>
                    <div aria-labelledby="profile-tab" id="tab_content3" class="tab-pane fade" role="tabpanel">
                        <div class="quick_response_search">

                        </div>
                        <ul id="responseList" class="responsText" style="margin-bottom:10px">
                            <?php
                                $responses = $db->selectAll($general->table(23),'where isActive=1 and mtType=1 and createdBy='.UID);
                                foreach($responses as $r){
                                ?>
                                <li id="tm_<?php echo $r['mtID'];?>"> 
                                    <i class="fa fa-remove" onclick="removeResponse(<?php echo $r['mtID']; ?>)" style="cursor:pointer"> </i>  
                                    <i class="fa fa-eye" onclick="viewResponse(<?php echo $r['mtID']; ?>)" style="cursor:pointer"> </i> 
                                    <?php
                                        if(!in_array($r['mtID'],$favID)){
                                        ?>
                                        <i class="fa fa-heart" onclick="makeFav('<?php echo $r['mtID'];?>')" style="cursor:pointer"> </i>
                                        <?php
                                        }
                                    ?>
                                    <a href="javascript:void(0);" id="res_<?php echo $r['mtID']; ?>" data-text="<?php echo $general->content_show($r['mtText'],'n'); ?>" class="template" onclick="useTemplate('<?php echo $r['mtID'];?>',1)"><?php echo $r['mtTitle'];?></a>
                                </li>
                                <?php
                                }
                            ?>
                        </ul>
                        <button class="btn btn-primary btn-xs pull-right"  data-toggle="modal" data-target="#modal">Add New</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="tvModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Response View</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Title:</label>
                        <div class="col-sm-10">
                            <p id="ttitleView"> </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Message:</label>
                        <div class="col-sm-10">
                            <p id="tmessageView"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="rvModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Response View</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Title:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="titleView" placeholder="Response Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Message:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="3" id="messageView" placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-10" id="updateBtn">
                            <button class="btn btn-success btn-sm" onclick="editResponse();">Update</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!-- Modal -->
<div id="modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Response</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Title:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" placeholder="Response Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Message:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="3" id="response" placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-10">
                            <button class="btn btn-success btn-sm" onclick="addResponse();">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div> 